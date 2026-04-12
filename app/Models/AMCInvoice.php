<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\LogsActivityTrait;
use App\Traits\SearchableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * AMCInvoice Model
 * Represents the 'amc_invoices' table and its relationships.
 */

class AMCInvoice extends Model
{
    use SoftDeletes, LogsActivityTrait, SearchableTrait;

    public const STATUS_PENDING = 'pending';
    public const STATUS_PAID = 'paid';
    public const STATUS_CANCELLED = 'cancelled';


    protected $fillable = [
        'project_id',
        'invoice_no',
        'description',
        'amount',
        'status',
        'invoice_date',
        'due_date',
        'paid_at',
        'created_by',
        'updated_by',
    ];

    // Activity Log Configuration
    protected static $logName = 'amc_invoices';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    protected $casts = [
        'amount'       => 'decimal:2',
        'invoice_date' => 'date',
        'due_date'     => 'date',
        'paid_at'      => 'datetime',
    ];

    /**
     * Relationship: An AMC invoice belongs to a project.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}