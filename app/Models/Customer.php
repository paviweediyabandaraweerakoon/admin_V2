<?php

namespace App\Models;

use App\Traits\LogsActivityTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory, SoftDeletes, LogsActivityTrait;

    /** @var string Activity log name */
    protected static $logName = 'customers';

    /** @var array Attributes to be logged */
    protected static $logAttributes = ['company_name', 'phone', 'country', 'status'];

    /** @var string Table name */
    protected $table = 'customers';

    /** @var array Mass assignable attributes */
    protected $fillable = [
        'company_name',
        'phone',
        'country',
        'status',
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
     * Search across multiple customer columns.
     */
    public function scopeSearchData($query, $term)
    {
        return $query->where('company_name', 'like', "%" . $term . "%")
            ->orWhere('phone', 'like', "%" . $term . "%")
            ->orWhere('country', 'like', "%" . $term . "%");
    }

    /**
     * Relationship: A customer can have multiple projects.
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}