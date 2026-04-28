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
    <div class="modal fade" id="editAMCInvoiceModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Edit AMC Invoice</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editAMCInvoiceForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="amc_invoice_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Invoice No</label>
                        <input type="text" id="edit_invoice_no" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" id="edit_status" class="form-control" required>
                            <option value="pending">Pending</option>
                            <option value="paid">Paid</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Invoice Date</label>
                        <input type="date" name="invoice_date" id="edit_invoice_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Payment Date (Manual)</label>
                        <input type="date" name="paid_at" id="edit_paid_at" class="form-control">
                        <small class="text-muted">Required only if status is "Paid"</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Invoice</button>
                </div>
                <div class="form-group">
                   <input type="text" name="invoice_no" id="edit_invoice_no" class="form-control" required> 
                
            </form>
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
        $(document).ready(function() {
            // Edit AMC Invoice
            $(document).on('click', '.amc-invoice-edit-btn', function() {
                let id = $(this).data('id');
                let url = $(this).data('url');

                //Load data to Modal
                $('#amc_invoice_id').val(id);
                $('#edit_invoice_no').val($(this).data('invoice-no'));
                $('#edit_status').val($(this).data('status'));
                $('#edit_invoice_date').val($(this).data('invoice-date'));
                $('#edit_paid_at').val($(this).data('payment-date'));
                $('#editAMCInvoiceModal').modal('show');
            });
            // Form Submit Event
            $('#editAMCInvoiceForm').on('submit', function(e) {
                e.preventDefault();
                let id = $('#amc_invoice_id').val();
                let formData = $(this).serialize();

                $.ajax({
                    url: "/amc-invoices/" + id,
                    type: "POST",
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                        $('#editAMCInvoiceModal').modal('hide');
                        $('#dataTable').DataTable().ajax.reload();
                        Swal.fire('Success', response.message, 'success');
                        }
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                let errorMessages = '';
                $.each(errors, function(key, value) {
                    errorMessages += value[0] + '<br>';
                });
                Swal.fire('Error', errorMessages, 'error');
            }
                });
            });
        });
    </script>
@endpush