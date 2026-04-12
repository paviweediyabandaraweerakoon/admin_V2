@extends('layouts.app')
@section('title', 'Projects')

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
                        <li class="breadcrumb-item active" aria-current="page">Projects</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Project Management</h4>
            </div>
            <div class="d-none d-md-block">
                <button class="btn btn-sm pd-x-15 btn-white btn-uppercase" data-toggle="modal" data-target="#createModal">
                    <i data-feather="plus" class="wd-10 mg-r-5"></i> NEW PROJECT
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
                                <th>Project Name</th>
                                <th>Customer</th>
                                <th>Status</th>
                                <th>Initial Value</th>
                                <th>Launch Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
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
                    <h5 class="modal-title text-dark">Create New Project</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body text-dark">
                    <form method="POST" id="projectCreateForm">
                        @csrf
                        @include('administration.projects.form', ['editable' => false])
                        <div class="float-right mt-3">
                            <button type="button" class="btn btn-xs btn-secondary" data-dismiss="modal">Close</button>
                            <button onclick="FormOptions.submitForm('projectCreateForm','createModal','dataTable')"
                                    type="button" class="btn btn-xs btn-primary">Save Project
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- EDIT MODAL --}}
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">Edit Project Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body text-dark">
                    <form method="POST" id="projectEditForm">
                        @csrf
                        @method('PUT')
                        @include('administration.projects.form', ['editable' => true])
                        <div class="float-right mt-3">
                            <button type="button" class="btn btn-xs btn-secondary" data-dismiss="modal">Close</button>
                            <button onclick="FormOptions.submitForm('projectEditForm','editModal','dataTable')"
                                    type="button" class="btn btn-xs btn-primary">Update Project
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
            // DataTable Initialization
            DataTableOption.initDataTable('dataTable', '/projects/table/data');

            // Select2 Initialization
            $('.select2').select2({
                placeholder: "-- Select Customer --",
                width: '100%'
            });

            // Validation
            FormOptions.initValidation('projectCreateForm', []);
            FormOptions.initValidation('projectEditForm', []);
        });

        $(document).on('click', '.project-edit-btn', function() {
            const data = $(this).data();

            editProject(data);
        });

        $(document).on('click', '.project-delete-btn', function() {
            const id = $(this).data('id');
            const url = $(this).data('url');

            FormOptions.deleteRecord(id, url, 'dataTable');
        });

        function editProject(data) {
            let form = $("#projectEditForm");
            form.find('.id').val(data.id);
            form.find('.project_name').val(data.name);
            form.find('.customer_id').val(data.customer).trigger('change');
            form.find('.status').val(data.status);
            form.find('.initial_value').val(data.value);
            form.find('.launch_date').val(data.launch);

            form.attr('action', '/projects/' + data.id);
            $('#editModal').modal('show');
        }
    </script>
@endpush