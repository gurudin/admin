<?php

namespace Gurudin\Admin\Controllers;

use Illuminate\Http\Request;
use Gurudin\Admin\Models\Menu;
use Gurudin\Admin\Models\AuthItem;

class AuthMenuController extends Controller
{
    /**
     * (view) Menu index
     * 
     * @param Illuminate\Http\Request $request
     * @param Gurudin\Admin\Models\Menu $menu
     * 
     * @return View
     */
    public function index(Request $request, Menu $menu)
    {
        $menus = $menu->getMenu();

        return view('admin::menu.index', compact('menus'));
    }

    /**
     * (view) Menu create
     * 
     * @param Illuminate\Http\Request $request
     * @param Gurudin\Admin\Models\Menu $menu
     * @param Gurudin\Admin\Models\AuthItem $authItem
     * @param int $id
     * 
     * @return View
     */
    public function createView(Request $request, Menu $menu, AuthItem $authItem, int $id = 0)
    {
        $menus     = $menu->getMenu();
        $routes    = $authItem->getPermission()['route'];
        $curr_menu = ['id' => 0, 'title' => '', 'parent' => null, 'route' => '', 'order' => 0, 'data' => ''];
        foreach ($menus as $value) {
            if ($value['id'] == $id) {
                $curr_menu = $value;
            }
        }
        unset($value);

        return view('admin::menu.save', compact('menus', 'routes', 'curr_menu'));
    }

    /**
     * (post ajax) Menu create
     * 
     * @param Illuminate\Http\Request $request
     * @param Gurudin\Admin\Models\Menu $menu
     * 
     * @return Json
     */
    public function create(Request $request, Menu $menu)
    {
        $result = $menu->saveMenu($request->input());

        return $result
            ? $this->response(true, ['menu_id' => $result])
            : $this->response(false, [], 'failed to create.');
    }

    /**
     * (put ajax) Menu update
     * 
     * @param Illuminate\Http\Request $request
     * @param Gurudin\Admin\Models\Menu $menu
     * 
     * @return Json
     */
    public function update(Request $request, Menu $menu)
    {
        $result = $menu->saveMenu($request->input());

        return $result
            ? $this->response(true, ['menu_id' => $result])
            : $this->response(false, [], 'failed to update.');
    }

    /**
     * (delete ajax) Menu delete
     * 
     * @param Illuminate\Http\Request $request
     * @param Gurudin\Admin\Models\Menu $menu
     * 
     * @return Json
     */
    public function destroy(Request $request, Menu $menu)
    {
        return $menu->deleteMenu($request->input('id'))
            ? $this->response(true)
            : $this->response(false);
    }
}
