<?php

namespace Gurudin\Admin\Controllers;

use Illuminate\Http\Request;
use Gurudin\Admin\Support\Helper;
use Illuminate\Support\Facades\Auth;

class AuthAssignmentController extends Controller
{
    /**
     * (view) Assignment index
     *
     * @param Illuminate\Http\Request $request
     * @param Gurudin\Admin\Support\Helper $helper
     *
     * @return View
     */
    public function index(Request $request, Helper $helper)
    {
        $user_item = $helper::getAuthUser(Auth::user(), $request->get('group'));
        
        return view('admin::assignment.index', compact('user_item'));
    }

    /**
     * (view) Assignment view
     *
     * @param Illuminate\Http\Request $request
     * @param Gurudin\Admin\Support\Helper $helper
     * @param int $user_id
     *
     * @return View
     */
    public function view(Request $request, Helper $helper, int $user_id)
    {
        $group_id    = $request->get('group');
        $user_detail = $helper::getAuthUserDetail(auth::user(), $user_id, $group_id);

        $group_is_arr = [];
        foreach ($user_detail['group'] as $key => $value) {
            $group_is_arr[] = $value['group_id'];
        }
        $group_ids   = implode(",", $group_is_arr);
        $distributor = $helper::getAuthGroupPermission(Auth::user(), $group_ids);

        return view('admin::assignment.view', compact(
            'user_detail',
            'distributor'
        ));
    }

    /**
     * (post ajax) Assignment create
     *
     * @param Illuminate\Http\Request $request
     * @param Gurudin\Admin\Support\Helper $helper
     *
     * @return Json
     */
    public function create(Request $request, Helper $helper)
    {
        return $helper::saveAuthAssignment($request->all())
            ? $this->response(true)
            : $this->response(false, [], 'failed to create.');
    }

    /**
     * (delete ajax) Assignment delete
     *
     * @param Illuminate\Http\Request $request
     * @param Gurudin\Admin\Support\Helper $helper
     *
     * @return Json
     */
    public function destroy(Request $request, Helper $helper)
    {
        return $helper::removeAuthAssignment($request->all())
            ? $this->response(true)
            : $this->response(false, [], 'failed to delete.');
    }
}
