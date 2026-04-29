<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\LogsActivityTrait;
use App\Traits\SearchableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Customer Model
 * Represents the 'customers' table and its relationships.
 */
class Customer extends Model
{
    use SoftDeletes, LogsActivityTrait, SearchableTrait;

    protected static $logName = 'customers';
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

    protected $casts = [
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship: A customer has many projects.
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Relationship: A customer has many AMC invoices.
     */
    public function amcInvoices(): HasMany
    {
        return $this->hasMany(AMCInvoice::class);
    }

    /**
     * Scope for DataTables Pagination & Ordering
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