@extends('addon:multivendor::seller.layouts.app')

@section('content')

<div class="col-lg-8 mx-auto">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{translate('Profile Info')}}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('seller.profile.update') }}" method="POST">
                @csrf
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="name">{{translate('Name')}}</label>
                    <div class="col-sm-9">
                        <input type="text" name="name" value="{{ Auth::user()->name }}" class="form-control" id="name" placeholder="{{translate('Name')}}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="email">{{translate('Email')}}</label>
                    <div class="col-sm-9">
                        <input type="text" value="{{ Auth::user()->email }}" class="form-control" id="email" placeholder="{{translate('Email')}}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="phone">{{translate('Phone')}}</label>
                    <div class="col-sm-9">
                        <input type="text" name="phone" value="{{ Auth::user()->phone }}" class="form-control" id="phone" placeholder="{{translate('Phone')}}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="new_password">{{translate('New Password')}}</label>
                    <div class="col-sm-9">
                        <input type="password" name="new_password"  class="form-control" placeholder="{{translate('New Password')}}" id="new_password">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="confirm_password">{{translate('Confirm Password')}}</label>
                    <div class="col-sm-9">
                        <input type="password" name="confirm_password" class="form-control" placeholder="{{translate('Confirm Password')}}" id="confirm_password">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('Photo')}} <small>(100x100)</small></label>
                    <div class="col-md-9">
                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                            </div>
                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                            <input type="hidden" name="avatar" class="selected-files" value="{{ Auth::user()->avatar }}">
                        </div>
                        <div class="file-preview box sm">
                        </div>
                    </div>
                </div>
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
