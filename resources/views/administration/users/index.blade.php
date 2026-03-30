@extends('layouts.app')
@section('title', 'Users')
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
                        <li class="breadcrumb-item active" aria-current="page">Users</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Users</h4>
            </div>
            <div class="d-none d-md-block">
                <button class="btn btn-sm pd-x-15 btn-white btn-uppercase" data-toggle="modal" data-target="#createModal"><i
                        data-feather="save" class="wd-10 mg-r-5"></i> NEW USER
                </button>
            </div>
        </div>

        <div class="card mg-b-10">
            <div class="card-body pd-y-30">
                <div class="table-responsive overflow-hidden">
                    <table id="dataTable" class="table compact">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Landing Page</th>
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
                    <h5 class="modal-title">Create user</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body mt-3 mb-3">
                    <form method="POST" id="userCreateForm">
                        @csrf
                        @include('administration.users.form', [
                            'editable' => false,
                            'reset' => false,
                            'id' => 'userCreateForm',
                        ])
                        <div class="float-right">
                            <button type="button" class="btn btn-xs btn-secondary" data-dismiss="modal">Close</button>
                            <button onclick="FormOptions.submitForm('userCreateForm','createModal','dataTable')"
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
                    <h5 class="modal-title">Edit user</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body mt-3 mb-3">
                    <form method="POST" id="userEditForm">
                        @csrf
                        @method('PUT')
                        @include('administration.users.form', [
                            'editable' => true,
                            'reset' => false,
                            'id' => 'userEditForm',
                        ])
                        <div class="float-right">
                            <button type="button" class="btn btn-xs btn-secondary" data-dismiss="modal">Close</button>
                            <button onclick="FormOptions.submitForm('userEditForm','editModal','dataTable')" type="button"
                                class="btn btn-xs btn-primary">Update now
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="userResetModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalForms"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header p-3">
                    <h5 class="modal-title">Reset user password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body mt-3 mb-3">
                    <form method="POST" id="userResetForm">
                        @csrf
                        @method('PUT')
                        @include('administration.users.form', ['reset' => true, 'editable' => false])
                        <div class="float-right">
                            <button type="button" class="btn btn-xs btn-secondary" data-dismiss="modal">Close</button>
                            <button onclick="FormOptions.submitForm('userResetForm','userResetModal','userTable')"
                                type="button" class="btn btn-xs btn-primary">Reset now
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
        var roles = [];
        var landing_page;

        function edit(result) {
            let id = result.dataset.id;
            let name = result.dataset.name;
            let email = result.dataset.email;
            roles = result.dataset.roles;
            landing_page = result.dataset.landing_page;
            roles = JSON.parse(roles);
            $("#userEditForm").find('.id').val(id);
            $("#userEditForm").find('.name').val(name);
            $("#userEditForm").find('.email').val(email);

            $("#userEditForm").find('.roles').val(roles);
            $("#userEditForm").find('.roles').trigger('change')

            $("#userEditForm").attr('action', '/users/' + id);
            ModalOptions.toggleModal('editModal');
        }

        $('#createModal').on('show.bs.modal', function() {
            $("#userCreateForm .select2-limit").val(null).trigger("change");
        })

        function reset(user) {
            let id = user.dataset.id;
            $("#userResetForm").attr('action', '/users/' + id + '/reset');
            ModalOptions.toggleModal('userResetModal');
        }

        function changeLandingPages(form) {
            $('.landing_page').empty();
            var formId = $(form).closest("form").attr("id");
            if (formId == 'userCreateForm') roles = [];
            var select2Val = $(`#${formId} .select2-limit`).val()
            var role = select2Val ? select2Val : roles;
            $.ajax({
                url: "users/get-landing-page",
                type: 'post',
                data: {
                    role: role,
                    _token: "{{ csrf_token() }}",
                },
                success: function(result) {
                    $.each(result, function(url, title) {
                        $(`#${formId} .landing_page`).append('<option value="' + url + '">' + title +
                            '</option>');
                    });
                    if (formId == 'userEditForm') {
                        $("#userEditForm").find('.landing_page').val(landing_page);
                    }
                },
                error: function(error) {
                    Notifications.showErrorMsg('Error occured when fetching landing pages!');
                }
            });
        }

        function resetAttempt(user) {
            let id = user.dataset.id;

            Swal.fire({
                title: 'Are you sure ?',
                text: 'User account will unlock',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: "/users/reset/login/" + id,
                        type: 'post',
                        data: {
                            id: id,
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(result) {
                            Swal.fire(
                                'Login attempt count reset successfully!',
                                result.message,
                                'success'
                            );
                            var table = $('#dataTable').DataTable();
                            table.ajax.reload();
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire({
                        title: "Cancelled!",
                        text: "Unlock has been successfully cancelled",
                        type: 'error',
                        showConfirmButton: false,
                        timer: 2000
                    })
                }
            })
        }

        $(document).ready(function() {

            // Limit selection
            $('.select2-limit').select2({
                searchInputPlaceholder: 'Search options'
            });

            $.validator.addMethod(
                "regex",
                function(value, element, regexp) {
                    var re = new RegExp(regexp);
                    return this.optional(element) || re.test(value);
                },
                "Password must be of 8-14 characters in length and have one or more special character and one or more number and one or more uppercase character."
            );

            DataTableOption.initDataTable('dataTable', 'users/table/data');

            let create_rules = {
                password: {
                    regex: "^(?=.*[0-9])(?=.*[!@#$%^&*])(?=.*[A-Z])(?=.*[a-z])[a-zA-Z0-9!@#$%^&*]{8,14}$",
                    minlength: 8
                },
                password_confirmation: {
                    regex: "^(?=.*[0-9])(?=.*[!@#$%^&*])(?=.*[A-Z])(?=.*[a-z])[a-zA-Z0-9!@#$%^&*]{8,14}$",
                    minlength: 8,
                    equalTo: "#userCreateForm #password"
                }
            };

            let reset_rules = {
                password: {
                    regex: "^(?=.*[0-9])(?=.*[!@#$%^&*])(?=.*[A-Z])(?=.*[a-z])[a-zA-Z0-9!@#$%^&*]{8,14}$",
                    minlength: 8
                },
                password_confirmation: {
                    regex: "^(?=.*[0-9])(?=.*[!@#$%^&*])(?=.*[A-Z])(?=.*[a-z])[a-zA-Z0-9!@#$%^&*]{8,14}$",
                    minlength: 8,
                    equalTo: "#userResetForm #password"
                }
            };

            $.validator.setDefaults({
                ignore: ":hidden:not(.select2-limit)"
            });
            FormOptions.initValidation('userCreateForm', create_rules, 'select2-limit');
            FormOptions.initValidation('userResetForm', reset_rules);
            FormOptions.initValidation('userEditForm', [], 'select2-limit');
        });
    </script>
@endpush
