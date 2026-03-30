<?php

namespace App\Models;

use App\Traits\LogsActivityTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use LogsActivityTrait;

    protected static $logName = 'permissions';
    protected static $logAttributes = ['name', 'guard_name','menu_id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'guard_name',
        'role_ids',
        'menu_id'
    ];



    public function scopeTableData($query, $order_column, $order_by_str, $start, $length)
    {
        return $query->orderBy($order_column, $order_by_str)
            ->select('permissions.*','menu.title')
            ->join('menu','menu.id','=','permissions.menu_id')
            ->offset($start)
            ->limit($length);
    }

    public function scopeSearchData($query, $term)
    {
        return $query
            ->where('name', 'like', "%" . $term . "%")
            ->orWhere('guard_name', 'like', "%" . $term . "%");
    }
}
