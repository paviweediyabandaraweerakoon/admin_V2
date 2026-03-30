<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $user_id = Auth::user()->id;
        $is_super_admin = $user->hasRole('Super Admin') ? true : false;
        $is_admin = $user->hasRole('Admin') ? true : false;
        $causers = User::all()->pluck('name', 'id');
        if (!($is_super_admin || $is_admin)){
            $causers = User::whereId($user_id)->pluck('name', 'id');
        }

        $days = [
            Carbon::yesterday()->format("Y-m-d 16:00"),
            Carbon::now()->format("Y-m-d 16:00"),
            Carbon::now()->format("Y-m-d 23:59:00")
        ];

        $performed_on = ActivityLog::PerformedOnList()->get()->pluck('subject_type', 'subject_type');
        return view('administration.activity-logs.index', compact('causers', 'performed_on','days')) ;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ActivityLog  $activityLog
     * @return \Illuminate\Http\Response
     */
    public function show(ActivityLog $activityLog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ActivityLog  $activityLog
     * @return \Illuminate\Http\Response
     */
    public function edit(ActivityLog $activityLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ActivityLog  $activityLog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ActivityLog $activityLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ActivityLog  $activityLog
     * @return \Illuminate\Http\Response
     */
    public function destroy(ActivityLog $activityLog)
    {
        //
    }

    public function tableData(Request $request, $filterData = false)
    {
        $user = Auth::user();
        $user_id = Auth::user()->id;
        $is_admin = ($user->hasRole('Super Admin') || $user->hasRole('Admin')) ? true : false;
        $order_by = $request->order;
        $search = $request->search['value'];
        $start = $request->start;
        $length = $request->length;
        $order_by_str = $order_by[0]['dir'];

        $columns = ['log_name', 'description', 'subject_id', 'subject_type', 'causer_id', 'causer_type', 'created_at', 'properties'];
        $order_column = $columns[$order_by[0]['column']];

        $activityLogs = ActivityLog::tableData($order_column, $order_by_str, $start, $length);

        if ($request->filter) {
            $daterRange = $request->date_range;
            $performed_on = $request->performed_on;
            $causedBy = (!empty($request->caused_by)) ? $request->caused_by : $user_id;
            $activity = $request->log_activity;
            $activityLogs = $activityLogs->FilterData($daterRange, $performed_on, $causedBy, $activity)->get();
            $activityLogsCount = ActivityLog::FilterData($daterRange, $performed_on, $causedBy, $activity);
            $activityLogsCount = $activityLogsCount->FilterByUser($is_admin,$user_id)->count();
        } else if (is_null($search) || empty($search) && is_null($request->filter)) {
            $activityLogs = $activityLogs->get();
            $activityLogsCount = ActivityLog::FilterByUser($is_admin,$user_id)->count();
        } else {
            $activityLogs = $activityLogs->searchData($search)->get();
            $activityLogsCount = ActivityLog::searchData($search);
            $activityLogsCount = $activityLogsCount->FilterByUser($is_admin,$user_id)->count();
        }

        $data[][] = array();
        $i = 0;
        $edit_btn = null;
        $delete_btn = null;


        foreach ($activityLogs as $key => $log) {
            $tmp = explode("\\", $log->subject_type);
            $subject_type = end($tmp);

            $tmp = explode("\\", $log->causer_type);
            $causer_type = end($tmp);

            if ($log->description == 'created' || $log->description == 'deleted') {
                $result_array = json_decode(json_encode($log->properties->attributes), TRUE);
            }
            if ($log->description == 'updated') {
                $it_1 = json_decode(json_encode($log->properties->attributes), TRUE);
                $it_2 = json_decode(json_encode($log->properties->old), TRUE);
                $result_array = array_diff($it_1, $it_2);
            }

            $causer = ($causer_type == 'User') ? User::whereId($log->causer_id)->withTrashed()->first()->name : $log->causer_id;
            $subject = ($subject_type == 'User') ? User::whereId($log->subject_id)->withTrashed()->first()->name : $log->subject_id;

            if (empty($result_array[0])) {
//                echo "they are same";
            } else {

            }
//
//            $tmp = collect($result_array);
//            $properties = $tmp->map(function ($item, $key) {
//                if (!is_array($item)){
//                    return " $key=>$item";
//                } else {
//                    return " $key=>".implode(",",$item);
//                }
//            })->flatten()->implode(",");
//

            $js = json_encode($result_array, JSON_PRETTY_PRINT);
            $jsonView = '<div class=""><textarea class="terminal-container">' . $js . '</textarea></div>';


            $data[$i] = array(
                $log->log_name,
                $log->description,
                $subject,
                $subject_type,
                $causer,
                $causer_type,
                $log->created_at,
                $jsonView,
            );
            $i++;
        }

        if ($activityLogsCount == 0) {
            $data = [];
        }

        $json_data = [
            "draw" => intval($_REQUEST['draw']),
            "recordsTotal" => intval($activityLogsCount),
            "recordsFiltered" => intval($activityLogsCount),
            "data" => $data
        ];

        return json_encode($json_data);
    }
}
