<?php

namespace Gurudin\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AuthAssignment extends Model
{
    protected $table = 'auth_assignment';
    public $timestamps = true;

    /**
     * Get auth assignment (by user_id,group_id)
     *
     * @param string $user_id (required)
     * @param int $group_id (required)
     *
     * @return array
     */
    public function getAuthAssignment(string $user_id, int $group_id)
    {
        $result = [];
        $this->where([
            'user_id' => $user_id,
            'group_id' => $group_id
        ])->orderBy('item_name', 'asc')->chunk(100, function ($items) use (&$result) {
            foreach ($items as $item) {
                $result[] = $item->toArray();
            }
        });

        return $result;
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
    public function saveAuthAssignment(array $data)
    {
        $insetData = [];
        foreach ($data['items'] as $child) {
            $insetData[] = [
                'user_id'  => $data['user_id'],
                'group_id'  => $data['group_id'],
                'item_name' => $child
            ];
        };

        return $this->insert($insetData);
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
    public function removeAuthAssignment(array $data)
    {
        if (isset($data['items'])) {
            DB::beginTransaction();

            foreach ($data['items'] as $child) {
                $res = $this->where([
                    'user_id'   => $data['user_id'],
                    'group_id'  => $data['group_id'],
                    'item_name' => $child
                ])->delete();

                if (!$res) {
                    DB::rollBack();

                    return false;
                }
            }
            DB::commit();
            
            return true;
        } else {
            return $this->where(['user_id' => $data['user_id'], 'group_id' => $data['group_id']])->delete();
        }
    }
}
