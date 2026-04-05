<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\LogsActivityTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\SearchableTrait;
use Illuminate\Database\Eloquent\Builder;

/**
 * Customer Model
 * * Represents the 'customers' table.
 */
class Customer extends Model
{
    use SoftDeletes, LogsActivityTrait, SearchableTrait;

    /**
     * Spatie Activity Log settings
     */
    protected static $logName = 'customers';

    /** Log fillable attributes */
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    
    protected $fillable = [
        'company_name',
        'phone',
        'country',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope for DataTables Pagination & Ordering
     * Explicit column selection to avoid 'SELECT *' and improve performance.
     */
    public function scopeTableData(Builder $query, string $order_column, string $order_by_str, int $start, int $length): Builder
    {
        return $query->orderBy($order_column, $order_by_str)
            ->offset($start)
            ->limit($length);
    }

    /**
     * Scope for Active Customers only 
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', true);
    }
}