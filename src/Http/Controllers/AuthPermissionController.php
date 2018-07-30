<?php

namespace Gurudin\Admin\Controllers;

use Illuminate\Http\Request;
use Gurudin\Admin\Models\AuthItem;
use Gurudin\Admin\Models\AuthItemChild;
use Validator;

class AuthPermissionController extends Controller
{
    /**
     * (view) Permission index
     * 
     * @param Illuminate\Http\Request $request
     * @param Gurudin\Admin\Models\AuthItem $authItem
     * 
     * @return View
     */
    public function index(Request $request, AuthItem $authItem)
    {
        $items = $authItem->getPermission();

        return view('admin::permission.index', compact('items'));
    }

    /**
     * (view) Permission create
     * 
     * @param Illuminate\Http\Request $request
     * @param Gurudin\Admin\Models\AuthItem $authItem
     * 
     * @return View
     */
    public function createView(Request $request, AuthItem $authItem)
    {
        return view('admin::permission.create');
    }

    /**
     * (view) Permission view
     * 
     * @param Illuminate\Http\Request $request
     * @param Gurudin\Admin\Models\AuthItem $authItem
     * @param Gurudin\Admin\Models\AuthItemChild $authItemChild
     * @param string $name
     * 
     * @return View
     */
    public function view(Request $request, AuthItem $authItem, AuthItemChild $authItemChild, string $name)
    {
        $items = $authItem->getPermission();
        $child_items = $authItemChild->getAuthItemChild($name);

        return view('admin::permission.view', compact('items', 'name', 'child_items'));
    }

    /**
     * (post ajax) Permission create
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
            : $this->response(false, [], 'failed to created.');
    }

    /**
     * (delete ajax) Permission create
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
            : $this->response(false, [], 'failed to delete.');
    }

    /**
     * (put ajax) Permission update
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
            : $this->response(false, [], 'failed to update.');
    }

    /**
     * (post ajax) Batch create route children
     * 
     * @param Illuminate\Http\Request $request
     * @param Gurudin\Admin\Models\AuthItemChild $authItemChild
     * 
     * @return Json
     */
    public function batchCreateRouteChild(Request $request, AuthItemChild $authItemChild)
    {
        return $authItemChild->saveItemChild($request->all())
            ? $this->response(true)
            : $this->response(false, [], 'failed to create.');
    }

    /**
     * (delete ajax) Batch remove route children
     * 
     * @param Illuminate\Http\Request $request
     * @param Gurudin\Admin\Models\AuthItemChild $authItemChild
     * 
     * @return Json
     */
    public function batchRemoveRouteChild(Request $request, AuthItemChild $authItemChild)
    {
        return $authItemChild->removeItemChild($request->all())
            ? $this->response(true)
            : $this->response(false, [], 'failed to delete.');
    }
}
