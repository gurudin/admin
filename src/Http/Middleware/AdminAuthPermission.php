<?php

namespace Gurudin\Admin\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Gurudin\Admin\Support\Helper;
use Illuminate\Support\Facades\Cache;

class AdminAuthPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle(\Illuminate\Http\Request $request, Closure $next)
    {
        $isAllow       = false;
        $current_route = [
            'method' => Route::current()->methods,
            'uri'    => Route::current()->uri,
            'name'   => Route::currentRouteName(),
        ];
        array_push($current_route['method'], 'ANY');
        
        // Is allowed.
        foreach (config('admin.allow') as $allow) {
            $uri  = isset($allow['uri']) ? ($allow['uri'][0] == '/' ? substr($allow['uri'], 1) : $allow['uri']) : '';
            $name = $allow['name'] ?? '';
            
            if (in_array(strtoupper($allow['method']), $current_route['method'])
                && ($current_route['uri'] == $uri || $current_route['name'] === $name)
            ) {
                return $next($request);
            }
        }

        // Check group id.
        if (Cache::get('current-group-' . Auth::user()->id)) {
            $group_id = Cache::get('current-group-' . Auth::user()->id);
        } else {
            if (!$group_id = $request->get('group')) {
                return redirect()->route('get.group.select');
            }
        }

        // Is admin.
        if (Helper::isAdmin(Auth::user())) {
            return $next($request);
        }

        // Check permission
        $routes = Helper::getUserRoute(Auth::user()->id, $group_id);
        foreach ($routes as $value) {
            $uri = isset($value['child'])
                ? (
                    $value['child'][0] == '/'
                    ? substr($value['child'], 1)
                    : $value['child']
                ) : '';
            if ((in_array(strtoupper($value['method']), $current_route['method']))
                && $current_route['uri'] == $uri
            ) {
                $isAllow = true;
            }
        }

        if ($isAllow) {
            return $next($request);
        } else {
            if (config('admin.forbidden_view')) {
                return response()->view(config('admin.forbidden_view'));
            } else {
                return response()->json('403 Forbidden Error', 403);
            }
        }
    }
}
