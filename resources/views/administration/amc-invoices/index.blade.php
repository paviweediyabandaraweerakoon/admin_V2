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
            <div class="d-none d-md-block">
                <button class="btn btn-sm pd-x-15 btn-white btn-uppercase" data-toggle="modal" data-target="#createModal">
                    <i data-feather="plus" class="wd-10 mg-r-5"></i> NEW INVOICE
                </button>
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
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Invoice Date</th>
                                <th>Due Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoices as $invoice)
                                <tr>
                                    <td>{{ $invoice->invoice_no }}</td>
                                    <td>{{ $invoice->project?->project_name ?? 'N/A' }}</td>
                                    <td>{{ number_format($invoice->amount, 2) }}</td>
                                    <td>
                                        @if($invoice->status === App\Models\AMCInvoice::STATUS_PENDING)
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif($invoice->status === App\Models\AMCInvoice::STATUS_PAID)
                                            <span class="badge badge-success">Paid</span>
                                        @else
                                            <span class="badge badge-secondary">{{ ucfirst($invoice->status) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ optional($invoice->invoice_date)->format('Y-m-d') }}</td>
                                    <td>{{ optional($invoice->due_date)->format('Y-m-d') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- CREATE MODAL --}}
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">Generate AMC Invoice</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body text-dark">
                    <form method="POST" id="invoiceCreateForm" action="{{ route('amc-invoices.store') }}">
                        @csrf
                        @include('administration.amc-invoices.form', ['editable' => false])
                        <div class="float-right mt-3">
                            <button type="button" class="btn btn-xs btn-secondary" data-dismiss="modal">Close</button>
                            <button onclick="FormOptions.submitForm('invoiceCreateForm','createModal','dataTable')"
                                    type="button" class="btn btn-xs btn-primary">Generate Invoice
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
                responsive: true,
                order: [[0, 'desc']],
                pageLength: 25
            });

            $('.select2').select2({
                placeholder: '-- Select Project --',
                width: '100%'
            });

            FormOptions.initValidation('invoiceCreateForm', []);
        });
    </script>
@endpush