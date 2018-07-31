<?php

namespace Gurudin\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AuthItemChild extends Model
{
    protected $table = 'auth_item_child';
    public $timestamps = false;

    /**
     * Get auth item child (by name)
     *
     * @param string $name=''
     *
     * @return array
     */
    public function getAuthItemChild(string $name)
    {
        $result = [];
        $this->where([
            'parent' => $name
        ])->orderBy('parent', 'asc')->chunk(100, function ($items) use (&$result) {
            foreach ($items as $item) {
                $result[] = $item->toArray();
            }
        });

        return $result;
    }

    /**
     * Get auth item by parents
     *
     * @param array $parents
     *
     * @return array
     */
    public function getAuthItemChilds(array $parents)
    {
        $result = [];
        $this->whereIn('parent', $parents)->orderBy('parent', 'asc')->chunk(100, function ($items) use (&$result) {
            foreach ($items as $item) {
                $result[] = $item->toArray();
            }
        });

        return $result;
    }

    /**
     * Save auth item child
     *
     * @param array $data = [
     *      'parent' => '',
     *      'childs'  => [
     *          [
     *              'method' => '',
     *              'child'  => ''
     *          ],
     *          [
     *              'method' => '',
     *              'child'  => ''
     *          ],
     *          ...
     *       ]
     * ];
     *
     * @return bool
     */
    public function saveItemChild(array $data)
    {
        $insetData = [];
        foreach ($data['childs'] as $child) {
            $insetData[] = [
                'parent' => $data['parent'],
                'method' => $child['method'] ? $child['method'] : '',
                'child' => $child['child']
            ];
        };

        return $this->insert($insetData);
    }

    /**
     * Remove auth item child
     *
     * @param array $data = [
     *      'parent' => '',
     *      'childs'  => [
     *          [
     *              'method' => '',
     *              'child'  => ''
     *          ],
     *          [
     *              'method' => '',
     *              'child'  => ''
     *          ],
     *          ...
     *       ]
     * ];
     *
     * @return bool
     */
    public function removeItemChild(array $data)
    {
        if (isset($data['childs'])) {
            DB::beginTransaction();

            foreach ($data['childs'] as $child) {
                $res = $this->where([
                    'parent' => $data['parent'],
                    'method' => $child['method'] ? $child['method'] : '',
                    'child'  => $child['child']
                ])->delete();

                if (!$res) {
                    DB::rollBack();

                    return false;
                }
            }
            DB::commit();
            
            return true;
        } else {
            return $this->where(['parent' => $data['parent']])->delete();
        }
    }
}
