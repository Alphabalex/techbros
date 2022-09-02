@extends('addon:multivendor::seller.layouts.app')

@section('content')

    <div class="card">
        <form class="" id="sort_paments" action="" method="GET">
            <div class="card-header row gutters-5">
                <div class="col text-center text-md-left">
                    <h5 class="mb-md-0 h6">{{ translate('All payouts') }}</h5>
                </div>
                <div class="col-md-2">
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm aiz-date-range" id="search" name="date_range" @isset($date_range) value="{{ $date_range }}" @endisset placeholder="{{ translate('Daterange') }}" autocomplete="off">
                    </div>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-primary">{{ translate('Filter') }}</button>
                </div>
            </div>
        </form>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ translate('Amount')}}</th>
                        <th>{{ translate('Payment Method')}}</th>
                        <th>{{ translate('Date')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payouts as $key => $payout)
                        <tr>
                            <td>
                                {{ $key+1 }}
                            </td>
                            <td>
                                {{ format_price($payout->paid_amount) }}
                            </td>
                            <td>
                                {{ ucfirst(str_replace('_', ' ', $payout->payment_method)) }} @if ($payout->txn_code != null) ({{  translate('TRX ID') }} : {{ $payout->txn_code }}) @endif
                            </td>
                            <td>{{ date('d-m-Y', strtotime($payout->created_at)) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $payouts->links() }}
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        function sort_paments(el) {
            $('#sort_paments').submit();
        }
    </script>
@endsection
