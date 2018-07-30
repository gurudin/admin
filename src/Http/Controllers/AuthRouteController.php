<?php

namespace Gurudin\Admin\Controllers;

use Illuminate\Http\Request;
use Gurudin\Admin\Models\AuthItem;

class AuthRouteController extends Controller
{
    /**
     * (view) Route index
     * 
     * @param Illuminate\Http\Request $request
     * @param Gurudin\Admin\Models\AuthItem $authItem
     * 
     * @return View
     */
    public function index(Request $request, AuthItem $authItem)
    {
        $routes = $authItem->getPermission();

        return view('admin::route.index', compact('routes'));
    }

    /**
     * (post ajax) Create route
     * 
     * @param Illuminate\Http\Request $request
     * @param Gurudin\Admin\Models\AuthItem $authItem
     * 
     * @return Json
     */
    public function create(Request $request, AuthItem $authItem)
    {
        $authItem->name   = $request->post('name');
        $authItem->method = $request->post('method');
        $authItem->type   = $authItem::TYPE_PERMISSION;

        return $authItem->save()
            ? $this->response(true)
            : $this->response(false, [], 'failed to create.');
    }

    /**
     * (delete ajax) Delete route
     * 
     * @param Illuminate\Http\Request $request
     * @param Gurudin\Admin\Models\AuthItem $authItem
     */
    public function destroy(Request $request, AuthItem $authItem)
    {
        return $authItem->removeItem($request->all())
            ? $this->response(true)
            : $this->response(false, [], 'failed to remove.');
    }
}
