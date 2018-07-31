<?php

namespace Gurudin\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';
    public $timestamps = false;

    /**
     * Get menu (by id)
     *
     * @return array
     */
    public function getMenu($id = 0)
    {
        if (!empty($id)) {
            $result = $this->where(['id' => $id])->first();
            $result = $result->toArray();
            if (!empty($result['parent'])) {
                $parent_menu = $this->where(['id' => $result['parent']])->select('title')->first();
                $result['parent_name'] = $parent_menu['title'];
            }
        } else {
            $result = [];
            $this->orderBy('id', 'desc')->chunk(100, function ($items) use (&$result) {
                foreach ($items as $item) {
                    $result[] = $item->toArray();
                }
            });

            foreach ($result as &$menu) {
                foreach ($result as $value) {
                    if ($menu['parent'] == $value['id']) {
                        $menu['parent_name'] = $value['title'];
                    }
                }
            }
            unset($menu);
        }

        return $result;
    }

    /**
     * Get menus by fileds
     *
     * @param array $arr = [ (required)
     *      '/menu',
     *      '/route',
     *      ...
     * ];
     * @param string $key = (id or route ro parent)
     *
     * @return array
     */
    public function getMenuByFiled(array $arr, $key = 'id')
    {
        $result = [];

        $this->orderBy('order', 'desc')->whereIn($key, $arr)->chunk(100, function ($items) use (&$result) {
            foreach ($items as $item) {
                $result[] = $item->toArray();
            }
        });

        return $result;
    }

    /**
     * Save or update menu
     *
     * @return int paimary id
     */
    public function saveMenu(array $data)
    {
        if (empty($data['id'])) {
            $this->title  = $data['title'];
            $this->parent = $data['parent'] ? $data['parent'] : null;
            $this->route  = $data['route'] ? $data['route'] : '';
            $this->order  = $data['order'] ? $data['order'] : 0;
            $this->data   = $data['data'] ? $data['data'] : '';

            return $this->save() ? $this->id : false;
        } else {
            $res = $this->where(['id' => $data['id']])->update([
                'title'  => $data['title'],
                'parent' => $data['parent'] ? $data['parent'] : null,
                'route'  => $data['route'] ? $data['route'] : '',
                'order'  => $data['order'] ? $data['order'] : 0,
                'data'   => $data['data'] ? $data['data'] : '',
            ]);

            return $res ? $data['id'] : false;
        }
    }

    /**
     * Delete menu by id
     *
     * @return bool
     */
    public function deleteMenu(int $id)
    {
        return $this->where(['id' => $id])->delete();
    }
}
