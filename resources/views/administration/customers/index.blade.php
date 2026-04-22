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
                <button class="btn btn-sm pd-x-15 btn-white btn-uppercase" data-toggle="modal" data-target="#createModal"><i
                        data-feather="save" class="wd-10 mg-r-5"></i> NEW CUSTOMER
                </button>
            </div>
        </div>

        <div class="card mg-b-10">
            <div class="card-body pd-y-30">
                <div class="table-responsive overflow-hidden">
                    <table id="dataTable" class="table compact">
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
                </div></div></div>
    </div>

    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalForms"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header p-3">
                    <h5 class="modal-title">Create customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body mt-3 mb-3">
                    <form method="POST" action="{{ route('customers.store') }}" id="customerCreateForm">
                        @csrf
                        @include('administration.customers.form', [
                            'editable' => false,
                            'id' => 'customerCreateForm',
                        ])
                        <div class="float-right">
                            <button type="button" class="btn btn-xs btn-secondary" data-dismiss="modal">Close</button>
                            <button onclick="FormOptions.submitForm('customerCreateForm','createModal','dataTable')"
                                type="button" class="btn btn-xs btn-primary">Create
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalForms"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header p-3">
                    <h5 class="modal-title">Edit customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body mt-3 mb-3">
                    <form method="POST" id="customerEditForm">
                        @csrf
                        @method('PUT')
                        @include('administration.customers.form', [
                            'editable' => true,
                            'id' => 'customerEditForm',
                        ])
                        <div class="float-right">
                            <button type="button" class="btn btn-xs btn-secondary" data-dismiss="modal">Close</button>
                            <button onclick="FormOptions.submitForm('customerEditForm','editModal','dataTable')" type="button"
                                class="btn btn-xs btn-primary">Update now
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
            DataTableOption.initDataTable('dataTable', 'customers/table/data');
            
            $('.select2').select2({
                searchInputPlaceholder: 'Search options'
            });

            FormOptions.initValidation('customerCreateForm', [], 'select2');
            FormOptions.initValidation('customerEditForm', [], 'select2');

            // Edit Button Click Handler
            $(document).on('click', '.customer-edit-btn', function () {
                edit(this);
            });

            // Delete Button Click Handler
            $(document).on('click', '.customer-delete-btn', function () {
                let url = $(this).data('url');
                let name = $(this).data('name');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "Delete \"" + name + "\"? This cannot be undone.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value || result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'DELETE', 
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                $('#dataTable').DataTable().ajax.reload(null, false);
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: 'Customer has been deleted.',
                                    icon: 'success',
                                    showConfirmButton: true,
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        confirmButton: 'btn btn-primary px-4',
                                    }
                                });
                                
                            },
                            error: function (xhr) {
                                NotificationOptions.showNotification({
                                    type: 'error',
                                    title: 'Failed!',
                                    message: 'Could not delete customer.'
                                });
                            }
                      });
                    }
                });
            });
        });

        function edit(result) {
            let id = result.dataset.id;
            let company_name = result.dataset.company_name;
            let phone = result.dataset.phone;
            let country = result.dataset.country;
            let status = result.dataset.status;

            $("#customerEditForm").find('.id').val(id);
            $("#customerEditForm").find('.company_name').val(company_name);
            $("#customerEditForm").find('.phone').val(phone);
            $("#customerEditForm").find('.country').val(country);
            $("#customerEditForm").find('.status').val(status);

            $("#customerEditForm").attr('action', '/customers/' + id);
            ModalOptions.toggleModal('editModal');
        }
    </script>
@endpush