<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Permission;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roots = Menu::whereNull('parent_id')->pluck('title', 'id');
        return view('administration.menu.index', compact('roots'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tags = $request->permission_tags;
        $isParent = (!empty($request->is_parent)) ? $request->is_parent : false;
        if ($isParent) {
            $root = Menu::firstOrCreate(['title' => $request->title, 'parent_id' => null, 'icon' => $request->icon, 'url' => $request->url, 'menu_order' => $request->menu_order]);
            if (!empty($tags)) {
                $tags = explode(",", $tags);
                foreach ($tags as $tag) {
                    $permission = strtolower($tag);
                    $exists = Permission::where('name', $permission)->where('guard_name', 'web')->exists();
                    if ($exists) {
                        continue;
                    } else {
                        $root->permissions()->save(new Permission(['name' => $permission, 'guard_name' => 'web']));
                    }
                }
            }
            if ($root->wasRecentlyCreated) {
                return $this->sendResponse($root, 'Menu Created Successfully');
            } else {
                return $this->sendError('Menu Already Exists');
            }
        } else {
            if (!Route::has($request->url)) {
                return $this->sendError('Route Not Found');
            }
            DB::transaction(function () use ($request, $tags) {
                $root = Menu::whereId($request->parent_id)->first();
                $root->url = null;
                $root->save();

                $permissions = $request->permissions;
                $child = $root->children()->create(['title' => $request->title, 'url' => $request->url, 'icon' => $request->icon, 'menu_order' => $request->menu_order]);

                if (!empty($permissions) && sizeof($permissions) > 0) {
                    foreach ($permissions as $permission) {
                        $permission = strtolower($child->title) . ' ' . strtolower($permission);
                        $exists = Permission::where('name', $permission)->where('guard_name', 'web')->exists();
                        if ($exists) {
                            continue;
                        } else {
                            $child->permissions()->save(new Permission(['name' => $permission, 'guard_name' => 'web']));
                        }
                    }
                }

                if (!empty($tags)) {
                    $tags = explode(",", $tags);
                    foreach ($tags as $tag) {
                        $permission = strtolower($tag);
                        $exists = Permission::where('name', $permission)->where('guard_name', 'web')->exists();
                        if ($exists) {
                            continue;
                        } else {
                            $child->permissions()->save(new Permission(['name' => $permission, 'guard_name' => 'web']));
                        }
                    }
                }
            });

            return $this->sendResponse([], 'Menu Created Successfully');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Menu $menu)
    {
        $isParent = (!empty($request->is_parent)) ? $request->is_parent : false;
        $tags = $request->permission_tags;
        if ($isParent) {
            if (!empty($menu)) {
                $menu->title = $request->title;
                $menu->url = $request->url;
                $menu->parent_id = null;
                $menu->icon = $request->icon;
                $menu->menu_order = $request->menu_order;
                $menu->save();

                // $menu->permissions()->delete();
                if (!empty($tags)) {
                    $tags = explode(",", $tags);
                    foreach ($tags as $tag) {
                        $permission = strtolower($tag);
                        $exists = Permission::where('name', $permission)->where('guard_name', 'web')->exists();
                        if ($exists) {
                            continue;
                        } else {
                            $menu->permissions()->save(new Permission(['name' => $permission, 'guard_name' => 'web']));
                        }
                    }
                }

                return $this->sendResponse($menu, 'Menu Updated Successfully');
            } else {
                return $this->sendError('Something Went Wrong ! Please Contact Administrator');
            }
        } else {
            if (!Route::has($request->url)) {
                return $this->sendError('Route Not Found');
            }
            DB::transaction(function () use ($request, $menu, $tags) {
                $menu->title = $request->title;
                $menu->url = $request->url;
                $menu->parent_id = $request->parent_id;
                $menu->icon = $request->icon;
                $menu->menu_order = $request->menu_order;
                $menu->save();

                $permissions = $request->permissions;
                // $menu->permissions()->delete();

                if (!empty($permissions) && sizeof($permissions) > 0) {
                    foreach ($permissions as $permission) {
                        $permission = strtolower($menu->title) . ' ' . strtolower($permission);
                        $exists = Permission::where('name', $permission)->where('guard_name', 'web')->exists();
                        if ($exists) {
                            continue;
                        } else {
                            $menu->permissions()->save(new Permission(['name' => $permission, 'guard_name' => 'web']));
                        }
                    }
                }

                if (!empty($tags)) {
                    $tags = explode(",", $tags);
                    foreach ($tags as $tag) {
                        $permission = strtolower($tag);
                        $exists = Permission::where('name', $permission)->where('guard_name', 'web')->exists();
                        if ($exists) {
                            continue;
                        } else {
                            $menu->permissions()->save(new Permission(['name' => $permission, 'guard_name' => 'web']));
                        }
                    }
                }
            });

            return $this->sendResponse($menu, 'Menu Updated Successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Menu $menu)
    {
        $submenu = Menu::whereParentId($menu->id)->exists();
        if ($submenu) {
            return $this->sendError('Sorry Cannot Delete .Submenu Available');
        }
        $menu->delete();
        return $this->sendResponse('', 'Menu Successfully Deleted');
    }

    public function tableData(Request $request)
    {
        $user = Auth::user();
        $order_by = $request->order;
        $search = $request->search['value'];
        $start = $request->start;
        $length = $request->length;
        $order_by_str = $order_by[0]['dir'];

        $columns = ['title', 'url', 'parent_id', 'menu_order'];
        $order_column = $columns[$order_by[0]['column']];

        $menus = Menu::query();

        if (is_null($search) || empty($search)) {
            $menu_count = $menus->count();
        } else {
            $menus = $menus->searchData($search);
            $menu_count = $menus->count();
        }

        $menus = $menus->tableData($order_column, $order_by_str, $start, (int)$length)->get();

        $data = [];
        $i = 0;
        $edit_btn = null;
        $delete_btn = null;
        $can_edit = ($user->can('menu edit')) ? 1 : 0;
        $can_delete = ($user->can('menu delete')) ? 1 : 0;
        foreach ($menus as $key => $menu) {
            $icon = !empty($menu->icon) ? $menu->icon : "fa fa-times";
            if ($can_edit) {
                $permissions = $menu->permissions->pluck('name');
                $parent_id = !empty($menu->parent_id) ? $menu->parent_id : null;
                $edit_btn = "<i title='Edit Menu' class='tooltip-enabled fas fa-edit mr-3 cursor-pointer' onclick=\"edit(this)\" data-id='{$menu->id}' data-title='{$menu->title}' data-url='{$menu->url}' data-parent_id='{$parent_id}' data-permissions='{$permissions}' data-icon='{$icon}' data-menu_order='{$menu->menu_order}'></i>";
            }
            if ($can_delete) {
                $url = "menu/{$menu->id}";
                $delete_btn = "<i title='Delete Menu' class='tooltip-enabled fas fa-trash-alt mr-3 cursor-pointer' onclick=\"FormOptions.deleteRecord('{$menu->id}','$url','dataTable')\"></i>";
            }

            $icon_text = "<i class='$icon'></i>";
            $data[$i] = array(
                $menu->title,
                $icon_text,
                $menu->url,
                (!empty($menu->parent)) ? $menu->parent->title : null,
                (!empty($menu->permissions)) ? $menu->permissions->pluck('name') : null,
                $menu->menu_order,
                $edit_btn . $delete_btn
            );
            $i++;
        }

        if ($menu_count == 0) {
            $data = [];
        }

        $json_data = [
            "draw" => intval($_REQUEST['draw']),
            "recordsTotal" => intval($menu_count),
            "recordsFiltered" => intval($menu_count),
            "data" => $data
        ];

        return json_encode($json_data);
    }
}
