@extends('layouts.app')
@section('title', 'Activity Log')
@push('styles')
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables.net-dt/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables.net-responsive-dt/css/responsive.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}" type="text/css" />
    <style>
        .terminal-container {
            min-height: 200px;
            width: 500px;
            color: white;
            background-color: #0d1113;
            font-family: "Lucida Console";
        }
    </style>
@endpush
@push('dashforge-css')
    <link rel="stylesheet" href="{{ asset('plugins/dashforge/css/dashforge.demo.css') }}">
@endpush
@section('content')
    <div class="container">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                        <li class="breadcrumb-item"><a href="#">Administration</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Activity Log</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Activity Log</h4>
            </div>
            <div class="d-none d-md-block">
                <button class="btn btn-sm pd-x-15 btn-white btn-uppercase" data-toggle="modal" data-target="#filterModal"><i
                        data-feather="filter" class="wd-10 mg-r-5"></i> Filter
                </button>
            </div>
        </div>

        <div class="card mg-b-10">
            <div class="card-body pd-y-30">
                <div class="table-responsive overflow-hidden">
                    <table id="dataTable" class="table compact">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Subject</th>
                                <th>Subject type</th>
                                <th>Causer</th>
                                <th>Causer type</th>
                                <th>Created at</th>
                                <th>Properties</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div><!-- table-responsive -->
            </div><!-- card-body -->
        </div>
    </div>

    <div class="modal fade" id="filterModal" role="dialog" aria-labelledby="exampleModalForms" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header p-3">
                    <h5 class="modal-title">Filter Activity Log</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body mt-3 mb-3">
                <form method="POST" id="activityLogForm" class="needs-validation" novalidate>
                    @csrf
                    @include('administration.activity-logs.form')
                    <div class="float-right">
                        <button type="button" class="btn btn-xs btn-secondary" data-dismiss="modal">Close</button>
                        <button onclick="process_form()" type="button" class="btn btn-xs btn-primary">Filter Now
                        </button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>


@endsection
@push('scripts')
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables.net-dt/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables.net-responsive-dt/js/responsive.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-validation/jquery.validate.js') }}"></script>
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('js/dataTable.js') }}"></script>
    <script src="{{ asset('js/FormOptions.js') }}"></script>
    <script src="{{ asset('js/modal.js') }}"></script>


    <script>
        $(document).ready(function() {
            DataTableOption.initDataTable('dataTable', 'activity-logs/table/data');
        });

        let rules = {
            'dateRange': {
                require_from_group: [1, '.validate_group']
            },
            'performed_on': {
                require_from_group: [1, '.validate_group']
            },
            'caused_by': {
                require_from_group: [1, '.validate_group']
            },
            'activity': {
                require_from_group: [1, '.validate_group']
            }
        };
        // FormOptions.initValidation('activityLogForm',rules);
        $('.date_input').daterangepicker({
            showDropdowns: true,
            timePicker: true,
            timePicker24Hour: true,
            startDate: '{{ $days[0] }}',
            endDate: '{{ $days[1] }}',
            maxDate: '{{ $days[2] }}',
            locale: {
                format: 'Y-M-DD HH:mm'
            }
        });

        // $('.date_input').val('');

        function process_form() {
            console.log(1)
            // if ($('#activityLogForm').valid()) {
            let date_range = $("#dateRange").val();
            let performed_on = $("#performed_on").val();
            let caused_by = $("#caused_by").val();
            let log_activity = $("#activity").val();
            let table = $('#dataTable').DataTable();
            table.ajax.url('/activity-logs/table/data?performed_on=' + performed_on + '&caused_by=' + caused_by +
                '&date_range=' + date_range + '&log_activity=' + log_activity + '&filter=' + true).load();
            $("#filterModal").modal('toggle');
            // }
        }
    </script>
@endpush
