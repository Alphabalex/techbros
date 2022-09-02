@extends('addon:multivendor::seller.layouts.app')

@section('content')

<div class="aiz-titlebar mt-2 mb-4">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Money Withdraw Settings') }}</h1>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-8 mx-auto">
        <form action="{{ route('seller.payout_settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Payment Setting')}}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <label class="col-md-3 col-form-label">{{ translate('Cash Payment') }}</label>
                        <div class="col-md-9">
                            <label class="aiz-switch aiz-switch-success mb-3">
                                <input value="1" name="cash_payout_status" type="checkbox" @if ($shop->cash_payout_status == 1) checked @endif>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3 col-form-label">{{ translate('Bank Payment') }}</label>
                        <div class="col-md-9">
                            <label class="aiz-switch aiz-switch-success mb-3">
                                <input value="1" name="bank_payout_status" type="checkbox" @if ($shop->bank_payout_status == 1) checked @endif>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3 col-form-label">{{ translate('Bank Name') }}</label>
                        <div class="col-md-9">
                            <input type="text" name="bank_name" value="{{ $shop->bank_name }}" class="form-control mb-3" placeholder="{{ translate('Bank Name')}}">
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3 col-form-label">{{ translate('Bank Account Name') }}</label>
                        <div class="col-md-9">
                            <input type="text" name="bank_acc_name" value="{{ $shop->bank_acc_name }}" class="form-control mb-3" placeholder="{{ translate('Bank Account Name')}}">
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3 col-form-label">{{ translate('Bank Account Number') }}</label>
                        <div class="col-md-9">
                            <input type="text" name="bank_acc_no" value="{{ $shop->bank_acc_no }}" class="form-control mb-3" placeholder="{{ translate('Bank Account Number')}}">
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3 col-form-label">{{ translate('Bank Routing Number') }}</label>
                        <div class="col-md-9">
                            <input type="number" name="bank_routing_no" value="{{ $shop->bank_routing_no }}" class="form-control mb-3" placeholder="{{ translate('Bank Routing Number')}}">
                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-sm btn-primary">{{translate('Update Withdraw Settings')}}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
  
@endsection