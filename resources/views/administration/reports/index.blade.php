@extends('layouts.app')
@section('title', 'Reports')

@push('styles')
    <link rel="stylesheet" href="{{ asset('plugins/datatables.net-dt/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables.net-responsive-dt/css/responsive.dataTables.min.css') }}">
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
                        <li class="breadcrumb-item active" aria-current="page">Reports</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Reports</h4>
            </div>
            <div class="d-none d-md-block">
                <button class="btn btn-sm pd-x-15 btn-white btn-uppercase" data-toggle="modal" data-target="#createModal">
                    <i data-feather="plus" class="wd-10 mg-r-5"></i> NEW REPORT
                </button>
            </div>
        </div>

        <div class="card mg-b-10">
            <div class="card-body pd-y-30">
                <div class="table-responsive overflow-hidden">
                    <table id="dataTable" class="table compact">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Route</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reports as $report)
                                <tr>
                                    <td>{{ $report['title'] }}</td>
                                    <td>{{ $report['description'] }}</td>
                                    <td>{{ $report['route'] }}</td>
                                    <td>
                                        <a href="{{ route($report['route']) }}" class="btn btn-xs btn-primary">Open</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-5">
                                        No reports are configured yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div><!-- table-responsive -->
            </div><!-- card-body -->
        </div>

        <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createReportModal"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header p-3">
                        <h5 class="modal-title">Create report</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body mt-3 mb-3">
                        <form>
                            <div class="form-group">
                                <label for="reportTitle">Title</label>
                                <input id="reportTitle" type="text" class="form-control" placeholder="Report title">
                            </div>
                            <div class="form-group">
                                <label for="reportDescription">Description</label>
                                <textarea id="reportDescription" class="form-control" rows="3" placeholder="Report description"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="reportRoute">Route</label>
                                <input id="reportRoute" type="text" class="form-control" placeholder="reports.customer">
                            </div>
                            <div class="float-right">
                                <button type="button" class="btn btn-xs btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-xs btn-primary">Create</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
