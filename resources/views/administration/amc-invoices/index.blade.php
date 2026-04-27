@extends('layouts.app')
@section('title', 'AMC Invoices')

@push('styles')
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables.net-dt/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables.net-responsive-dt/css/responsive.dataTables.min.css') }}">
@endpush

@push('dashforge-css')
    <link rel="stylesheet" href="{{ asset('plugins/dashforge/css/dashforge.demo.css') }}">
@endpush

@section('content')
    <div class="container">
        {{-- BREADCRUMB & HEADER --}}
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                        <li class="breadcrumb-item"><a href="#">Administration</a></li>
                        <li class="breadcrumb-item active" aria-current="page">AMC Invoices</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">AMC Invoice Management</h4>
            </div>
            
        </div>

        {{-- TABLE CARD --}}
        <div class="card mg-b-10">
            <div class="card-body pd-y-30">
                <div class="table-responsive overflow-hidden">
                    <table id="dataTable" class="table compact w-100">
                        <thead>
                            <tr>
                                <th>Invoice #</th>
                                <th>Project</th>
                                <th>Customer</th>
                                <th>AMC Amount</th>
                                <th>Payment Status</th>
                                <th>Invoice Date</th>
                                <th>Payment Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('plugins/select2/js/select2.js') }}"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables.net-dt/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables.net-responsive-dt/js/responsive.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-validation/jquery.validate.js') }}"></script>
    <script src="{{ asset('plugins/jquery-form/jquery.form.min.js') }}"></script>
    <script src="{{ asset('js/dataTable.js') }}"></script>
    <script src="{{ asset('js/FormOptions.js') }}"></script>
    <script src="{{ asset('js/modal.js') }}"></script>
    <script src="{{ asset('js/notifications.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                processing: true,
                serverSide: true, // Server-side processing for large datasets
            ajax: {
                url: "{{ route('amc-invoices.tableData') }}",
                type: 'GET'
            },
            columns: [
                { data: 0 }, // Invoice #
                { data: 1 }, // Project
                { data: 2 }, // Customer
                { data: 3 }, // AMC Amount
                { data: 4 }, //  Payment Status
                { data: 5 }, // Invoice Date
                { data: 6 }, // Payment Date
                { data: 7, orderable: false, searchable: false } //Action Buttons (Edit/Delete)
            ],
            
            responsive: true,
            order: [[0, 'desc']],
            pageLength: 25
            });

            $('.select2').select2({
                placeholder: '-- Select Project --',
                width: '100%'
            });

        });
    </script>
@endpush