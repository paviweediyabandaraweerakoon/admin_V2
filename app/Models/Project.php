<?php

namespace App\Models;

use App\Traits\LogsActivityTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory, SoftDeletes, LogsActivityTrait;

    /** @var string Activity log name */
    protected static $logName = 'projects';

    /** @var array Attributes to be logged */
    protected static $logAttributes = ['project_name', 'status', 'initial_value', 'next_amc_date'];

    /** @var string Table name */
    protected $table = 'projects';

    /** @var array Mass assignable attributes */
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
     * Search projects by name, status, or customer.
     */
    public function scopeSearchData($query, $term)
    {
        return $query->where('project_name', 'like', "%" . $term . "%")
            ->orWhere('status', 'like', "%" . $term . "%")
            ->orWhereHas('customer', function($q) use($term) {
                $q->where('company_name', 'like', "%" . $term . "%");
            });
    }

    /**
     * Relationship: The project belongs to a customer.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Relationship: The project has multiple AMC invoices.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(AmcInvoice::class);
    }
}