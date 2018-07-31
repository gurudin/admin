<?php

namespace Gurudin\Admin\Controllers;

use Illuminate\Http\Request;
use Gurudin\Admin\Models\AuthItem;
use Gurudin\Admin\Models\AuthItemChild;

class AuthRoleController extends Controller
{
    /**
     * (view) Role index
     *
     * @param Illuminate\Http\Request $request
     * @param Gurudin\Admin\Models\AuthItem $authItem
     *
     * @return View
     */
    public function index(Request $request, AuthItem $authItem)
    {
        $roles = $authItem->getRole();

        return view('admin::role.index', compact('roles'));
    }

    /**
     * (view) Role view
     *
     * @param Illuminate\Http\Request $request
     * @param Gurudin\Admin\Models\AuthItem $authItem
     * @param string $name
     *
     * @return View
     */
    public function view(Request $request, AuthItem $authItem, AuthItemChild $authItemChild, string $name)
    {
        $items = $authItem->getPermission()['permission'];
        $itemChildren = $authItemChild->getAuthItemChild($name);

        return view('admin::role.view', compact('name', 'items', 'itemChildren'));
    }

    /**
     * (post ajax) Role create
     *
     * @param Illuminate\Http\Request $request
     * @param Gurudin\Admin\Models\AuthItem $authItem
     *
     * @return Json
     */
    public function create(Request $request, AuthItem $authItem)
    {
        return $authItem->setItem($request->all())
            ? $this->response(true)
            : $this->response(false);
    }

    /**
     * (put ajax) Role update
     *
     * @param Illuminate\Http\Request $request
     * @param Gurudin\Admin\Models\AuthItem $authItem
     *
     * @return Json
     */
    public function update(Request $request, AuthItem $authItem)
    {
        return $authItem->updateItem($request->all())
            ? $this->response(true)
            : $this->response(false);
    }

    /**
     * (delete ajax) Role destroy
     *
     * @param Illuminate\Http\Request $request
     * @param Gurudin\Admin\Models\AuthItem $authItem
     *
     * @return Json
     */
    public function destroy(Request $request, AuthItem $authItem)
    {
        return $authItem->removeItem($request->all())
            ? $this->response(true)
            : $this->response(false);
    }
}
