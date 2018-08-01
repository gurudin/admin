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
        $local_routes = [];
        $local = app()->routes->getRoutes();
        foreach ($local as $route) {
            foreach ($route->methods as $method) {
                if (strtolower($method) != 'head') {
                    $local_routes[] = [
                        'name'   => $route->uri[0] == '/' ? $route->uri : '/' . $route->uri,
                        'method' => strtolower($method),
                    ];
                }
            }
        }
        unset($route);
        
        $routes = $authItem->getPermission();
        $local_routes = array_values($local_routes);

        return view('admin::route.index', compact('routes', 'local_routes'));
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
        $req_data = $request->all();
        $data     = [];
        
        foreach ($req_data as $req) {
            $data[] = [
                'name'   => $req['name'],
                'method' => $req['method'],
                'type'   => $authItem::TYPE_PERMISSION
            ];
        }

        return $authItem->insert($data)
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
        $req_data = $request->all();

        foreach ($req_data as $data) {
            $authItem->removeItem($data, $authItem::TYPE_PERMISSION);
        }
        
        return $this->response(true);
    }
}
