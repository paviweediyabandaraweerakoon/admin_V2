<?php

namespace App\Models;

use App\Traits\LogsActivityTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GlobalConfiguration extends Model
{
    use HasFactory, SoftDeletes, LogsActivityTrait;

    /** @var string Activity log name */
    protected static $logName = 'global_configurations';

    /** @var array Attributes to be logged */
    protected static $logAttributes = ['name', 'key_value', 'value', 'enabled'];

    /** @var string Table name */
    protected $table = 'global_configurations';

    /** @var array Mass assignable attributes */
    protected $fillable = [
        'name',
        'key_value',
        'value',
        'description',
        'enabled',
        'created_by',
        'updated_by'
    ];

    /**
     * Scope for DataTable server-side processing.
     */
    public function scopeTableData($query, $order_column, $order_by_str, $start, $length)
    {
        return $query->orderBy($order_column, $order_by_str)
            ->offset($start)
            ->limit($length);
    }

    /**
     * Search configuration by name or key.
     */
    public function scopeSearchData($query, $term)
    {
        return $query->where('name', 'like', "%" . $term . "%")
            ->orWhere('key_value', 'like', "%" . $term . "%")
            ->orWhere('value', 'like', "%" . $term . "%");
    }
}