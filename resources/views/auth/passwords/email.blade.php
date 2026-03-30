@extends('layouts.app')
@section('title',__('Reset Password'))
@push('styles')
    <link rel="stylesheet" href="{{asset('plugins/dashforge/css/dashforge.auth.css')}}">
@endpush
@push('scripts')
    <script src="{{asset('plugins/jquery-validation/jquery.validate.js')}}"></script>
    <script>
        $("#passwordResetForm").validate({
            errorPlacement: function(error, element) {
                if (element.attr("name") == "email") {
                    error.appendTo(".emailError");
                } else {
                    error.insertAfter(element)
                }
            }
        });
    </script>
@endpush
@section('content')
    <div class="container d-flex justify-content-center ht-100p">
        <div class="mx-wd-300 wd-sm-450 ht-100p d-flex flex-column align-items-center justify-content-center">
            <div class="wd-80p wd-sm-300 mg-b-15"><img src="{{asset('img/img18.png')}}" class="img-fluid" alt=""></div>
            <h4 class="tx-20 tx-sm-24">{{ __('Reset Password') }}</h4>
            <p class="tx-color-03 mg-b-30 tx-center">Enter your email address and we will send you a link to reset your password.</p>
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <form method="POST" action="{{ route('password.email') }}" id="passwordResetForm" class="mg-b-40">
                @csrf
                <div class="wd-100p d-flex flex-column flex-sm-row">
                    <input id="email" type="email" class="form-control wd-sm-250 flex-fill @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="{{ __('E-Mail Address') }}" autofocus>
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    <button type="submit" class="btn btn-brand-02 mg-sm-l-10 mg-t-10 mg-sm-t-0">
                        Send Link
                    </button>
                </div>
                <div class="wd-100p d-flex flex-column flex-sm-row emailError"></div>
            </form>
        </div>
    </div><!-- container -->
@endsection
