<?php

namespace App\Models;

use App\Traits\LogsActivityTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AmcInvoice extends Model
{
    use HasFactory, SoftDeletes, LogsActivityTrait;

    /** @var string Activity log name */
    protected static $logName = 'amc_invoices';

    /** @var array Attributes to be logged */
    protected static $logAttributes = ['invoice_no', 'amount', 'status', 'due_date'];

    /** @var string Table name */
    protected $table = 'amc_invoices';

    /** @var array Mass assignable attributes */
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
     * Search invoices by number, status, or project name.
     */
    public function scopeSearchData($query, $term)
    {
        return $query->where('invoice_no', 'like', "%" . $term . "%")
            ->orWhere('status', 'like', "%" . $term . "%")
            ->orWhereHas('project', function($q) use($term) {
                $q->where('project_name', 'like', "%" . $term . "%");
            });
    }

    /**
     * Relationship: Invoice belongs to a project.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Relationship: Invoice has many payments.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'amc_invoice_id');
    }
}