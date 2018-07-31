<?php

namespace Gurudin\Admin\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get user item.
     *
     * @param string $id
     *
     * @return array
     */
    public function getUser(string $id = '')
    {
        if ($id == '') {
            $result = [];

            $this->select(['id', 'name', 'email'])
                ->orderBy('id', 'desc')
                ->chunk(100, function ($items) use (&$result) {
                    foreach ($items as $item) {
                        $result[] = $item->toArray();
                    }
                });
        } else {
            $result = $this->select(['id', 'name', 'email'])->where(['id' => $id])->first();
        }
        
        return $result;
    }

    /**
     * Get user by ids
     *
     * @param array $oriArray
     *
     * @return array
     */
    public function extendProfile(array $oriArray, string $key = 'id')
    {
        $ids = array_map('intval', array_values(array_filter(array_column($oriArray, $key))));

        $result = [];
        $this->orderBy('id', 'desc')->whereIn('id', $ids)->chunk(100, function ($items) use (&$result) {
            foreach ($items as $item) {
                $result[] = $item->toArray();
            }
        });

        return $result;
    }
}
