@extends('layouts.app')
@section('title',__('Verify Your Email Address'))
@push('styles')
    <link rel="stylesheet" href="{{asset('plugins/dashforge/css/dashforge.auth.css')}}">
@endpush
@section('content')
    <div class="container d-flex justify-content-center ht-100p">
        <div class="mx-wd-300 wd-sm-450 ht-100p d-flex flex-column align-items-center justify-content-center">
            <div class="wd-80p wd-sm-300 mg-b-15"><img src="{{asset('img/img17.png')}}" class="img-fluid" alt=""></div>
            <h4 class="tx-20 tx-sm-24">{{ __('Verify Your Email Address') }}</h4>
            @if (session('resent'))
                <div class="alert alert-success" role="alert">
                    {{ __('A fresh verification link has been sent to your email address.') }}
                </div>
            @endif
            <p class="tx-color-03 mg-b-30 tx-center">
                {{ __('Before proceeding, please check your email for a verification link.') }}
                {{ __('If you did not receive the email') }},
            </p>
            <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <div class="tx-13 tx-lg-14 mg-b-40">
                    <button class="btn btn-brand-02 d-inline-flex align-items-center">Resend Verification</button>
                    <a href="mailto:support@mylinex.com" class="btn btn-white d-inline-flex align-items-center mg-l-5">Contact Support</a>
                </div>
            </form>
        </div>
    </div><!-- container -->
@endsection
