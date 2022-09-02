@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{translate('All Sellers')}}</h1>
        </div>
    </div>
</div>

<div class="card">
    <form class="" id="sort_sellers" action="" method="GET">
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-md-0 h6">{{ translate('Sellers') }}</h5>
            </div>
            
            <div class="col-md-3 ml-auto">
                <select class="form-control aiz-selectpicker" name="approved_status" id="approved_status" onchange="sort_sellers()" data-selected="{{ $approved }}">
                    <option value="">{{translate('Filter by Approval')}}</option>
                    <option value="1">{{translate('Approved')}}</option>
                    <option value="0">{{translate('Non-Approved')}}</option>
                </select>
            </div>
            <div class="col-md-3">
                <div class="form-group mb-0">
                  <input type="text" class="form-control" id="search" name="search" @isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type name or phone & Enter') }}">
                </div>
            </div>
        </div>
    
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th data-breakpoints="lg">#</th>
                        <th data-breakpoints="lg">{{translate('Seller info')}}</th>
                        <th>{{translate('Shop info')}}</th>
                        <th data-breakpoints="lg">{{translate('Current package')}}</th>
                        <th data-breakpoints="lg">{{translate('Current balance')}}</th>
                        <th data-breakpoints="lg">{{translate('Seller Approval')}}</th>
                        <th data-breakpoints="lg">{{translate('Shop Published')}}</th>
                        <th width="10%">{{translate('Options')}}</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($shops as $key => $shop)
                    <tr>
                        <td>{{ ($key+1) + ($shops->currentPage() - 1)*$shops->perPage() }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ uploaded_asset($shop->user->avatar ?? null) }}"class="size-50px rounded-circle mr-2" onerror="this.onerror=null;this.src='{{ static_asset('/assets/img/placeholder.jpg') }}';" />
                                <span class="flex-grow-1 minw-0">
                                    <div class="text-truncate fs-12 fw-600">{{ $shop->user->name ?? translate('Deleted User') }}</div>
                                    <div class="text-truncate fs-12">{{ translate('Phone').': '. $shop->user->phone ?? null }}</div>
                                    <div class="text-truncate fs-12">{{ translate('Email').': '. $shop->user->email ?? null }}</div>
                                </span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ uploaded_asset($shop->logo) }}"class="size-50px rounded-circle mr-2" onerror="this.onerror=null;this.src='{{ static_asset('/assets/img/placeholder.jpg') }}';" />
                                <span class="flex-grow-1 minw-0">
                                    <div class="text-truncate fs-12 fw-600">{{ $shop->name }}</div>
                                    <div class="text-truncate fs-12">{{ translate('Phone').': '. $shop->phone }}</div>
                                    <div class="text-truncate fs-12">{{ translate('Total products').': '. $shop->products_count }}</div>
                                </span>
                            </div>
                        </td>
                        <td>
                            @if(seller_package_validity_check($shop->seller_package, $shop->package_invalid_at) == 'active')
                                <div>{{ translate('Package').': '. $shop->seller_package->name }}</div>
                                <div>{{ translate('Valid till').': '. $shop->package_invalid_at }}</div>
                            @else
                                <span class="badge badge-inline badge-danger">{{ translate('No active Package') }}</span>
                            @endif
                        </td>
                        <td>
                            @if($shop->current_balance == 0)
                                <div>{{ translate('Due to seller') }}:</div>
                                <span class="fs-16 fw-700 text-secondary">{{ format_price($shop->current_balance) }}</span>
                            @elseif($shop->current_balance >= 0)
                                <div>{{ translate('Due to seller') }}:</div>
                                <span class="fs-16 fw-700 text-danger">{{ format_price($shop->current_balance) }}</span>
                            @else
                                <div>{{ translate('Due from seller') }}:</div>
                                <span class="fs-16 fw-700 text-success">{{ format_price(abs($shop->current_balance)) }}</span>
                            @endif
                        </td>
                        <td>
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input onchange="seller_approval(this)" value="{{ $shop->id }}" type="checkbox" @if($shop->approval == 1) checked @endif >
                                <span class="slider round"></span>
                            </label>
                        </td>
                        <td>
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input onchange="shop_publish(this)" value="{{ $shop->id }}" type="checkbox" @if($shop->published == 1) checked @endif >
                                <span class="slider round"></span>
                            </label>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn btn-sm btn-circle btn-soft-primary btn-icon dropdown-toggle no-arrow" data-toggle="dropdown" href="javascript:void(0);" role="button" aria-haspopup="false" aria-expanded="false">
                                    <i class="las la-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                                    <a href="#" onclick="show_seller_profile('{{$shop->user->id}}');"  class="dropdown-item">
                                        {{translate('Profile')}}
                                    </a>
                                    <a href="#" onclick="show_seller_payment_modal('{{$shop->user->id}}');" class="dropdown-item">
                                        {{translate('Pay to Seller')}}
                                    </a>
                                    <a href="{{route('admin.seller_payments_history','shop_id='.$shop->id)}}" class="dropdown-item">
                                        {{translate('Payment History')}}
                                    </a>
                                    <a href="{{route('admin.seller.edit', encrypt($shop->user->id))}}" class="dropdown-item">
                                        {{translate('Edit')}}
                                    </a>
                                    <a href="#" class="dropdown-item confirm-delete" data-href="{{route('admin.seller.destroy', $shop->user->id)}}" class="">
                                        {{translate('Delete')}}
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
              {{ $shops->appends(request()->input())->links() }}
            </div>
        </div>
    </form>
</div>

@endsection

@section('modal')
	<!-- Delete Modal -->
	@include('backend.inc.delete_modal')

	<!-- Seller Profile Modal -->
	<div class="modal fade" id="profile_modal">
		<div class="modal-dialog">
			<div class="modal-content" id="profile-modal-content">

			</div>
		</div>
	</div>

    <!-- Seller Payment Modal -->
	<div class="modal fade" id="payment_modal">
	    <div class="modal-dialog">
	        <div class="modal-content" id="payment-modal-content">

	        </div>
	    </div>
	</div>
@endsection

@section('script')
    <script type="text/javascript">
        function seller_approval(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('admin.sellers.approval') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Seller approval status successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
        function shop_publish(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('admin.shop.publish') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Shop publish status successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

        function show_seller_profile(id){
            $.post('{{ route('admin.sellers.profile_modal') }}',{_token:'{{ @csrf_token() }}', id:id}, function(data){
                $('#profile-modal-content').html(data);
                $('#profile_modal').modal('show', {backdrop: 'static'});
            });
        }

        function show_seller_payment_modal(id){
            $.post('{{ route('admin.sellers.payment_modal') }}',{_token:'{{ @csrf_token() }}', id:id}, function(data){
                $('#payment-modal-content').html(data);
                $('#payment_modal').modal('show', {backdrop: 'static'});
            });
        }

        function sort_sellers(el){
            $('#sort_sellers').submit();
        }
    </script>
@endsection
