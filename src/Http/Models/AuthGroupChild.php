<?php

namespace Gurudin\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AuthGroupChild extends Model
{
    protected $table = 'auth_group_child';
    public $timestamps = false;

    const TYPE_USER = 1;
    const TYPE_ITEM = 2;

    /**
     * Get auth_group_child by type=1
     *
     * @param int $group_id (required)
     *
     * @return array
     */
    public function getGroupUserChild(int $group_id)
    {
        return $this->getAuthGroupChild($group_id, self::TYPE_USER);
    }

    /**
     * Get auth_group_child by type=2
     *
     * @param int $group_id (required)
     *
     * @return array
     */
    public function getGroupItemChild(int $group_id)
    {
        return $this->getAuthGroupChild($group_id, self::TYPE_ITEM);
    }

    /**
     * Get group by type
     * 
     * @param int $type
     * @param string $user_id
     * 
     * @return array
     */
    public function getGroupByType(int $type, string $user_id = '')
    {
        $result = [];
        
        $query = $this->orderBy('group_id', 'asc');
        if ($user_id != '') {
            $query->where(['child' => $user_id]);
        }
        
        $query->where(['type' => $type])
            ->chunk(100, function ($items) use (&$result) {
                foreach ($items as $item) {
                    $result[] = $item->toArray();
                }
            });

        return $result;
    }

    /**
     * extendProfile
     * 
     * @param array $oriArray
     * @param string $key
     * @param int $type
     * 
     * @return array
     */
    public function extendProfile(array $oriArray, string $key = 'id', int $type = self::TYPE_USER)
    {
        $ids = array_map('intval', array_values(array_filter(array_column($oriArray, $key))));
        
        $result = [];
        $this->orderBy('group_id', 'asc')
            ->where('type', $type)
            ->whereIn('child', $ids)
            ->chunk(100, function ($items) use (&$result) {
                foreach ($items as $item) {
                    $result[] = $item->toArray();
                }
            });

        return $result;
    }

    /**
     * Get auth_group_child by type.
     *
     * @param int $id (required)
     * @param int $type
     *
     * @return array
     */
    public function getAuthGroupChild(int $id, int $type = 0)
    {
        $result = [];
        if ($type != 0) {
            $this->orderBy('group_id', 'desc')
                ->where(['group_id' => $id, 'type' => $type])
                ->chunk(100, function ($items) use (&$result) {
                    foreach ($items as $item) {
                        $result[] = $item->toArray();
                    }
                });
        } else {
            $user_children = $item_children = [];
            $this->orderBy('group_id', 'desc')
                ->where(['group_id' => $id])
                ->chunk(100, function ($items) use (&$user_children, &$item_children) {
                    foreach ($items as $item) {
                        if ($item['type'] == self::TYPE_USER) {
                            $user_children[] = $item->toArray();
                        }
                        if ($item['type'] == self::TYPE_ITEM) {
                            $item_children[] = $item->toArray();
                        }
                    }
                });

            $result = [
                'users' => $user_children,
                'items' => $item_children
            ];
        }

        return $result;
    }

    /**
     * Set auth_group_child
     *
     * @param array $data = [
     *      'group_id' => '', (required)
     *      'type'     => '', (required)
     *      'childs'   => [ (required)
     *          '1',
     *          '2',
     *          ...
     *      ]
     * ];
     *
     * @return bool
     */
    public function setAuthGroupChild(array $data)
    {
        $insetData = [];

        foreach ($data['childs'] as $child) {
            $insetData[] = [
                'group_id' => $data['group_id'],
                'type'     => $data['type'],
                'child'    => $child
            ];
        };

        return $this->insert($insetData);
    }

    /**
     * Remove auth_group_child
     *
     * @param array $data = [
     *      'group_id' => '', (required)
     *      'type'     => '',
     *      'childs'   => [
     *          '1',
     *          '2',
     *          ...
     *      ]
     * ];
     *
     * @return bool
     */
    public function removeAuthGroup(array $data)
    {
        DB::beginTransaction();

        if (isset($data['childs']) && !empty($data['childs'])) {
            foreach ($data['childs'] as $child) {
                $query = $this->where(['group_id' => $data['group_id'], 'child' => $child]);
                if (isset($data['type']) && !empty($data['type'])) {
                    $query->where(['type' => $data['type']]);
                }
                
                if (!$query->delete()) {
                    DB::rollBack();

                    return false;
                }
            }
            unset($child);

            if (isset($data['type']) && !empty($data['type'])) {
                $auth_assign_query = DB::table('auth_assignment')->where(['group_id' => $data['group_id']]);
                
                if ($data['type'] == self::TYPE_USER) {
                    $auth_assign_query->whereIn('user_id', $data['childs']);
                } else {
                    $auth_assign_query->whereIn('item_name', $data['childs']);
                }
                $auth_assign_query->delete();
            }
        } else {
            $query = $this->where(['group_id' => $data['group_id']]);
            if (isset($data['type']) && !empty($data['type'])) {
                $query->where(['type' => $data['type']]);
            }
            
            if (!$query->delete()) {
                DB::rollBack();

                return false;
            }

            DB::table('auth_assignment')->where(['group_id' => $data['group_id']])->delete();
        }

        DB::commit();

        return true;
    }
}
