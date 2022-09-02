@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="align-items-center">
        <h1 class="h3">{{translate('All Package Payments')}}</h1>
	</div>
</div>


<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{translate('Package Payments')}}</h5>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ translate('Seller') }}</th>
                    <th>{{translate('Package')}}</th>
                    <th data-breakpoints="lg">{{translate('Amount')}}</th>
                    <th data-breakpoints="lg">{{translate('paymant Method')}}</th>
                    <th data-breakpoints="lg">{{translate('Date')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($package_payments as $key => $package_payment)
                    <tr>
                        <td>{{ ($key+1) + ($package_payments->currentPage() - 1)*$package_payments->perPage() }}</td>
                        <td>
                            {{ $package_payment->user->name ?? translate('Deleted user') }}
                            ({{ $package_payment->user->shop->name ?? translate('Deleted shop') }})
                        </td>
                        <td>{{ $package_payment->seller_package->name ?? translate('Deleted package') }}</td>
                        <td>{{ format_price($package_payment->amount) }}</td>
                        <td>{{ $package_payment->payment_method }}</td>
                        <td>{{ $package_payment->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $package_payments->appends(request()->input())->links() }}
        </div>
    </div>
</div>

@endsection