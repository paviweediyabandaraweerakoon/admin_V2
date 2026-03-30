<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $level = $user->roles->pluck('level');
        $levels = [];
        for ($i = 0; $i <= 5; $i++){
            if ($level[0] <= $i) {
                $l = "Level $i";
                $levels[$i] = $l;
            }
        }
        
        return view('administration.roles.index',compact('levels'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $exist = Role::whereName($request->name)->exists();
        if ($exist) {
            return $this->sendError('Role already exists!');
        } else {
            $level = $user->roles->pluck('level');
            if ($level[0] > $request->level) {
                return $this->sendError('You can not add higher level role than your\'s!');
            } else {
                $user = Auth::user();
                $exist = Role::whereName($request->name)->exists();
                if ($exist) {
                    return $this->sendError('Role already exists!');
                } else {
                    $level = $user->roles->pluck('level');
                    if ($level[0] > $request->level) {
                        return $this->sendError('You can not add higher level role than your\'s!');
                    } else {
                        $name = $request->name;
                        $permissions = $request->permissions;
                        $role = Role::firstOrCreate(['name' => $name, 'guard_name' => 'web','level' => $request->level]);

                        $role->givePermissionTo($permissions);
                        $msg = ($role->wasRecentlyCreated) ? 'Role Created Successfully' : 'Role Already Exists';
                        return $this->sendResponse($role, $msg);
                    }
                }
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $user = Auth::user();
        // $exist = Role::whereName($request->name)->where('id','!=',$request->id)->count();
        // if ($exist) {
        //     return $this->sendError('Role already exists!');
        // } else {
            $level = $user->roles->pluck('level');
            if ($level[0] > $request->level) {
                return $this->sendError('You can not add higher level role than your\'s!');
            } else {
                $role->name = $request->name;
                $role->level = $request->level;
                $permissions = $request->permissions;
                $role->save();

                $role = Role::find($role->id);
                $currentPermissions= $role->getAllPermissions();
                $role->revokePermissionTo($currentPermissions);
                $role->givePermissionTo($permissions);
                // return $this->sendResponse($role, 'Role Updated Successfully');
                return $this->sendResponse(null, 'Role successfully updated!');
            }
        // }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return $this->sendResponse('', 'Role successfully deleted');
    }

    public function tableData(Request $request)
    {
        $user = Auth::user();
        $order_by = $request->order;
        $search = $request->search['value'];
        $start = $request->start;
        $length = $request->length;
        $order_by_str = $order_by[0]['dir'];

        $columns = ['id','name', 'level', 'guard_name'];
        $order_column = $columns[$order_by[0]['column']];

        $roles = Role::query();

        if (is_null($search) || empty($search)) {
            $role_count = $roles->count();
        } else {
            $roles = $roles->searchData($search);
            $role_count = $roles->count();
        }
        $roles = $roles->tableData($order_column, $order_by_str, $start, (int)$length)->get();

        $data[][] = array();
        $i = 0;
        $edit_btn = null;
        $delete_btn = null;
        $can_edit = ($user->can('roles edit')) ? 1 : 0;
        $can_delete = ($user->can('roles delete')) ? 1 : 0;
        foreach ($roles as $key => $role) {
            if ($can_edit) {
                $edit_btn = "<i title='Edit role' class='tooltip-enabled fas fa-edit mr-3 cursor-pointer' onclick=\"edit(this)\" data-id='{$role->id}' data-name='{$role->name}' data-level='{$role->level}'></i>";
            }
            if ($can_delete) {
                $url = "roles/{$role->id}";
                $delete_btn = "<i title='Delete role' class='tooltip-enabled fas fa-trash-alt mr-3 cursor-pointer' onclick=\"FormOptions.deleteRecord('{$role->id}','$url','dataTable')\"></i>";
            }

            $data[$i] = array(
                $role->id,
                $role->name,
                $role->level,
                $role->guard_name,
                $edit_btn . $delete_btn
            );
            $i++;
        }

        if ($role_count == 0) {
            $data = [];
        }

        $json_data = [
            "draw" => intval($_REQUEST['draw']),
            "recordsTotal" => intval($role_count),
            "recordsFiltered" => intval($role_count),
            "data" => $data
        ];

        return json_encode($json_data);
    }

    public function renderForm(Request $request)
    {
        $id = $request->id;
        $role = Role::findById($id);

        $permissions = $role->getAllPermissions();
        $rolePermissions = collect($permissions->pluck('name'));

        $view = View::make('administration.roles.permissions-list', compact('rolePermissions'));
        $html = $view->render();
        return $html;
    }
}
