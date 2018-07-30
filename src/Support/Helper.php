<?php

namespace Gurudin\Admin\Support;

use Illuminate\Support\Facades\Auth;
use Gurudin\Admin\Models\User;
use Gurudin\Admin\Models\Menu;
use Gurudin\Admin\Models\AuthAssignment;
use Gurudin\Admin\Models\AuthGroupChild;
use Gurudin\Admin\Models\AuthItemChild;
use Gurudin\Admin\Models\AuthGroup;
use Illuminate\Support\Facades\Cache;

class Helper
{
    /**
     * Is admin
     * 
     * @param User $user
     * 
     * @return bool
     */
    public static function isAdmin(User $user)
    {
        return in_array($user->email, config('admin.admin_email')) ? true : false;
    }

    /**
     * Remove cache
     * 
     * @param string $key='menu'
     * 
     * @return void
     */
    public static function removeCache(string $key = 'menu')
    {
        Cache::forget($key);
    }

    /**
     * Save auth assignment
     * 
     * @param array $data = [
     *      'user_id'  => '', (required)
     *      'group_id' => 0, (required)
     *      'items'    => [
     *          'item1',
     *          'item2',
     *          ...
     *      ]
     * ];
     * 
     * @return bool
     */
    public static function saveAuthAssignment(array $data)
    {
        self::removeCache();
        $assignModel = new AuthAssignment;

        return $assignModel->saveAuthAssignment($data);
    }

    /**
     * Remove auth assignment
     * 
     * @param array $data = [
     *      'user_id'  => '', (required)
     *      'group_id' => 0, (required)
     *      'items'    => [
     *          'item1',
     *          'item2',
     *          ...
     *      ]
     * ];
     * 
     * @return bool
     */
    public static function removeAuthAssignment(array $data)
    {
        self::removeCache();
        $assignModel = new AuthAssignment;
        
        return $assignModel->removeAuthAssignment($data);
    }

    /**
     * Get auth group permission by group_ids (1,2,3)
     * 
     * @param string $group_ids
     * 
     * @return array
     */
    public static function getAuthGroupPermission(User $user, string $group_ids)
    {
        $ids_arr         = array_map('intval', explode(",", $group_ids));
        $result          = [];
        $groupModel      = new AuthGroup;
        $groupChildModel = new AuthGroupChild;

        foreach ($ids_arr as $id) {
            $ids[] = ['id' => $id];
        }

        if (self::isAdmin($user)) {
            $res_group = $groupModel->extendProfile($ids, 'id');
            foreach ($res_group as $group) {
                $group_child = $groupChildModel->getGroupItemChild($group['id']);
                $group['child'] = array_column($group_child, 'child');

                $result[] = $group;
            }
            
            return $result;
        } else {
            $assignModel = new AuthAssignment;

            $res_group = $groupModel->extendProfile($ids, 'id');
            foreach ($res_group as $group) {
                $res_assign = $assignModel->getAuthAssignment($user->id, $group['id']);
                $item_names = array_column($res_assign, 'item_name');
                $group['child'] = $item_names;

                $result[] = $group;
            }

            return $result;
        }
    }
    
    /**
     * Get auth user detail by (user_id, group_id)
     */
    public static function getAuthUserDetail(User $user, string $user_id, int $group_id)
    {
        $groupChildModel = new AuthGroupChild;
        $groupModel      = new AuthGroup;
        $assignModel     = new AuthAssignment;
        
        $user_res = clone $user->getUser($user_id);
        if (self::isAdmin($user)) {
            $child_res = $groupChildModel->extendProfile([['id' => $user_res['id']]]);
            $group_res = $groupModel->extendProfile($child_res, 'group_id');

            foreach ($child_res as &$child) {
                foreach ($group_res as $group) {
                    if ($group['id'] == $child['group_id']) {
                        $assign_res = $assignModel->getAuthAssignment($user_id, $group['id']);

                        $child['name']       = $group['name'];
                        $child['permission'] = array_column($assign_res, 'item_name');

                        unset($child['type'], $child['child']);
                    }
                }
            }
            unset($child);
            $user_res['group'] = $child_res;

            
        } else {
            $group_info  = $groupModel->getGroup($group_id);
            $group_res[] = ['group_id' => $group_info['id'], 'name' => $group_info['name']];

            foreach ($group_res as &$group) {
                $assign_res = $assignModel->getAuthAssignment($user_id, $group['group_id']);

                $group['permission'] = array_column($assign_res, 'item_name');
            }
            unset($group);

            $user_res['group'] = $group_res;
        }

        return $user_res;
    }

    /**
     * Get auth user by (user, group_id)
     * 
     * @param User $user
     * @param int $group_id
     * 
     * @return array
     */
    public static function getAuthUser(User $user, int $group_id)
    {
        $groupChildModel = new AuthGroupChild;
        $groupModel      = new AuthGroup;

        if (self::isAdmin($user)) {
            $user_res  = $user->getUser();
            $child_res = $groupChildModel->extendProfile($user_res);
            $group_res = $groupModel->extendProfile($child_res, 'group_id');

            foreach ($child_res as &$child) {
                foreach ($group_res as $group) {
                    if ($group['id'] == $child['group_id']) {
                        $child['name'] = $group['name'];
                    }
                }
            }
            unset($child);

            foreach ($user_res as &$value) {
                foreach ($child_res as $child) {
                    if ($value['id'] == $child['child']) {
                        $value['group'][] = ['id' => $child['group_id'], 'name' => $child['name']];
                    }
                }
            }
            unset($value);
        } else {
            $user_to_group = $groupChildModel->getGroupUserChild($group_id);
            $group_res     = $groupModel->getGroup($group_id);
            $user_res      = $user->extendProfile($user_to_group, 'child');

            foreach ($user_res as &$value) {
                $value['group'][] = ['id' => $group_res->id, 'name' => $group_res->name];
            }
            unset($value);
        }

        return $user_res;
    }

    /**
     * Get auth group by user
     * 
     * @param User $user
     * 
     * @return array
     */
    public static function authGroup(User $user)
    {
        $m = new AuthGroupChild;
        
        $group_arr = $m->getGroupByType($m::TYPE_USER, $user->id);

        $result = (new AuthGroup)->extendProfile($group_arr, 'group_id');

        return $result;
    }

    /**
     * Get auth menu by user
     * 
     * @param User $user
     * @param int $group_id
     * 
     * @return array
     */
    public static function authMenu(User $user, int $group_id = 0)
    {
        if ($cache = Cache::get("menu")) {
            if (isset($cache['menu-' . $user->id])) {
                return $cache['menu-' . $user->id];
            }
        }

        if (self::isAdmin($user)) {
            $menu_res = (new Menu)->getMenu();
        } else {
            $menu_res = self::getUserMenu($user->id, $group_id);
        }

        $menu_item = [];
        foreach ($menu_res as $menu) {
            $menu_item[] = [
                'id'     => $menu['id'],
                'text'   => $menu['title'],
                'href'   => $menu['route'],
                'icon'   => $menu['data'] ? json_decode($menu['data'])->icon : '',
                'parent' => $menu['parent'],
            ];
        }
        unset($menu);

        $tree = self::getTree($menu_item, null);

        cache(['menu' => [
            'menu-' . $user->id => $tree
        ]], 60 * 12);

        return $tree;
    }

    /**
     * (Auth) Get user group menus by (user_id, group_id)
     * 
     * @param string $user_id (required)
     * @param int $group_id (required)
     * 
     * @return array
     */
    public static function getUserMenu(string $user_id, int $group_id)
    {
        $routes     = [];
        $menu_res   = [];
        $assign_res = (new AuthAssignment)->getAuthAssignment($user_id, $group_id);
        $assign_res = array_column($assign_res, 'item_name');

        self::getRouteByParents($assign_res, $routes);

        $child_item = (new Menu)->getMenuByFiled($routes, 'route');

        self::getMenusByChild($child_item, $menu_res);

        return $menu_res;
    }

    /**
     * Get menus by childs
     * 
     * @param array $child_item
     * @param int $pid
     */
    public static function getMenusByChild(array $child_item, array &$menu_item)
    {
        $menu_item = array_merge($child_item, $menu_item);

        $parents = array_filter(array_column($child_item, 'parent'));

        if (empty($parents)) {
            return false;
        } else {
            $menu_res = (new Menu)->getMenuByFiled($parents, 'id');
            self::getMenusByChild($menu_res, $menu_item);
        }
    }

    /**
     * Get routes by parents
     * 
     * @param array $parents = [
     *      'parents1',
     *      'parents2',
     *      ...
     * ];
     * @param array &$routes = []
     * @param array $fileds = []
     * 
     * @return array
     */
    public static function getRouteByParents(array $parents, array &$routes)
    {
        $parents = array_filter(array_map(function($value) use(&$routes) {
            if ($value[0] == '/') {
                $routes[] = $value;
            } else {
                return $value;
            }
        }, $parents));

        if (empty($parents)) {
            return false;
        } else {
            $item_arr = (new AuthItemChild)->getAuthItemChilds($parents);
            $item_arr = array_column($item_arr, 'child');
            
            self::getRouteByParents($item_arr, $routes);
        }
    }

    public static function getRouteByItemParents(array $parents, array &$routes)
    {
        $parents = array_filter(array_map(function($value) use(&$routes) {
            if ($value['child'][0] == '/') {
                $routes[] = $value;
            } else {
                return $value;
            }
        }, $parents));

        if (empty($parents)) {
            return false;
        } else {
            $item     = array_column($parents, 'child');
            $item_arr = (new AuthItemChild)->getAuthItemChilds($item);
            
            self::getRouteByItemParents($item_arr, $routes);
        }
    }

    /**
     * (Auth) Get user routes by (user_id , group_id)
     */
    public static function getUserRoute(string $user_id, int $group_id)
    {
        $routes     = [];
        $item_child = [];
        $assign_res = (new AuthAssignment)->getAuthAssignment($user_id, $group_id);
        foreach ($assign_res as $assign) {
            $item_child[]['child'] = $assign['item_name'];
        }

        self::getRouteByItemParents($item_child, $routes);

        return $routes;
    }

    public static function getTree($data, $pId)
    {
        $tree = [];
        
        foreach($data as $k => $v) {
            if($v['parent'] == $pId) {    
                $v['children'] = self::getTree($data, $v['id']);
                $tree[] = $v;
                unset($data[$k]);
            }
        }
        
        return $tree;
    }
}
