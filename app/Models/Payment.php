<?php

namespace App\Models;

use App\Traits\LogsActivityTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory, SoftDeletes, LogsActivityTrait;

    /** @var string Activity log name */
    protected static $logName = 'payments';

    /** @var array Attributes to be logged */
    protected static $logAttributes = ['payment_date', 'amount_received', 'payment_method'];

    /** @var string Table name */
    protected $table = 'payments';

    /** @var array Mass assignable attributes */
    protected $fillable = [
        'amc_invoice_id',
        'payment_date',
        'amount_received',
        'payment_method',
        'currency',
        'cheque_num',
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
     * Search payments by cheque number, method, or invoice number.
     */
    public function scopeSearchData($query, $term)
    {
        return $query->where('cheque_num', 'like', "%" . $term . "%")
            ->orWhere('payment_method', 'like', "%" . $term . "%")
            ->orWhereHas('invoice', function($q) use($term) {
                $q->where('invoice_no', 'like', "%" . $term . "%");
            });
    }

    /**
     * Relationship: Payment belongs to an invoice.
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(AmcInvoice::class, 'amc_invoice_id');
    }
}