<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menus = Menu::whereNotNull('parent_id')->pluck('title','id');
        return view('administration.permissions.index',compact('menus'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $exist = Permission::whereName($request->name)->count();
        if ($exist) {
            return $this->sendError('Permission already exists!');
        } else {
            Permission::create(['name' => $request->name,'menu_id'=>$request->menu_id]);
            return $this->sendResponse(null, 'Permission successfully added!');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        $exist = Permission::whereName($request->name)->where('id','!=',$permission->id)->count();
        if ($exist) {
            return $this->sendError('Permission already exists!');
        } else {
            $permission->name = $request->name;
            $permission->menu_id = $request->menu_id;
            $permission->save();
            return $this->sendResponse($permission, 'Permission successfully updated!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();
        return $this->sendResponse('', 'Permission successfully deleted');
    }

    public function tableData(Request $request)
    {
        $user = Auth::user();
        $order_by = $request->order;
        $search = $request->search['value'];
        $start = $request->start;
        $length = $request->length;
        $order_by_str = $order_by[0]['dir'];

        $columns = ['name','title','guard_name'];
        $order_column = $columns[$order_by[0]['column']];

        $permissions = Permission::query();

        if (is_null($search) || empty($search)) {
            $permission_count = $permissions->count();
        } else {
            $permissions = $permissions->searchData($search);
            $permission_count = $permissions->count();
        }
        $permissions = $permissions->tableData($order_column, $order_by_str, $start, (int)$length)->get();

        $data=[];
        $i = 0;
        $edit_btn = null;
        $delete_btn = null;
        $can_edit = ($user->can('permissions edit')) ? 1 : 0;
        $can_delete = ($user->can('permissions delete')) ? 1 : 0;
        foreach ($permissions as $key => $permission) {
            if ($can_edit) {
                $edit_btn = "<i title='Edit Permission' class='tooltip-enabled fas fa-edit mr-3 cursor-pointer' onclick=\"edit(this)\" data-id='{$permission->id}' data-name='{$permission->name}' data-menu_id='{$permission->menu_id}'></i>";
            }
            if ($can_delete) {
                $url = "permissions/{$permission->id}";
                $delete_btn = "<i title='Delete Permission' class='tooltip-enabled fas fa-trash-alt mr-3 cursor-pointer' onclick=\"FormOptions.deleteRecord('{$permission->id}','$url','dataTable')\"></i>";
            }

            $data[$i] = array(
                $permission->name,
                $permission->title,
                $permission->guard_name,
                $edit_btn . $delete_btn
            );
            $i++;
        }

        if ($permission_count == 0) {
            $data = [];
        }

        $json_data = [
            "draw" => intval($_REQUEST['draw']),
            "recordsTotal" => intval($permission_count),
            "recordsFiltered" => intval($permission_count),
            "data" => $data
        ];

        return json_encode($json_data);
    }
}
