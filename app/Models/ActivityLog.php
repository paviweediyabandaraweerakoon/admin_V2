<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table = "activity_log";

    public function getCreatedAtAttribute($value)
    {
        return  Carbon::parse($value)->diffForHumans();
    }

    public function getPropertiesAttribute($value)
    {
        return json_decode($value);
    }

    public function causer(){
        return $this->belongsTo(User::class);
    }

    public function scopeTableData($query, $order_column, $order_by_str, $start, $length)
    {
        $user = Auth::user();
        $is_super_admin = $user->hasRole('Super Admin') ? true : false;
        $is_admin = $user->hasRole('Admin') ? true : false;
        if (!($is_super_admin || $is_admin)){
            $query->whereCauserId($user->id);
        }
        return $query->orderBy($order_column, $order_by_str)
            ->offset($start)
            ->limit($length);
    }

    public function scopeFilterByUser($query,$is_admin,$user_id)
    {
        if (!$is_admin){
            $query->whereCauserId($user_id);
        }
        return $query;
    }

    public function scopeSearchData($query, $term)
    {
        return $query
            ->Where('id', 'like', "%" . $term . "%")
            ->orWhere('log_name', 'like', "%" . $term . "%")
            ->orWhere('description', 'like', "%" . $term . "%")
            ->orWhere('subject_id', 'like', "%" . $term . "%")
            ->orWhere('subject_type', 'like', "%" . $term . "%")
            ->orWhereHas('causer', function($query) use($term){
                $query->where('name', 'like', "%" . $term . "%");
            })
            ->orWhere('causer_id', 'like', "%" . $term . "%")
            ->orWhere('causer_type', 'like', "%" . $term . "%");
    }

    public function scopeFilterData($query,$daterRange,$performed_on,$causedBy,$activity)
    {
        if (!empty($daterRange)){
            $date  = explode(' - ',$daterRange);
            $start_date = $date[0];
            $end_date = $date[1];
            $query->whereBetween('created_at',[$start_date,$end_date]);
        }
        if (!empty($performed_on)){
            $query->where('subject_type', '=',$performed_on);
        }
        if (!empty($causedBy)){
            $query->where('causer_id', '=',$causedBy);
        }
        if (!empty($activity)){
            if($activity == 'all'){
                $query->where('description', '=','created')->Orwhere('description', '=','updated')->Orwhere('description', '=','deleted');
            }
            else{
                $query->where('description', '=',$activity);
            }
        }
        return $query;

    }

    public function scopeCausedByList($query){
        return $query->with('causer')->groupBy('causer_id');
    }

    public function scopePerformedOnList($query){
        return $query->groupBy('subject_type');
    }
}
