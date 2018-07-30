<?php

namespace Gurudin\Admin\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Gurudin\Admin\Models\AuthItem;
use Gurudin\Admin\Models\AuthGroup;
use Gurudin\Admin\Models\AuthGroupChild;
use Gurudin\Admin\Models\User;
use Gurudin\Admin\Support\Helper;

class AuthGroupController extends Controller
{
    /**
     * (view) Group select
     * 
     * @param Illuminate\Http\Request $request
     * @param Gurudin\Admin\Models\AuthGroup $authGroup
     * 
     * @return View
     */
    public function select(Request $request, Helper $Helper)
    {
        // if ($request->session()->has('group_list')) {
        //     $group_list = $request->session()->get('group_list');
        // } else {
        //     $group_list = $Helper::authGroup(Auth::user());
        //     $request->session()->put('group_list', $group_list);
        // }

        $group_list = $Helper::authGroup(Auth::user());
        $request->session()->put('group_list', $group_list);
        
        return view(config('admin.select_view'), compact('group_list'));
    }

    /**
     * (view) Group index
     * 
     * @param Illuminate\Http\Request $request
     * @param Gurudin\Admin\Models\AuthGroup $authGroup
     * 
     * @return View
     */
    public function index(Request $request, AuthGroup $authGroup)
    {
        $group_items = $authGroup->getGroup();

        return view('admin::group.index', compact('group_items'));
    }

    /**
     * (view) Group view
     * 
     * @param Illuminate\Http\Request $request
     * @param Gurudin\Admin\Models\AuthGroup $authGroupChild
     * @param Gurudin\Admin\Models\AuthItem $authItem
     * @param Gurudin\Admin\Models\User $user
     * @param int $id
     * @param string $name
     * 
     * @return View
     */
    public function view(
        Request $request,
        AuthGroupChild $authGroupChild,
        AuthItem $authItem,
        User $user,
        int $id,
        string $name
    ) {
        $users = $user->getUser();
        $group_children = $authGroupChild->getAuthGroupChild($id);
        $item_permission = $authItem->getPermission()['permission'];
        $item_role = $authItem->getRole();
        
        return view('admin::group.view', compact(
            'id',
            'name',
            'users',
            'group_children',
            'item_permission',
            'item_role'
        ));
    }

    /**
     * (post ajax) Group create
     * 
     * @param Illuminate\Http\Request $request
     * @param Gurudin\Admin\Models\AuthGroup $authGroup
     * 
     * @return Json
     */
    public function create(Request $request, AuthGroup $authGroup)
    {
        $result = $authGroup->setGroup($request->all());

        return $result
            ? $this->response(true, ['id' => $result])
            : $this->response(false, [], 'failed to create.');
    }

    /**
     * (put ajax) Group update
     * 
     * @param Illuminate\Http\Request $request
     * @param Gurudin\Admin\Models\AuthGroup $authGroup
     * 
     * @return Json
     */
    public function update(Request $request, AuthGroup $authGroup)
    {
        $result = $authGroup->setGroup($request->all());

        return $result
            ? $this->response(true)
            : $this->response(false, [], 'failed to update.');
    }

    /**
     * (delete ajax) Group delete
     * 
     * @param Illuminate\Http\Request $request
     * @param Gurudin\Admin\Models\AuthGroup $authGroup
     * 
     * @return Json
     */
    public function destroy(Request $request, AuthGroup $authGroup)
    {
        return $authGroup->removeGroup($request->input('id'))
            ? $this->response(true)
            : $this->response(false, [], 'failed to delete.');
    }

    /**
     * (post ajax) Group child create
     * 
     * @param Illuminate\Http\Request $request
     * @param Gurudin\Admin\Models\AuthGroupChild $authGroupChild
     * 
     * @return Json
     */
    public function createChild(Request $request, AuthGroupChild $authGroupChild)
    {
        return $authGroupChild->setAuthGroupChild($request->all())
            ? $this->response(true)
            : $this->response(false, [], 'failed to create.');
    }

    /**
     * (delete ajax) Group child delete
     * 
     * @param Illuminate\Http\Request $request
     * @param Gurudin\Admin\Models\AuthGroupChild $authGroupChild
     * 
     * @return Json
     */
    public function destroyChild(Request $request, AuthGroupChild $authGroupChild)
    {
        return $authGroupChild->removeAuthGroup($request->all())
            ? $this->response(true)
            : $this->response(false, [], 'failed to delete.');
    }
}
