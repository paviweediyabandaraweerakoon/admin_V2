@extends('layouts.app')
@section('title', 'Customers')

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
                        <li class="breadcrumb-item active" aria-current="page">Customers</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Customers</h4>
            </div>
            <div class="d-none d-md-block">
                <button class="btn btn-sm pd-x-15 btn-white btn-uppercase" data-toggle="modal" data-target="#createModal">
                    <i data-feather="plus" class="wd-10 mg-r-5"></i> ADD CUSTOMER
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
                                <th>Company</th>
                                <th>Phone</th>
                                <th>Country</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- CREATE MODAL --}}
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content text-white">
                <div class="modal-header p-3">
                    <h5 class="modal-title">Create Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body mt-3 mb-3 text-dark">
                    <form method="POST" id="customerCreateForm" action="{{ route('customers.store') }}">
                        @csrf
                        @include('administration.customers.form', ['editable' => false])
                        <div class="float-right mt-3">
                            <button type="button" class="btn btn-xs btn-secondary" data-dismiss="modal">Close</button>
                            <button onclick="FormOptions.submitForm('customerCreateForm','createModal','dataTable')"
                                    type="button" class="btn btn-xs btn-primary">Save Customer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- EDIT MODAL --}}
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content text-white">
                <div class="modal-header p-3">
                    <h5 class="modal-title">Edit Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body mt-3 mb-3 text-dark">
                    <form method="POST" id="customerEditForm">
                        @csrf
                        @method('PUT')
                        @include('administration.customers.form', ['editable' => true])
                        <div class="float-right mt-3">
                            <button type="button" class="btn btn-xs btn-secondary" data-dismiss="modal">Close</button>
                            <button onclick="FormOptions.submitForm('customerEditForm','editModal','dataTable')"
                                    type="button" class="btn btn-xs btn-primary">Update now
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
        // Initialize DataTable and form validations on document ready
        $(document).ready(function() {
            DataTableOption.initDataTable('dataTable', "{{ route('customers.table.data') }}");

            // Validation
            FormOptions.initValidation('customerCreateForm', []);
            FormOptions.initValidation('customerEditForm', []);
        });

        $(document).on('click', '.customer-edit-btn', function() {
            const data = $(this).data();
            editCustomer(data);
        });

        $(document).on('click', '.customer-delete-btn', function() {
            const id = $(this).data('id');
            const url = $(this).data('url');

            FormOptions.deleteRecord(id, url, 'dataTable');
        });

        function editCustomer(data) {
            let form = $("#customerEditForm");
            form.find('.id').val(data.id);
            form.find('.company_name').val(data.company_name);
            form.find('.phone').val(data.phone);
            form.find('.country').val(data.country);
            form.find('.status').val(data.status);

           // Update form action URL with the correct customer ID
            let updateUrl = "{{ route('customers.update', ':id') }}".replace(':id', data.id);
            form.attr('action', updateUrl);
            
            $('#editModal').modal('show');
        }
    </script>
@endpush