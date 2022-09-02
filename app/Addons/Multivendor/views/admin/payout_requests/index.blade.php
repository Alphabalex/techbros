@extends('backend.layouts.app')
@section('content')

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{translate('Seller Withdraw Request')}}</h5>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th data-breakpoints="lg">#</th>
                        <th data-breakpoints="lg">{{translate('Date')}}</th>
                        <th>{{translate('Seller')}}</th>
                        <th data-breakpoints="lg">{{translate('Total Amount to Pay')}}</th>
                        <th>{{translate('Requested Amount')}}</th>
                        <th data-breakpoints="lg" width="30%">{{ translate('Message') }}</th>
                        <th data-breakpoints="lg">{{ translate('Status') }}</th>
                        <th data-breakpoints="lg" width="15%" class="text-right">{{translate('Options')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payout_requests as $key => $payout_request)
                        @php $shop = $payout_request->shop; @endphp
                        @if ($shop != null && $shop->user != null)
                            <tr>
                                <td>{{ ($key+1) + ($payout_requests->currentPage() - 1)*$payout_requests->perPage() }}</td>
                                <td>{{ $payout_request->created_at }}</td>
                                <td>{{ $shop->user->name ?? translate('Deleted User') }} ({{ $shop->name }}) </td>
                                <td>{{ format_price($shop->current_balance) }}</td>
                                <td>{{ format_price($payout_request->requested_amount) }}</td>
                                <td>
                                    {{ $payout_request->seller_note }}
                                </td>
                                <td>
                                    @if ($payout_request->status == 'paid')
                                    <span class="badge badge-inline badge-success">{{translate('Paid')}}</span>
                                    @else
                                    <span class="badge badge-inline badge-info">{{translate('Pending')}}</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <a  href="javascript:void(0);" 
                                        class="btn btn-soft-info btn-icon btn-circle btn-sm"  
                                        onclick="show_seller_payment_modal('{{ $payout_request->id }}');" 
                                        title="{{ translate('Pay Now') }}">
                                        <i class="las la-money-bill"></i>
                                    </a>

                                    <a href="{{route('admin.seller_payments_history','shop_id='.$shop->id)}}" 
                                        class="btn btn-soft-primary btn-icon btn-circle btn-sm"  
                                        title="{{ translate('Payment History') }}">
                                        <i class="las la-history"></i>
                                    </a>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $payout_requests->links() }}
            </div>
        </div>
    </div>

@endsection

@section('modal')
<!-- payment Modal -->
<div class="modal fade" id="payment_modal">
  <div class="modal-dialog">
    <div class="modal-content" id="payment-modal-content">

    </div>
  </div>
</div>


@endsection



@section('script')
<script type="text/javascript">

    function show_seller_payment_modal(id){
        $.post('{{ route('admin.payout_request.payment_modal') }}',{_token:'{{ @csrf_token() }}', id:id }, function(data){
            $('#payment-modal-content').html(data);
            $('#payment_modal').modal('show', {backdrop: 'static'});
        });
    }
   
</script>

@endsection
