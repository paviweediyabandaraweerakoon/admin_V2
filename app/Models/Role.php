<?php

namespace App\Models;

use App\Scopes\RoleLevelScope;
use App\Traits\LogsActivityTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Permission\Traits\HasPermissions;

class Role extends SpatieRole
{
    use LogsActivityTrait;

    protected static $logName = 'roles';
    protected static $logAttributes = ['name', 'level', 'guard_name'];


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'level',
        'guard_name',
        'permission_ids',
        'user_ids',
    ];


    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new RoleLevelScope);
    }

    public function scopeTableData($query, $order_column, $order_by_str, $start, $length)
    {
        return $query->orderBy($order_column, $order_by_str)
            ->offset($start)
            ->limit($length);
    }

    public function scopeSearchData($query, $term)
    {
        return $query
        ->where('id', 'like', "%" . $term . "%")
        ->orWhere('name', 'like', "%" . $term . "%")
        ->orWhere('level', 'like', "%" . $term . "%")
        ->orWhere('guard_name', 'like', "%" . $term . "%");
    }
}
