@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Update Package Information')}}</h5>
</div>

<div class="col-lg-10 mx-auto">
    <div class="card">
        <div class="card-body p-0">
            <ul class="nav nav-tabs nav-fill border-light">
                @foreach (\App\Models\Language::all() as $key => $language)
                    <li class="nav-item">
                        <a class="nav-link text-reset @if ($language->code == $lang) active @else bg-soft-dark border-light border-left-0 @endif py-3" href="{{ route('admin.seller_packages.edit', ['id'=>$seller_package->id, 'lang'=> $language->code] ) }}">
                            <img src="{{ static_asset('assets/img/flags/'.$language->code.'.png') }}" height="11" class="mr-1">
                            <span>{{ $language->name }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
            <form class="p-4" action="{{ route('admin.seller_packages.update', $seller_package->id) }}" method="POST">
                <input type="hidden" name="_method" value="PATCH">
                <input type="hidden" name="lang" value="{{ $lang }}">
            	@csrf
                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{translate('Package Name')}}</label>
                    <div class="col-sm-10">
                        <input type="text" name="name" value="{{ $seller_package->getTranslation('name', $lang) }}" placeholder="{{translate('Name')}}" id="name" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="amount">{{translate('Amount')}}</label>
                    <div class="col-sm-10">
                        <input type="number" name="amount" value="{{ $seller_package->amount }}" min="0" step="0.01" placeholder="{{translate('Amount')}}" id="amount" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="product_upload_limit">{{translate('Product Upload Limit')}}</label>
                    <div class="col-sm-10">
                        <input type="number" name="product_upload_limit" value="{{ $seller_package->product_upload_limit }}" min="0" step="1" placeholder="{{translate('Product Upload Limit')}}" id="product_upload_limit" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="commission">{{translate('Commission')}}</label>
                    <div class="col-sm-10">
                        <div class="input-group">
                            <input type="number" name="commission" value="{{ $seller_package->commission }}" min="0" step=".1" placeholder="{{translate('Eg. 5')}}" id="commission"  class="form-control" required>
                            <div class="input-group-append">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="duration">{{translate('Duration')}}</label>
                    <div class="col-sm-10">
                        <div class="input-group">
                            <input type="number" name="duration" value="{{ $seller_package->duration }}" min="0" step="1" placeholder="{{translate('Validity in number of days')}}" id="duration" class="form-control" required>
                            <div class="input-group-append">
                                <span class="input-group-text">{{ translate('Days') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="signinSrEmail">{{translate('Package Logo')}}</label>
                    <div class="col-md-10">
                        <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                            </div>
                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                            <input type="hidden" name="logo" value="{{ $seller_package->logo }}" class="selected-files">
                        </div>
                        <div class="file-preview box sm">
                        </div>
                    </div>
                </div>
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
