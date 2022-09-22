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
            <form class="" id="package_payment_form" action="{{ route('seller.packages.purchase') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="seller_package_id" value="">
                <input type="hidden" name="payment_type" value="seller_package_payment">
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

                        @if (get_setting('payfast_payment') == 1)
                            <div class="col-6 col-md-4">
                                <label class="aiz-megabox d-block">
                                    <input type="radio" name="payment_option" value="payfast" required>
                                    <span class="d-block p-3 aiz-megabox-elem text-center">
                                        <img src="{{ static_asset('assets/img/cards/payfast.png') }}" class="img-fluid w-100">
                                        <span class="fw-700 fs-13 mt-2 d-inline-block">{{translate('Payfast')}}</span>
                                    </span>
                                </label>
                            </div>
                        @endif
                        
                        @if (get_setting('authorizenet_payment') == 1)
                            <div class="col-6 col-md-4">
                                <label class="aiz-megabox d-block">
                                    <input type="radio" name="payment_option" value="authorizenet" required>
                                    <span class="d-block p-3 aiz-megabox-elem text-center">
                                        <img src="{{ static_asset('assets/img/cards/authorizenet.png') }}" class="img-fluid w-100">
                                        <span class="fw-700 fs-13 mt-2 d-inline-block">{{translate('Authorize Net')}}</span>
                                    </span>
                                </label>
                            </div>
                        @endif
                        
                        @if (get_setting('mercadopago_payment') == 1)
                            <div class="col-6 col-md-4">
                                <label class="aiz-megabox d-block">
                                    <input type="radio" name="payment_option" value="mercadopago" required>
                                    <span class="d-block p-3 aiz-megabox-elem text-center">
                                        <img src="{{ static_asset('assets/img/cards/mercadopago.png') }}" class="img-fluid w-100">
                                        <span class="fw-700 fs-13 mt-2 d-inline-block">{{translate('Mercadopago')}}</span>
                                    </span>
                                </label>
                            </div>
                        @endif


                         @if (get_setting('offline_payment') == 1)
                            @foreach (\App\Models\ManualPaymentMethod::all() as $manualPayment)
                                <div class="col-6 col-md-4">
                                    <label class="aiz-megabox d-block">
                                        <input type="radio" name="payment_option" value="offline_payment-{{ $manualPayment->id }}" onchange="toggleManualPaymentData({{ $manualPayment->id }})"  required>
                                        <span class="d-block p-3 aiz-megabox-elem text-center">
                                            <img src="{{ uploaded_asset($manualPayment->photo) }}" class="img-fluid w-100">
                                            <span class="fw-700 fs-13 mt-2 d-inline-block">{{ $manualPayment->heading }}</span>
                                        </span>
                                    </label>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    
                    {{-- Inputs for authorize net payment --}}
                    <div class="mt-4 card card-body d-none authorizenet_input">
                    
                    </div>
                    {{-- Inputs for authorize net payment --}}


                    {{-- form for offline payment --}}
                    <div class="offline_payment_form mt-4 card card-body d-none">

                        <div class="row">
                            @foreach (\App\Models\ManualPaymentMethod::all() as $method)
                                <div id="manual_payment_info_{{ $method->id }}"
                                    class="d-none">

                                    <div class="px-2">@php echo $method->description @endphp</div>

                                    @if ($method->bank_info != null)
                                        <ul class="px-4">
                                            @foreach (json_decode($method->bank_info) as $key => $info)
                                                <li>{{ translate('Bank Name') }} -
                                                    {{ $info->bank_name }},
                                                    {{ translate('Account Name') }} -
                                                    {{ $info->account_name }},
                                                    {{ translate('Account Number') }} -
                                                    {{ $info->account_number }},
                                                    {{ translate('Routing Number') }} -
                                                    {{ $info->routing_number }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="offline_input">

                        </div>
                        
                    </div> 
                    {{-- form for offline payment --}}

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

        var offline_input = '<div class="row">'+
                              '<label class="col-md-4 col-form-label">{{ translate('Transaction ID') }} <span class="text-danger text-danger">*</span></label>'+
                              '<div class="col-md-8">'+
                              '<input type="text" name="transactionId" class="form-control mb-3" placeholder="{{ translate('Transaction ID') }}" required>'+
                              '</div>'+
                              '</div>'+ 
                              '<div class="row">'+
                              '<label class="col-md-4 col-form-label">{{ translate('Receipt') }}</label>'+
                              '<div class="col-md-8">'+
                              '<input type="file" name="receipt" class="form-control-file mb-3" placeholder="{{ translate('Receipt') }}">'+
                              '</div>'+
                              '</div>'

        // Authorize Net Inputs
        const months = ["Jan", "Feb", "Mar", "Apr", "May",  "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        let monthOptions = '';
        months.forEach((month, index) => {
            monthOptions = monthOptions + '<option value="'+ (index+1) +'">'+ month +'</option>'
        })

        let yearOptions = '';
        let presentYear =  new Date().getFullYear();
        let fifteenPlus = new Date().getFullYear() + 15;
        for (let i = presentYear; i < fifteenPlus; i++) {
            yearOptions = yearOptions + '<option value="'+ i +'">'+ i +'</option>'
        }
                             
        var authorizenet_input = '<div class="row">'+
                            '<div class="form-group col-md-8" id="card-number-field">'+
                                '<label for="cardNumber">{{ translate('Card Number') }}</label>'+
                                '<input type="text" class="form-control" id="cardNumber" name="card_number" required>'+
                                '<span id="card-error" class="error text-red">{{ translate('Please enter valid card number') }}</span>'+
                            '</div>'+
                            '<div class="form-group CVV col-md-4">'+
                                '<label for="cvv">{{ translate('CVV') }}</label>'+
                                '<input type="number" class="form-control" id="cvv" name="cvv" required>'+
                                '<span id="cvv-error" class="error text-red">{{ translate('Please enter cvv') }}</span>'+
                            '</div>'+
                        '</div> '+ 
                        '<div class="row">'+
                            '<div class="form-group col-md-6" id="expiration-date">'+
                                '<label>{{ translate("Expiration Date") }}</label><br/>'+
                                '<select class="form-control" id="expiration-month" name="expiration_month" style="float: left; width: 100px; margin-right: 10px;">'+monthOptions+'</select>'+
                                '<select class="form-control" id="expiration-year" name="expiration_year"  style="float: left; width: 100px;">'+yearOptions+'</select>'+
                            '</div> '+   
                        '</div>';
        
        function select_payment_method_modal(id){
            $('input[name=seller_package_id]').val(id);
            $('#select_payment_method_modal').modal('show');
        }

        function get_free_package(id){
            $('input[name=seller_package_id]').val(id);
            $('#package_payment_form').submit();
        }

        $('input[name=payment_option]').bind('input', function() { 
            var html = '';
            if($(this).val().includes('offline_payment')){
                html+= offline_input;
                $('.offline_input').html(html);
                $('.offline_payment_form').removeClass('d-none');
                $('.authorizenet_input').html('');
                $('.authorizenet_input').addClass('d-none');
            }
            else if($(this).val().includes('authorizenet')){
                html+= authorizenet_input;
                $('.authorizenet_input').html(html);
                $('.authorizenet_input').removeClass('d-none');

                $('.offline_input').html('');
                $('.offline_payment_form').addClass('d-none');
            }
            else{
                $('.offline_input').html(html);
                $('.offline_payment_form').addClass('d-none');
                $('.authorizenet_input').html('');
                $('.authorizenet_input').addClass('d-none');
            }
        });

        function toggleManualPaymentData(id) {
            if (typeof id != 'undefined') {
                $('#manual_payment_info_' + id).removeClass('d-none'); 
                $('#manual_payment_info_' + id).siblings().addClass('d-none'); 
            }
        }
    </script>
@endsection
