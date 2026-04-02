<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\LogsActivityTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

/**
 * Customer Model
 * * Represents the 'customers' table.
 */
class Customer extends Model
{
    use SoftDeletes, LogsActivityTrait;

    /**
     * Spatie Activity Log settings
     */
    protected static $logName = 'customers';
    protected static $logAttributes = ['company_name', 'phone', 'country', 'status'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'customers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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
        'status' => 'integer',
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
     * Scope for DataTables Global Search
     * Explicit column selection to avoid 'SELECT *' and improve performance.
     */
    public function scopeSearchData(Builder $query, ?string $term): Builder
    {
        if (!$term) {
        return $query;
    }
        return $query->where(function ($q) use ($term) {
            $q->where('id', 'like', "%$term%")
              ->orWhere('company_name', 'like', "%$term%")
              ->orWhere('phone', 'like', "%$term%")
              ->orWhere('country', 'like', "%$term%");
        });
    }

    /**
     * Scope for Active Customers only 
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 1);
    }
}