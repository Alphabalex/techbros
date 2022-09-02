@extends('addon:multivendor::seller.layouts.app')

@section('content')
@php
    $shop = auth()->user()->shop;
@endphp
<section class="py-4 py-lg-5">
    <div class="container">
        @if (seller_package_validity_check($shop->seller_package, $shop->package_invalid_at) == 'no_package' )
            <div class="alert alert-danger">
                {{ translate("You don't have any active package") }}
            </div>
        @elseif (seller_package_validity_check($shop->seller_package, $shop->package_invalid_at) == 'expired' )
            <div class="alert alert-danger">
                {{ translate('Your current package') }}
                <span class="fw-600">{{ $shop->seller_package->name }}</span>
                {{ translate('has been expired at') }}
                {{ $shop->package_invalid_at }}
            </div>
        @else
            <div class="alert alert-info">
                {{ translate('Your current package') }}
                <span class="fw-600">{{ $shop->seller_package->name }}</span>
                {{ translate('will expire at') }}
                {{ $shop->package_invalid_at }}
            </div>
        @endif
        
        <div class="row row-cols-xxl-4 row-cols-lg-3 row-cols-md-2 row-cols-1 gutters-10 justify-content-center">
            @foreach ($seller_packages as $key => $seller_package)
                <div class="col">
                    <div class="card overflow-hidden">
                        <div class="card-body">
                            <div class="text-center mb-4 mt-3">
                                <img class="mw-100 mx-auto mb-4" src="{{ uploaded_asset($seller_package->logo) }}" height="100">
                                <h5 class="mb-3 h5 fw-600">{{$seller_package->getTranslation('name')}}</h5>
                            </div>
                            <ul class="list-group list-group-raw fs-15">
                                <li class="list-group-item py-2">
                                    <i class="las la-check text-success mr-2"></i>{{ $seller_package->product_upload_limit }} {{translate('Product Upload Limit')}}
                                </li>
                            </ul>
                            <ul class="list-group list-group-raw fs-15 mb-5">
                                <li class="list-group-item py-2">
                                    <i class="las la-check text-success mr-2"></i>{{ $seller_package->commission }}% {{translate('Commission')}}
                                </li>
                            </ul>
                            <div class="mb-5 d-flex align-items-center justify-content-center">
                                @if ($seller_package->amount == 0)
                                    <span class="h2 fw-600 lh-1 mb-0">{{ translate('Free') }}</span>
                                @else
                                    <span class="h2 fw-600 lh-1 mb-0">{{format_price($seller_package->amount)}}</span>
                                @endif
                                <span class="text-secondary border-left ml-2 pl-2">{{$seller_package->duration}} {{translate('Days')}}</span>
                            </div>

                            <div class="text-center">
                                @if ($seller_package->amount == 0)
                                    <button class="btn btn-primary fw-600" onclick="get_free_package({{ $seller_package->id}})">{{ translate('Free Package')}}</button>
                                @else
                                    <button class="btn btn-primary fw-600" onclick="select_payment_method_modal({{ $seller_package->id}})">{{ translate('Purchase Package')}}</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

@endsection

@section('modal')

<div class="modal fade" id="select_payment_method_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ translate('Purchase Your Package') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="" id="package_payment_form" action="{{ route('seller.packages.purchase') }}" method="post">
                @csrf
                <input type="hidden" name="seller_package_id" value="">
                <div class="modal-body">
                    <div class="mb-2 fs-15">
                        <label>{{translate('Select a Payment Method')}}</label>
                    </div>
                    <div class="row">
                        @if (get_setting('paypal_payment') == 1)
                            <div class="col-6 col-md-4">
                                <label class="aiz-megabox d-block">
                                    <input type="radio" name="payment_option" value="paypal" required>
                                    <span class="d-block p-3 aiz-megabox-elem text-center">
                                        <img src="{{ static_asset('assets/img/cards/paypal.png') }}" class="img-fluid w-100">
                                        <span class="fw-700 fs-13 mt-2 d-inline-block">{{translate('Paypal')}}</span>
                                    </span>
                                </label>
                            </div>
                        @endif
                        @if (get_setting('stripe_payment') == 1)
                            <div class="col-6 col-md-4">
                                <label class="aiz-megabox d-block">
                                    <input type="radio" name="payment_option" value="stripe" required>
                                    <span class="d-block p-3 aiz-megabox-elem text-center">
                                        <img src="{{ static_asset('assets/img/cards/stripe.png') }}" class="img-fluid w-100">
                                        <span class="fw-700 fs-13 mt-2 d-inline-block">{{translate('Stripe')}}</span>
                                    </span>
                                </label>
                            </div>
                        @endif
                        @if (get_setting('sslcommerz_payment') == 1)
                            <div class="col-6 col-md-4">
                                <label class="aiz-megabox d-block">
                                    <input type="radio" name="payment_option" value="sslcommerz" required>
                                    <span class="d-block p-3 aiz-megabox-elem text-center">
                                        <img src="{{ static_asset('assets/img/cards/sslcommerz.png') }}" class="img-fluid w-100">
                                        <span class="fw-700 fs-13 mt-2 d-inline-block">{{translate('sslcommerz')}}</span>
                                    </span>
                                </label>
                            </div>
                        @endif
                        @if (get_setting('paystack_payment') == 1)
                            <div class="col-6 col-md-4">
                                <label class="aiz-megabox d-block">
                                    <input type="radio" name="payment_option" value="paystack" required>
                                    <span class="d-block p-3 aiz-megabox-elem text-center">
                                        <img src="{{ static_asset('assets/img/cards/paystack.png') }}" class="img-fluid w-100">
                                        <span class="fw-700 fs-13 mt-2 d-inline-block">{{translate('Paystack')}}</span>
                                    </span>
                                </label>
                            </div>
                        @endif
                        @if (get_setting('flutterwave_payment') == 1)
                            <div class="col-6 col-md-4">
                                <label class="aiz-megabox d-block">
                                    <input type="radio" name="payment_option" value="flutterwave" required>
                                    <span class="d-block p-3 aiz-megabox-elem text-center">
                                        <img src="{{ static_asset('assets/img/cards/flutterwave.png') }}" class="img-fluid w-100">
                                        <span class="fw-700 fs-13 mt-2 d-inline-block">{{translate('Flutterwave')}}</span>
                                    </span>
                                </label>
                            </div>
                        @endif
                        @if (get_setting('razorpay_payment') == 1)
                            <div class="col-6 col-md-4">
                                <label class="aiz-megabox d-block">
                                    <input type="radio" name="payment_option" value="razorpay" required>
                                    <span class="d-block p-3 aiz-megabox-elem text-center">
                                        <img src="{{ static_asset('assets/img/cards/razorpay.png') }}" class="img-fluid w-100">
                                        <span class="fw-700 fs-13 mt-2 d-inline-block">{{translate('Razorpay')}}</span>
                                    </span>
                                </label>
                            </div>
                        @endif
                        @if (get_setting('paytm_payment') == 1)
                            <div class="col-6 col-md-4">
                                <label class="aiz-megabox d-block">
                                    <input type="radio" name="payment_option" value="paytm" required>
                                    <span class="d-block p-3 aiz-megabox-elem text-center">
                                        <img src="{{ static_asset('assets/img/cards/paytm.png') }}" class="img-fluid w-100">
                                        <span class="fw-700 fs-13 mt-2 d-inline-block">{{translate('Paytm')}}</span>
                                    </span>
                                </label>
                            </div>
                        @endif
                    </div>
                    <div class="form-group text-right mt-4">
                        <button type="button" class="btn btn-secondary transition-3d-hover mr-1" data-dismiss="modal">{{translate('cancel')}}</button>
                        <button type="submit" class="btn btn-primary transition-3d-hover mr-1">{{translate('Confirm')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection


@section('script')
    <script type="text/javascript">

        function select_payment_method_modal(id){
            $('input[name=seller_package_id]').val(id);
            $('#select_payment_method_modal').modal('show');
        }

        function get_free_package(id){
            $('input[name=seller_package_id]').val(id);
            $('#package_payment_form').submit();
        }

    </script>
@endsection
