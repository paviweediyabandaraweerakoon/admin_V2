<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\LogsActivityTrait;
use App\Traits\SearchableTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Project Model
 * Represents the 'projects' table and its relationships.
 */
class Project extends Model
{
    use SoftDeletes, LogsActivityTrait, SearchableTrait;

    protected static $logName = 'projects';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

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
        return $this->belongsTo(Customer::class);
    }

    /**
     * Relationship: A project has many AMC invoices.
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

    public function calculateNextAmcDate(): ?Carbon
    {
        if (!$this->launch_date || !$this->amc_durations_month) {
            $this->next_amc_date = null;

            return null;
        }

        $nextAmcDate = Carbon::parse($this->launch_date)
            ->addMonths((int) $this->amc_durations_month);

        $this->next_amc_date = $nextAmcDate;

        return $nextAmcDate;
    }
}