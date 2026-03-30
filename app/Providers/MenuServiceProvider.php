<?php

namespace App\Providers;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            $html = '';
            if (Auth::check()) {
                $user_id = Auth::id();
                $user = User::find($user_id);
                $menu_ids = [];
                $current_route = Request::route()->getName();

                if ($user->hasRole(['Super Admin', 'Admin'])) {
                    $menu_ids = Menu::with('parentMenu')->get()->pluck('id');
                } else {
                    $menu_id = $user->getAllPermissions()->pluck('menu_id');
                    // dd($menu_id);
                    $menu_ids = $menu_id->unique();
                }

                $menus = Menu::whereIn('id', $menu_ids)->with('parentMenu')->get();
                $parent = collect();
                foreach ($menus as $menu) {
                    $parentMenu = $menu->parentMenu;
                    if (!empty($menu->url) && empty($menu->parent_id)) {
                        $parent->push($menu);
                    }
                    if (!empty($parentMenu->parentMenu)) {
                        $menus->push($parentMenu);
                        $parent->push($parentMenu->parentMenu);
                    }
                    if (!empty($parentMenu) && empty($parentMenu->parent_id)) {
                        $parent->push($parentMenu);
                    }
                }
                $parents = $parent->unique('id')->sortBy('menu_order');
                $menus = $menus->unique('id')->sortBy('menu_order');

                $html = '';

                foreach ($parents as $parent) {
                    $inner_html = "";
                    $base_html = "";
                    $submenu_html = "";
                    $category_act = "";
                    $submenu_count = 1;
                    if (!empty($parent->url) && empty($parent->parent_id)) {
                        $page_act = ($parent->url == $current_route) ? 'active' : '';
                        $base_html .= "<li class='nav-item $page_act'>";
                        $base_html .= "<a href='" . route($parent->url) . "'  class='nav-link $page_act'>";
                        $base_html .= $parent->title;
                        $base_html .= "</a>";
                        $base_html .= "</li>";
                    }
                    foreach ($menus as $menu) {
                        if (empty($menu->url) && !empty($menu->parent_id)) {
                            if ($menu->parent_id == $parent->id) {
                                $childrens = $menu->children()->get();
                                $inner_submenu_html = "";

                                $siblings_count = $menu->siblingsAndSelf()->count();
                                foreach ($childrens as $children) {
                                    if ($menu->id == $children->parent_id) {
                                        $page_act = ($children->url == $current_route) ? 'active' : '';
                                        if ($children->url == $current_route) {
                                            $category_act = "active";
                                        }
                                        //                                        $inner_submenu_html .= "<a href='" . route($children->url) . "'  class='dropdown-item $page_act'>" . $children->title . "</a>";
                                        $inner_submenu_html .= "<a href='" . route($children->url) . "' class='nav-sub-link'>";
                                        $icon = !empty($children->icon) ? $children->icon : 'fa fa-times';
                                        $inner_submenu_html .= "<i class='$icon mr-2'></i>";
                                        $inner_submenu_html .= "$children->title";
                                        $inner_submenu_html .= "</a>";
                                    }
                                }

                                if ($submenu_count == 1) {
                                    $inner_html .= '<div class="navbar-menu-sub">';
                                    $inner_html .= '<div class="d-lg-flex">';
                                    $inner_html .= '<ul>';
                                }

                                $inner_html .= '<li class="nav-label " style="margin-bottom: 5px ;margin-top: 10px">';
                                $inner_html .= $menu->title;
                                $inner_html .= '</li>';
                                $inner_html .= $inner_submenu_html;

                                if ($submenu_count == $siblings_count) {
                                    $inner_html .= '</ul>';
                                    $inner_html .= '</div>';
                                    $inner_html .= '</div>';
                                }

                                $submenu_count++;
                            }
                        } else {
                            if ($parent->id == $menu->parent_id) {
                                $page_act = ($menu->url == $current_route) ? 'active' : '';
                                if ($menu->url == $current_route) {
                                    $category_act = "active";
                                }
                                $inner_html .= "<li class='nav-sub-item'>";
                                $inner_html .= "<a href='" . route($menu->url) . "'  class='nav-sub-link $page_act'>";
                                $icon = !empty($menu->icon) ? $menu->icon : 'fa fa-times';
                                $inner_html .= "<i class='$icon mr-2'></i>";
                                $inner_html .= $menu->title;
                                $inner_html .= "</a>";
                                $inner_html .= "</li>";
                            }
                        }
                    }

                    $html .= $base_html;
                    if (empty($parent->url) && empty($parent->parent_id)) {
                        $html .= "<li class='nav-item with-sub $category_act'>";
                        $html .= " <a class='nav-link' href='#' >";
                        $html .= $parent->title;
                        $html .= '</a>';

                        if ($submenu_count == 1) {
                            $html .= '<ul class="navbar-menu-sub">';
                        }

                        $html .= $inner_html;
                        if ($submenu_count == 1) {
                            $html .= '</ul>';
                        }

                        $html .= '</li>';
                    }
                }
            }

            View::share('menux', $html);
        });
    }
}
