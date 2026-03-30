@extends('layouts.app')
@section('title','Permissions')
@push('styles')
    <link rel="stylesheet" href="{{asset('plugins/sweetalert2/sweetalert2.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables.net-dt/css/jquery.dataTables.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables.net-responsive-dt/css/responsive.dataTables.min.css')}}">
@endpush
@push('dashforge-css')
    <link rel="stylesheet" href="{{asset('plugins/dashforge/css/dashforge.demo.css')}}">
@endpush
@section('content')
    <div class="container">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                        <li class="breadcrumb-item"><a href="#">Administration</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Permissions</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Permissions</h4>
            </div>
            <div class="d-none d-md-block">
                <button class="btn btn-sm pd-x-15 btn-white btn-uppercase" data-toggle="modal" data-target="#createModal"><i data-feather="save" class="wd-10 mg-r-5"></i> NEW PERMISSION</button>
            </div>
        </div>

        <div class="card mg-b-10">
            <div class="card-body pd-y-30">
                <div class="table-responsive overflow-hidden">
                    <table id="dataTable" class="table compact">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Menu</th>
                            <th>Guard</th>
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

    <div class="modal fade" id="createModal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalForms"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header p-3">
                    <h5 class="modal-title">Create permission</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body mt-3 mb-3">
                    <form method="POST" id="permissionCreateForm">
                        @csrf
                        @include('administration.permissions.form',['editable'=>false])
                        <div class="float-right">
                            <button type="button" class="btn btn-xs btn-secondary" data-dismiss="modal">Close</button>
                            <button
                                onclick="FormOptions.submitForm('permissionCreateForm','createModal','dataTable')"
                                type="button" class="btn btn-xs btn-primary">Create
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalForms"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header p-3">
                    <h5 class="modal-title">Edit permission</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body mt-3 mb-3">
                    <form method="POST" id="permissionEditForm" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')
                        @include('administration.permissions.form')
                        <div class="float-right">
                            <button type="button" class="btn btn-xs btn-secondary" data-dismiss="modal">Close</button>
                            <button
                                onclick="FormOptions.submitForm('permissionEditForm','editModal','dataTable')"
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
    <script src="{{asset('plugins/sweetalert2/sweetalert2.js')}}"></script>
    <script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/datatables.net-dt/js/dataTables.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('plugins/datatables.net-responsive-dt/js/responsive.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/jquery-validation/jquery.validate.js')}}"></script>
    <script src="{{asset('plugins/jquery-form/jquery.form.min.js')}}"></script>
    <script src="{{asset('js/dataTable.js')}}"></script>
    <script src="{{asset('js/FormOptions.js')}}"></script>
    <script src="{{asset('js/modal.js')}}"></script>
    <script>
        function edit(result) {
            let id = result.dataset.id;
            let name = result.dataset.name;
            let menu_id = result.dataset.menu_id;
            $("#permissionEditForm").find('.id').val(id);
            $("#permissionEditForm").find('.name').val(name);
            $("#permissionEditForm").find('.menu_id').val(menu_id);

            console.log(menu_id)
            $("#permissionEditForm").attr('action', '/permissions/' + id);
            ModalOptions.toggleModal('editModal');
        }
        $(document).ready(function() {
            DataTableOption.initDataTable('dataTable', 'permissions/table/data');
            FormOptions.initValidation('permissionCreateForm', []);
            FormOptions.initValidation('permissionEditForm',[]);
        });
    </script>
@endpush
