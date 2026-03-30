@extends('layouts.app')
@section('title', 'Profile Setting')
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
                        <li class="breadcrumb-item active" aria-current="page">Profile Setting</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Profile Setting</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 col-lg-4 mg-t-10">
                <div class="card">
                    <div class="card-header pd-b-0 bd-b-0 pd-t-20 pd-lg-t-25 pd-l-20 pd-lg-l-25">
                        <h3 class="text-capitalize font-weight-500 text-white">{{ $user->name }}</h3>
                        <p class="tx-13 tx-color-03 mg-b-0">{{ $user->roles[0]->name }}</p>
                    </div><!-- card-header -->
                    <div class="card-body pd-sm-20 pd-lg-25">
                        <div class="row row-sm text-center">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <h2 class="mg-b-5 text-white">
                                    {{ $user->hasRole('Super Admin') || $user->hasRole('Admin') ? 'unlimited' : $permissions->count() }}
                                </h2>
                                <h4 class="tx-14 tx-color-03 mg-b-0">Permissions</h4>
                            </div><!-- col -->
                        </div><!-- row -->
                        <br>
                        <div class="row row-sm">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <span><i class="ion ion-md-calendar tx-18 text-danger mr-10"></i><span>Last
                                        Login:</span></span>
                                <span
                                    class="ml-5 text-white">{{ \Carbon\Carbon::parse($user->last_login)->diffForHumans() }}</span>
                            </div><!-- col -->
                        </div><!-- row -->
                        <hr>
                        <div class="row row-sm">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                @if ($user->hasRole('Super Admin') || $user->hasRole('Admin'))
                                    <div class="col-12">
                                        <span class="text-capitalize font-14">unlimited permissions</span>
                                    </div>
                                @else
                                    @foreach ($permissions as $permission)
                                        <div class="col-12">
                                            <span class="text-capitalize font-14">{{ $permission }}</span>
                                        </div>
                                    @endforeach
                                @endif
                            </div><!-- col -->
                        </div><!-- row -->
                    </div><!-- card-body -->
                </div><!-- card -->
            </div><!-- col -->

            <div class="col-md-8 col-lg-8 mg-t-10">
                <div class="card">
                    <div class="card-header pd-b-0 bd-b-0 pd-t-20 pd-lg-t-25 pd-l-20 pd-lg-l-25">
                        <h4 class="text-capitalize font-weight-500 text-white">Edit Primary Data</h4>
                    </div><!-- card-header -->
                    <div class="card-body pd-sm-20 pd-lg-25">
                        <form method="POST" id="ProfileEditForm" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')
                        @include('administration.users.form', [
                            'editable' => true,
                            'profile_editable' => true,
                            'reset' => false,
                        ])
                        <input type="hidden" name="roles" class="roles">
                        <div class="float-right">
                            <button type="button" class="btn btn-xs btn-secondary"
                                onclick="clearForm('#ProfileEditForm', '.landing_page', true)">Clear</button>
                            <button onclick="FormOptions.submitForm('ProfileEditForm', '', '', false)" type="button"
                                class="btn btn-xs btn-primary">Update now
                            </button>
                        </div>
                        </form>
                    </div>
                </div>
                </br>
                <div class="card">
                    <div class="card-header pd-b-0 bd-b-0 pd-t-20 pd-lg-t-25 pd-l-20 pd-lg-l-25">
                        <h4 class="text-capitalize font-weight-500 text-white">Change Password</h4>
                    </div><!-- card-header -->
                    <div class="modal-body mt-3 mb-3">
                       <form method="POST" id="userResetForm" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')
                       @include('administration.users.form', ['reset' => true, 'editable' => false])
                        <div class="float-right">
                            <button type="button" class="btn btn-xs btn-secondary"
                                onclick="clearForm('#userResetForm')">Clear</button>
                            <button onclick="FormOptions.submitForm('userResetForm')" type="button"
                                class="btn btn-xs btn-primary">Reset now
                            </button>
                        </div>
                       </form>
                    </div>
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
    <script>
        var landing_page;
        $(document).ready(function() {
            let id = "{{ $user->id }}";
            let name = "{{ $user->name }}";
            let email = "{{ $user->email }}";
            let roles = "{{ $user->roles[0]->id }}";
            landing_page = "{{ $user->landing_page }}";

            $("#ProfileEditForm").find('.id').val(id);
            $("#ProfileEditForm").find('.name').val(name);
            $("#ProfileEditForm").find('.email').val(email);
            $("#ProfileEditForm").find('.roles').val(roles);

            $("#ProfileEditForm").attr('action', '/users/' + id);

            $("#userResetForm").attr('action', '/users/' + id + '/reset');

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

            let rules = {
                password: {
                    regex: "^(?=.*[0-9])(?=.*[!@#$%^&*])(?=.*[A-Z])(?=.*[a-z])[a-zA-Z0-9!@#$%^&*]{8,14}$",
                    minlength: 8
                },
                password_confirmation: {
                    regex: "^(?=.*[0-9])(?=.*[!@#$%^&*])(?=.*[A-Z])(?=.*[a-z])[a-zA-Z0-9!@#$%^&*]{8,14}$",
                    minlength: 8,
                    equalTo: "#password"
                }
            };

            changeLandingPages('ProfileEditForm');
            $.validator.setDefaults({
                ignore: ":hidden:not(.select2-limit)"
            });
            FormOptions.initValidation('userResetForm', rules);
            FormOptions.initValidation('ProfileEditForm', [], 'select2-limit');
        });

        function changeLandingPages(form) {
            var role = $(`#${form} .roles`).val();
            $.ajax({
                url: "users/get-landing-page",
                type: 'post',
                data: {
                    role: role,
                    _token: "{{ csrf_token() }}",
                },
                success: function(result) {
                    $.each(result, function(url, title) {
                        $(`#${form} .landing_page`).append('<option value="' + url + '">' + title +
                            '</option>');
                    });
                    $(`#${form}`).find('.landing_page').val(landing_page);

                },
                error: function(error) {
                    Notifications.showErrorMsg('Error occured when fetching landing pages!');
                }
            });
        }

        function clearForm(formId, field = '', fieldClear = false) {
            $(formId).validate().resetForm();
            $(formId).trigger('reset');
            if (fieldClear) {
                $(field).val('')
            }
        }
    </script>
@endpush
