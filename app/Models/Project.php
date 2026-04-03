<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\LogsActivityTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Project Model
 * Represents the 'projects' table and its relationships.
 */
class Project extends Model
{
    use SoftDeletes, LogsActivityTrait;

    protected static $logName = 'projects';
    protected static $logAttributes = ['project_name', 'customer_id', 'status', 'initial_value'];

    protected $table = 'projects';

    protected $fillable = [
        'customer_id',
        'project_name',
        'description',
        'initial_value',
        'status',
        'amc_percentage',
        'amc_durations_month',
        'launch_date',
        'next_amc_date',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'initial_value' => 'decimal:2',
        'amc_percentage' => 'decimal:2',
        'launch_date' => 'date',
        'next_amc_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship: A project belongs to a customer.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * Relationship: A project has many AMC invoices.
     */
    public function amcInvoices(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AMCInvoice::class, 'project_id');
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
     * Scope for DataTables Global Search
     */
    public function scopeSearchData(Builder $query, ?string $term): Builder
    {
        if (!$term) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('project_name', 'like', "%$term%")
              ->orWhere('status', 'like', "%$term%")
              ->orWhereHas('customer', function ($subQ) use ($term) {
                  $subQ->where('company_name', 'like', "%$term%");
              });
        });
    }
}