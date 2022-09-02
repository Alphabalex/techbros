@php $shop = $seller->shop; @endphp
<div class="modal-body">

    <div class="text-center">
        <span class="avatar avatar-xxl mb-3">
            <img src="{{ uploaded_asset($seller->avatar) }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
        </span>
        <h1 class="h5 mb-1">{{ $seller->name }}</h1>
        <p class="text-sm text-muted">{{ $shop->name }}</p>
    </div>
    <hr>

    <!-- Profile Details -->
    <h6 class="mb-4">{{translate('About')}} {{ $seller->name }}</h6>
    <p><i class="demo-pli-old-telephone icon-lg icon-fw mr-1"></i>{{ $seller->email }}</p>
    <p><i class="demo-pli-old-telephone icon-lg icon-fw mr-1"></i>{{ $seller->phone }}</p>
    
    <h6 class="mb-4 mt-4">{{translate('Shop Info')}}</h6>
    <p><i class="demo-pli-internet icon-lg icon-fw mr-1"></i>{{ $shop->name }}</p>
    <p><i class="demo-pli-map-marker-2 icon-lg icon-fw mr-1"></i>{{ $shop->phone }}</p>
    <p><i class="demo-pli-map-marker-2 icon-lg icon-fw mr-1"></i>{{ $shop->address }}</p>

    <h6 class="mb-4 mt-4">{{translate('Payout Info')}}</h6>
    <p>{{translate('Bank Name')}} : {{ $shop->bank_name }}</p>
    <p>{{translate('Bank Acc Name')}} : {{ $shop->bank_acc_name }}</p>
    <p>{{translate('Bank Acc Number')}} : {{ $shop->bank_acc_no }}</p>
    <p>{{translate('Bank Routing Number')}} : {{ $shop->bank_routing_no }}</p>

    <br>

    <div class="table-responsive">
        <table class="table table-striped mar-no">
            <tbody>
                <tr>
                    <td>{{ translate('Total Products') }}</td>
                    <td>{{ App\Models\Product::where('shop_id', $shop->id)->get()->count() }}</td>
                </tr>
                <tr>
                    <td>{{ translate('Total Orders') }}</td>
                    <td>{{ App\Models\Order::where('shop_id', $shop->id)->get()->count() }}</td>
                </tr>
                <tr>
                    <td>{{ translate('Total Sold Amount') }}</td>
                    @php
                        $total_sold_amount = \App\Models\Order::where('shop_id', $shop->id)->where('payment_status','paid')->sum('grand_total');
                    @endphp
                    <td>{{ format_price($total_sold_amount) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
