<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\PasswordValidationRules;
use App\Models\Menu;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Services\PasswordPolicyService;
use ErrorException;
use Exception;
use Illuminate\Database\QueryException;
use InvalidArgumentException;

class UserController extends Controller
{
    use PasswordValidationRules;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::pluck('name', 'name');
        return view('administration.users.index', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $exist_user = User::whereEmail($request->email)->withTrashed()->first();

        if (!empty($exist_user)) {
            if (User::whereName($request->name)->count() > 0) {
                return $this->sendError('User name already exists!');
            }
            if ($exist_user->trashed()) {
                $exist_user->name = $request->name;
                $exist_user->updated_by = Auth::user()->id;
                $exist_user->landing_page = $request->landing_page;
                $exist_user->save();
                $exist_user->syncRoles($request->roles);
                $exist_user->restore();

                $pc = new PasswordPolicyService($exist_user);
                $pc->passwordChangeProcess();

                return $this->sendResponse($exist_user, 'User successfully added!');
            }
            return $this->sendError('Error', 'User already exits!');
        } else {
            $user = User::create(['name' => $request['name'], 'email' => $request['email'], 'password' => Hash::make($request['password']), 'landing_page' => $request['landing_page'], 'created_by' => Auth::user()->id]);

            $pc = new PasswordPolicyService($user);
            $pc->passwordChangeProcess();

            $user->syncRoles($request->roles);
            return $this->sendResponse($exist_user, 'User successfully added!');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {

        if (User::whereName($request->name)->where('id', '!=', $user->id)->withTrashed()->count() > 0) {
            return $this->sendError('User name already exists!');
        }

        if ($user->email != $request->email) {
            $existUser = User::whereEmail($request->email)
                ->withTrashed()
                ->exists();
            if ($existUser) {
                return $this->sendError('User already exits!');
            }
        }


        $user->name = $request->name;
        $user->email = $request->email;
        $user->updated_by = Auth::user()->id;
        $user->landing_page = $request->landing_page;
        $user->save();
        $user->syncRoles($request->roles);

        return $this->sendResponse($user, 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return $this->sendResponse('', 'User successfully deleted');
    }

    public function tableData(Request $request)
    {
        $user = Auth::user();
        $order_by = $request->order;
        $search = $request->search['value'];
        $start = $request->start;
        $length = $request->length;
        $order_by_str = $order_by[0]['dir'];

        $columns = ['name', 'email', 'id', 'landing_page'];
        $order_column = $columns[$order_by[0]['column']];

        $users = User::whereHas('roles', function ($query) {
            $query->where('level', '>=', Auth::user()->roles[0]->level);
        });

        if (is_null($search) || empty($search)) {
            $user_count = $users->count();
        } else {
            $users = $users->searchData($search);
            $user_count = $users->count();
        }

        $users = $users->tableData($order_column, $order_by_str, $start, (int)$length)->get();

        $data[][] = array();
        $i = 0;
        $edit_btn = null;
        $delete_btn = null;
        $reset_btn = null;
        $can_reset = ($user->can('reset password')) ? 1 : 0;
        $can_edit = ($user->can('users edit')) ? 1 : 0;
        $can_delete = ($user->can('users delete')) ? 1 : 0;
        $reset_attempts = ($user->can('reset attempts')) ? 1 : 0;
        $level = auth()->user()->roles[0]->level;
        foreach ($users as $key => $user) {

            if ($user->roles[0]->level < $level) {
                continue;
            }

            $attempts_btn = null;

            if ($reset_attempts) {
                if ($user->wrong_attempts >= 3 || !$user->enabled) {
                    $attempts_btn = "<i title='Unlock user' class='tooltip-enabled fas fa-unlock-alt mr-3 cursor-pointer' onclick=\"resetAttempt(this)\" data-id='{$user->id}'></i>";
                }
            }

            if ($can_reset) {
                $reset_btn = "<i title='Reset user' class='tooltip-enabled fas fa-sync-alt mr-3 cursor-pointer' onclick=\"reset(this)\" data-id='{$user->id}'></i>";
            }
            if ($can_edit) {
                $edit_btn = "<i title='Edit user' class='tooltip-enabled fas fa-edit mr-3 cursor-pointer' onclick=\"edit(this)\" data-id='{$user->id}' data-email='{$user->email}' data-name='{$user->name}' data-roles='{$user->getRoleNames()}' data-landing_page='{$user->landing_page}'></i>";
            }
            if ($can_delete) {
                $url = "users/{$user->id}";
                (Auth::user()->id !== $user->id) ? $delete_btn = "<i title='Delete user' class='tooltip-enabled fas fa-trash-alt mr-3 cursor-pointer' onclick=\"FormOptions.deleteRecord('{$user->id}','$url','dataTable')\"></i>" : $delete_btn = "";
            }

            $data[$i] = array(
                $user->name,
                $user->email,
                $user->getRoleNames(),
                $user->landing_page,
                $edit_btn . $delete_btn . $reset_btn . $attempts_btn
            );
            $i++;
        }

        if ($user_count == 0) {
            $data = [];
        }

        $json_data = [
            "draw" => intval($_REQUEST['draw']),
            "recordsTotal" => intval($user_count),
            "recordsFiltered" => intval($user_count),
            "data" => $data
        ];

        return json_encode($json_data);
    }

    public function resetPassword(Request $request, User $user)
    {

        $fields = [
            'password' =>  $this->passwordRules($user)
        ];

        $validator = Validator::make($request->all(), $fields);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $err_str = '';
            foreach ($errors as $error) {
                $err_str .= $error . " <br> ";
            }
            return $this->sendError($err_str, 'Required Fields Missing !');
        }

        $newPassword = $request->password;
        $user->password = Hash::make($newPassword);
        $user->password_changed_at = Carbon::now()->toDateTimeString();
        $user->save();

        $pc = new PasswordPolicyService($user);
        $pc->passwordChangeProcess();

        return $this->sendResponse($user, 'Password Reset Successfully');
    }

    public function resetLogin(Request $request, User $user)
    {
        if (Auth::user()->can('reset attempts')) {

            $meta = [
                'message' => "reset {$user->email} user account",
                'updated_by' => Auth::user()->id,
                'previous_record' => $user->toArray()
            ];
            Log::channel('event-log')->error(implode(",", ['success', json_encode($meta)]));

            if ($user->wrong_attempts >= 3 || !$user->enabled) {
                $user->wrong_attempts = 0;
                $user->last_login = Carbon::now();
                $user->enabled = 1;
                $user->save();
                return $this->sendResponse($user, 'Login attempt count reset successfully!');
            }
        } else {
            return $this->sendError('Login attempt count reset failed!');
        }
    }

    public function editProfile(Request $request)
    {
        $user = Auth::user();

        $role = Role::find(Auth::user()->roles[0]->id);
        $permissions = $role->permissions->pluck('name');

        $roles = Role::pluck('name', 'name');

        $home = ['home' => 'Dashboard'];
        $menu = Menu::whereNotNull('parent_id')->pluck('title', 'url')->toArray();
        $menu = array_merge($home, $menu);

        return view('administration.profile.index', compact('user', 'permissions', 'roles', 'menu'));
    }

    public function getLandingPageByRole(Request $request)
    {
        try {
            $landingPage = [];
            $role = $request->role;
            if (!empty($role)) {
                if (is_array($role)) {
                    $roleIds = DB::table('roles')->select('id')->whereIn('name', $role)->get()->pluck('id')->toArray();
                    if (!in_array(1, $roleIds)) {
                        $landingPage = DB::table('menu')
                            ->select('title', 'url')
                            ->join('permissions', 'menu.id', '=', 'permissions.menu_id')
                            ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                            ->whereIn('role_has_permissions.role_id', $roleIds)
                            ->whereNotNull('url')
                            ->pluck('title', 'url');
                    } else {
                        $landingPage = Menu::whereNotNull('url')->get()->pluck('title', 'url');
                    }
                } else {
                    if ($role !== "1") {
                        $landingPage = DB::table('menu')
                            ->select('title', 'url')
                            ->join('permissions', 'menu.id', '=', 'permissions.menu_id')
                            ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                            ->where('role_has_permissions.role_id', $role)
                            ->whereNotNull('url')
                            ->pluck('title', 'url');
                    } else {
                        $landingPage = Menu::whereNotNull('url')->get()->pluck('title', 'url');
                    }
                }
            }

            return $landingPage;
        } catch (InvalidArgumentException $exception) {
            return redirect()->back()->with(['alert-type' => 'error', 'message' => config('exception-errors.errors.page_not_found_error')]);
        } catch (ErrorException $exception) {
            return redirect()->back()->with(['alert-type' => 'error', 'message' => config('exception-errors.errors.undefine_variable_error')]);
        } catch (QueryException $exception) {
            return redirect()->back()->with(['alert-type' => 'error', 'message' => config('exception-errors.errors.db_error')]);
        } catch (Exception $exception) {
            return redirect()->back()->with(['alert-type' => 'error', 'message' => config('exception-errors.errors.server_error')]);
        }
    }
}
