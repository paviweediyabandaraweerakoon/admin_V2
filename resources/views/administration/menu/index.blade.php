@extends('layouts.app')
@section('title', 'Menu')
@push('styles')
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables.net-dt/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables.net-responsive-dt/css/responsive.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/tagsinput/bootstrap-tagsinput.css') }}">
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
                        <li class="breadcrumb-item active" aria-current="page">Menu</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Menu</h4>
            </div>
            <div class="d-none d-md-block">
                <button class="btn btn-sm pd-x-15 btn-white btn-uppercase" data-toggle="modal" data-target="#createModal"><i
                        data-feather="save" class="wd-10 mg-r-5"></i> NEW MENU
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
                                <th>Icon</th>
                                <th>Route</th>
                                <th>Parent Menu</th>
                                <th>Permissions</th>
                                <th>Menu Order</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div><!-- table-responsive -->
            </div><!-- card-body -->
        </div>
    </div>

    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalForms"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header p-3">
                    <h5 class="modal-title">Create menu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body mt-3 mb-3">
                    <form method="POST" id="menuCreateForm">
                        @csrf
                        @include('administration.menu.form', ['editable' => false])
                        <div class="float-right">
                            <button type="button" class="btn btn-xs btn-secondary" data-dismiss="modal">Close</button>
                            <button onclick="FormOptions.submitForm('menuCreateForm','createModal','dataTable')" type="button"
                                class="btn btn-xs btn-primary">Create
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
                    <h5 class="modal-title">Edit menu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body mt-3 mb-3">
                    <form method="POST" id="menuEditForm" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')
                        @include('administration.menu.form', ['editable' => true])
                        <div class="float-right">
                            <button type="button" class="btn btn-xs btn-secondary" data-dismiss="modal">Close</button>
                            <button onclick="FormOptions.submitForm('menuEditForm','editModal','dataTable')" type="button"
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
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables.net-dt/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables.net-responsive-dt/js/responsive.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-validation/jquery.validate.js') }}"></script>
    <script src="{{ asset('plugins/jquery-form/jquery.form.min.js') }}"></script>
    <script src="{{ asset('plugins/tagsinput/bootstrap-tagsinput.min.js') }}"></script>
    <script src="{{ asset('js/dataTable.js') }}"></script>
    <script src="{{ asset('js/FormOptions.js') }}"></script>
    <script src="{{ asset('js/modal.js') }}"></script>
    <script>
        function edit(result) {
            let id = result.dataset.id;
            let title = result.dataset.title;
            let url = result.dataset.url;
            let parent_id = result.dataset.parent_id;
            let icon = result.dataset.icon;
            let order = result.dataset.menu_order;
            let permissions = JSON.parse(result.dataset.permissions);

            if (parent_id == null || parent_id === "") {
                $("#menuEditForm").find('.isParent').prop("checked", true);
                $("#menuEditForm").find('.parentRow').addClass('d-none');
                $("#menuEditForm").find('.routeRow').addClass('d-none');
                $("#menuEditForm").find('.permissionRow').addClass('d-none');
                $.each(permissions, function(index, value) {
                    $('.tgs').tagsinput('add', value);
                });
                // $("#menuEditForm").find('.tagsRow').addClass('d-none');
            } else {
                $("#menuEditForm").find('.isParent').prop("checked", false);
                $("#menuEditForm").find('.parentRow').removeClass('d-none');
                $("#menuEditForm").find('.routeRow').removeClass('d-none');
                $("#menuEditForm").find('.permissionRow').removeClass('d-none');
                // $("#menuEditForm").find('.tagsRow').removeClass('d-none');

                $("#menuEditForm").find('.parent_id').val(parent_id);


                $.each(permissions, function(index, value) {
                    switch (value) {
                        case title.toLowerCase() + ' index':
                            $('.index').prop('checked', true);
                            break

                        case title.toLowerCase() + ' create':
                            $('.create').prop('checked', true);
                            break

                        case title.toLowerCase() + ' show':
                            $('.show').prop('checked', true);
                            break

                        case title.toLowerCase() + ' edit':
                            $('.edit').prop('checked', true);
                            break

                        case title.toLowerCase() + ' delete':
                            $('.delete').prop('checked', true);
                            break

                        default:
                            $('.tgs').tagsinput('add', value);
                    }
                });

            }

            $("#menuEditForm").find('.id').val(id);
            $("#menuEditForm").find('.title').val(title);
            $("#menuEditForm").find('.url_route').val(url);
            $("#menuEditForm").find('.icon').val(icon);
            $("#menuEditForm").find('.menu_order').val(order);

            $("#menuEditForm").attr('action', '/menu/' + id);
            ModalOptions.toggleModal('editModal');
        }

        function resortInputs() {
            $('.index').prop('checked', false);
            $('.create').prop('checked', false);
            $('.show').prop('checked', false);
            $('.edit').prop('checked', false);
            $('.delete').prop('checked', false);
            $('.tgs').tagsinput('removeAll');
            $('.isParent').prop("checked", false);
            $('.parentRow').removeClass('d-none');
            $('.routeRow').removeClass('d-none');
            $('.permissionRow').removeClass('d-none');
            $('.tagsRow').removeClass('d-none');
        }

        function change() {
            if ($('.isParent').is(':checked')) {
                $('.parentRow').addClass('d-none');
                $('.routeRow').addClass('d-none');
                $('.permissionRow').addClass('d-none');
                // $('.tagsRow').addClass('d-none');
            } else {
                $('.parentRow').removeClass('d-none');
                $('.routeRow').removeClass('d-none');
                $('.permissionRow').removeClass('d-none');
                // $('.tagsRow').removeClass('d-none');
            }
        }

        function validation(formId) {
            let validation = {
                url: {
                    required: {
                        depends: function(element) {
                            if ($(`#${formId} .isParent`).is(':checked')) {
                                return false;
                            } else {
                                return true;
                            }
                        }
                    }
                }
            }

            return validation;
        }

        $(document).ready(function() {
            DataTableOption.initDataTable('dataTable', 'menu/table/data');
            FormOptions.initValidation('menuCreateForm', validation('menuCreateForm'));
            FormOptions.initValidation('menuEditForm', validation('menuEditForm'));

            $('#editModal').on('hidden.bs.modal', function() {
                resortInputs();
            });
            $('#createModal').on('hidden.bs.modal', function() {
                // resortInputs();
                change();
            });

        });
    </script>
@endpush
