@extends('addon:multivendor::seller.layouts.app')

@section('content')

    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Payout requests') }}</h1>
            </div>
        </div>
    </div>

    <div class="row gutters-10">
        <div class="col-md-4 mb-3 ml-auto" >
            <div class="bg-grad-1 text-white rounded-lg overflow-hidden">
                <span class="size-30px rounded-circle mx-auto bg-soft-primary d-flex align-items-center justify-content-center mt-3">
                    <i class="las la-dollar-sign la-2x text-white"></i>
                </span>
                <div class="px-3 pt-3 pb-3">
                    <div class="h4 fw-700 text-center">{{ format_price(Auth::user()->shop->current_balance) }}</div>
                    <div class="opacity-50 text-center">{{ translate('Pending Balance') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3 mr-auto" >
            <div class="p-3 rounded mb-3 c-pointer text-center bg-white shadow-sm hov-shadow-lg border border-gray-200 has-transition" onclick="withdraw_request_modal()">
                <span class="size-60px rounded-circle mx-auto bg-secondary d-flex align-items-center justify-content-center mb-3">
                    <i class="las la-plus la-3x text-white"></i>
                </span>
                <div class="fs-18 text-primary">{{ translate('Send payout request') }}</div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Payout requests history')}}</h5>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ translate('Date') }}</th>
                        <th>{{ translate('Amount')}}</th>
                        <th data-breakpoints="lg">{{ translate('Status')}}</th>
                        <th data-breakpoints="lg">{{ translate('Message')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payout_requests as $key => $payout_request)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ date('d-m-Y', strtotime($payout_request->created_at)) }}</td>
                            <td>{{ format_price($payout_request->requested_amount) }}</td>
                            <td>
                                @if ($payout_request->status == 'paid')
                                    <span class=" badge badge-inline badge-success" >{{ translate('Paid')}}</span>
                                @else
                                    <span class=" badge badge-inline badge-info" >{{ translate('Pending')}}</span>
                                @endif
                            </td>
                            <td>
                                {{ $payout_request->seller_note }}
                            </td>
                        </tr>
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
    <div class="modal fade" id="withdraw_request_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ translate('Send A Withdraw Request') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                @if (Auth::user()->shop->current_balance > 5)
                    <form class="" action="{{ route('seller.payouts.request.store') }}" method="post">
                        @csrf
                        <div class="modal-body gry-bg px-3 pt-3">
                            <div class="row">
                                <div class="col-md-3">
                                    <label>{{ translate('Amount')}} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <input type="number" lang="en" class="form-control mb-3" name="amount" min="1" max="{{ auth()->user()->shop->current_balance }}" placeholder="{{ translate('Amount') }}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label>{{ translate('Message')}}</label>
                                </div>
                                <div class="col-md-9">
                                    <textarea name="message" rows="8" class="form-control mb-3"></textarea>
                                </div>
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-sm btn-primary">{{translate('Send')}}</button>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="modal-body gry-bg px-3 pt-3">
                        <div class="p-5 heading-3">
                            {{ translate('You do not have enough balance to send withdraw request') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        function withdraw_request_modal(){
            $('#withdraw_request_modal').modal('show');
        }
    </script>
@endsection
