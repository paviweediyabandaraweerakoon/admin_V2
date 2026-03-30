@extends('layouts.app')
@section('title','Roles')
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
                        <li class="breadcrumb-item active" aria-current="page">Roles</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Roles</h4>
            </div>
            <div class="d-none d-md-block">
                <button class="btn btn-sm pd-x-15 btn-white btn-uppercase" data-toggle="modal" data-target="#createModal"><i data-feather="save" class="wd-10 mg-r-5"></i> NEW ROLE</button>
            </div>
        </div>

        <div class="card mg-b-10">
            <div class="card-body pd-y-30">
                <div class="table-responsive overflow-hidden">
                    <table id="dataTable" class="table compact">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Level</th>
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
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header p-3">
                    <h5 class="modal-title">Create role</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body mt-3 mb-3">
                    <form method="POST" id="roleCreateForm" class="needs-validation" novalidate>
                        @csrf
                        @include('administration.roles.form',['editable'=>false])
                        <div class="float-right">
                            <button type="button" class="btn btn-xs btn-secondary" data-dismiss="modal">Close</button>
                            <button
                                onclick="FormOptions.submitForm('roleCreateForm','createModal','dataTable')"
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
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header p-3">
                    <h5 class="modal-title">Edit role</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body mt-3 mb-3">
                    <form method="POST" id="roleEditForm" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')
                    @include('administration.roles.form-edit',['editable'=>true])
                    <div class="float-right">
                        <button type="button" class="btn btn-xs btn-secondary" data-dismiss="modal">Close</button>
                        <button
                            onclick="FormOptions.submitForm('roleEditForm','editModal','dataTable')"
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
        function initCheckBox() {
            $(".superParentCheckBox").click(
                function () {
                    $(this).parents('.super').find('.parentCheckBox').prop('checked', this.checked);
                    $(this).parents('.super').find('.secondParentCheckBox').prop('checked', this.checked);
                    $(this).parents('.super').find('.childCheckBox').prop('checked', this.checked);
                }
            );

            $(".parentCheckBox").click(
                function () {
                    $(this).parents('.main-parent').find('.childCheckBox').prop('checked', this.checked);

                    if ($(this).parents('.super').find('.superParentCheckBox').prop('checked') == true && this.checked == false)
                        $(this).parents('.super').find('.superParentCheckBox').prop('checked', false);

                    if ($(this).parents('.sub-super').find('.secondParentCheckBox').prop('checked') == true && this.checked == false)
                        $(this).parents('.sub-super').find('.secondParentCheckBox').prop('checked', false);


                    if (this.checked == true) {
                        var superFlag = true;
                        var secondParentFlag = true;
                        $(this).parents('.super').find('.childCheckBox').each(
                            function () {
                                if (this.checked == false)
                                    superFlag = false;
                            }
                        );
                        $(this).parents('.sub-super').find('.childCheckBox').each(
                            function () {
                                if (this.checked == false)
                                    secondParentFlag = false;
                            }
                        );
                        $(this).parents('.sub-super').find('.secondParentCheckBox').prop('checked', secondParentFlag);
                        $(this).parents('.super').find('.superParentCheckBox').prop('checked', superFlag);
                    }
                }
            );

            $(".secondParentCheckBox").click(
                function () {
                    $(this).parents(".sub-super").find('.main-parent').find('.parentCheckBox').prop('checked', this.checked);
                    $(this).parents(".sub-super").find('.childCheckBox').prop('checked', this.checked);

                    if ($(this).parents('.super').find('.superParentCheckBox').prop('checked') == true && this.checked == false)
                        $(this).parents('.super').find('.superParentCheckBox').prop('checked', false);

                    if ($(this).parents(".sub-super").find('.main-parent').find('.parentCheckBox').prop('checked') == true && this.checked == false)
                        $(this).parents(".sub-super").find('.main-parent').find('.parentCheckBox').prop('checked', false);


                    if (this.checked == true) {
                        var superFlag = true;
                        var flag = true;
                        $(this).parents('.super').find('.childCheckBox').each(
                            function () {
                                if (this.checked == false)
                                    superFlag = false;
                            }
                        );
                        $(this).parents(".sub-super").find('.main-parent').find('.childCheckBox').each(
                            function () {
                                if (this.checked == false)
                                    flag = false;
                            }
                        );
                        $(this).parents(".sub-super").find('.main-parent').find('.parentCheckBox').prop('checked', flag);
                        $(this).parents('.super').find('.superParentCheckBox').prop('checked', superFlag);
                    }
                }
            );

            //clicking the last unchecked or checked checkbox should check or uncheck the parent checkbox
            $('.childCheckBox').click(
                function () {
                    if ($(this).parents('.super').find('.superParentCheckBox').prop('checked') == true && this.checked == false)
                        $(this).parents('.super').find('.superParentCheckBox').prop('checked', false);

                    if ($(this).parents('.main-parent').find('.parentCheckBox').prop('checked') == true && this.checked == false)
                        $(this).parents('.main-parent').find('.parentCheckBox').prop('checked', false);

                    if ($(this).parents('.sub-super').find('.secondParentCheckBox').prop('checked') == true && this.checked == false)
                        $(this).parents('.sub-super').find('.secondParentCheckBox').prop('checked', false);

                    if (this.checked == true) {
                        var flag = true;
                        var superFlag = true;
                        var secondParentFlag = true;
                        $(this).parents('.super').find('.childCheckBox').each(
                            function () {
                                if (this.checked == false)
                                    superFlag = false;
                            }
                        );
                        $(this).parents('.main-parent').find('.childCheckBox').each(
                            function () {
                                if (this.checked == false)
                                    flag = false;
                            }
                        );
                        $(this).parents('.sub-super').find('.childCheckBox').each(
                            function () {
                                if (this.checked == false)
                                    secondParentFlag = false;
                            }
                        );
                        $(this).parents('.sub-super').find('.secondParentCheckBox').prop('checked', secondParentFlag);
                        $(this).parents('.main-parent').find('.parentCheckBox').prop('checked', flag);
                        $(this).parents('.super').find('.superParentCheckBox').prop('checked', superFlag);
                    }
                }
            );
        }

        function edit(role) {
            let id = role.dataset.id;
            let name = role.dataset.name;
            let level = role.dataset.level;
            let permissions = role.dataset.permissions;
            $("#roleEditForm").find('.name').val(name);
            $("#roleEditForm").find('.level').val(level);

            var values = "Test,Prof,Off";
            $.each(values.split(","), function (i, e) {
                $("#strings option[value='" + e + "']").prop("selected", true);
            });

            // $("#roleEditForm").find('.input_tags').val(JSON.parse(permissions));
            // $("#roleEditForm").find('.input_tags').trigger('change');

            $("#roleEditForm").attr('action', '/roles/' + id);
            ModalOptions.toggleModal('editModal');
            $.ajax({
                url: '/roles/render/form',
                type: 'GET',
                data:{id:id},
                success: function success(result) {
                    $('#permissionForm').empty();
                    $('#permissionForm').append(result);
                    initCheckBox();
                },
                error: function error(XMLHttpRequest, textStatus, errorThrown) {

                }
            })
        }

        $(document).ready(function() {
            DataTableOption.initDataTable('dataTable', 'roles/table/data');
            FormOptions.initValidation('roleCreateForm', []);
            FormOptions.initValidation('roleEditForm',[]);
            initCheckBox();
        });
    </script>
@endpush
