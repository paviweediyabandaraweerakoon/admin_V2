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
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                        <li class="breadcrumb-item"><a href="#">Administration</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Projects</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Projects</h4>
            </div>
            <div class="d-none d-md-block">
                <button class="btn btn-sm pd-x-15 btn-white btn-uppercase" data-toggle="modal" data-target="#createModal">
                    <i data-feather="save" class="wd-10 mg-r-5"></i> NEW PROJECT
                </button>
            </div>
        </div>

        <div class="card mg-b-10">
            <div class="card-body pd-y-30">
                <div class="table-responsive overflow-hidden">
                    <table id="projectDataTable" class="table compact">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Project Name</th>
                                <th>Initial Value</th>
                                <th>Status</th>
                                <th>Launch Date</th>
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

    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header p-3">
                    <h5 class="modal-title">Create Project</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body mt-3 mb-3">
                    <form method="POST" id="projectCreateForm">
                        @csrf
                        @include('administration.projects.form', [
                            'editable' => false,
                            'id' => 'projectCreateForm',
                        ])
                        <div class="float-right">
                            <button type="button" class="btn btn-xs btn-secondary" data-dismiss="modal">Close</button>
                            <button onclick="FormOptions.submitForm('projectCreateForm','createModal','projectDataTable')"
                                type="button" class="btn btn-xs btn-primary">Create
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header p-3">
                    <h5 class="modal-title">Edit Project</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body mt-3 mb-3">
                    <form method="POST" id="projectEditForm">
                        @csrf
                        @method('PUT')
                        @include('administration.projects.form', [
                            'editable' => true,
                            'id' => 'projectEditForm',
                        ])
                        <div class="float-right">
                            <button type="button" class="btn btn-xs btn-secondary" data-dismiss="modal">Close</button>
                            <button onclick="FormOptions.submitForm('projectEditForm','editModal','projectDataTable')" 
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
        $(document).ready(function() {
            // DataTable Initialization
            DataTableOption.initDataTable('projectDataTable', 'projects/table/data');

            $('.select2').select2({
                searchInputPlaceholder: 'Search options',
                width: '100%'
            });

            // Form Validations
            FormOptions.initValidation('projectCreateForm', []);
            FormOptions.initValidation('projectEditForm', []);
        });

        function edit(result) {
            let id = result.dataset.id;
            let project_name = result.dataset.project_name;
            let customer_id = result.dataset.customer_id;
            let initial_value = result.dataset.initial_value;
            let status = result.dataset.status;
            let amc_percentage = result.dataset.amc_percentage;
            let amc_durations_month = result.dataset.amc_durations_month;
            let launch_date = result.dataset.launch_date;
            let description = result.dataset.description;

            $("#projectEditForm").find('.id').val(id);
            $("#projectEditForm").find('.project_name').val(project_name);
            $("#projectEditForm").find('.customer_id').val(customer_id).trigger('change');
            $("#projectEditForm").find('.initial_value').val(initial_value);
            $("#projectEditForm").find('.status').val(status);
            $("#projectEditForm").find('.amc_percentage').val(amc_percentage);
            $("#projectEditForm").find('.amc_durations_month').val(amc_durations_month);
            $("#projectEditForm").find('.launch_date').val(launch_date);
            $("#projectEditForm").find('.description').val(description);

            $("#projectEditForm").attr('action', '/projects/' + id);
            ModalOptions.toggleModal('editModal');
        }
    </script>
@endpush