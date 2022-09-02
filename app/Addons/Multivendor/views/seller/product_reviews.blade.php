@extends('addon:multivendor::seller.layouts.app')

@section('content')

    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="align-items-center">
            <h1 class="h3">{{ translate('Product Reviews') }}</h1>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-block d-md-flex">
            <h5 class="mb-0 h6">{{ translate('Product Reviews') }}</h5>
            <div class="ml-auto mr-0">
                <form class="" id="sort_by_rating" action="{{ route('reviews.index') }}" method="GET">
                    <div class="box-inline pad-rgt pull-left">
                        <div class="select" style="min-width: 300px;">
                            <select class="form-control aiz-selectpicker" name="rating" id="rating"
                                onchange="filter_by_rating()">
                                <option value="">{{ translate('Filter by Rating') }}</option>
                                <option value="rating,desc">{{ translate('Rating (High > Low)') }}</option>
                                <option value="rating,asc">{{ translate('Rating (Low > High)') }}</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th class="col-lg-3" data-breakpoints="lg">{{ translate('Product & rating') }}</th>
                        <th>{{ translate('Customer') }}</th>
                        <th class="col-lg-5" data-breakpoints="lg">{{ translate('Comment') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reviews as $key => $review_id)
                        @php $review = \App\Models\Review::where('id',$review_id->id)->first(); @endphp
                        @if ($review->product != null && $review->user != null)
                            <tr>
                                <td>{{ $key + 1 + ($reviews->currentPage() - 1) * $reviews->perPage() }}</td>
                                <td>
                                    <span class="d-flex align-items-center">
                                        <img src="{{ uploaded_asset($review->product->thumbnail_img) }}"
                                            class="size-80px mr-2"
                                            onerror="this.onerror=null;this.src='{{ static_asset('/assets/img/placeholder.jpg') }}';">
                                        <span
                                            class="flex-grow-1 minw-0 text-truncate-2">{{ $review->product->name }}</span>
                                    </span>

                                </td>
                                <td class="lh-1-8">
                                    <span
                                        class="d-block">{{ translate('Name') . ': ' . $review->user->name }}</span>
                                    <span
                                        class="d-block">{{ translate('Email') . ': ' . $review->user->email }}</span>
                                    <span
                                        class="d-block">{{ translate('Phone') . ': ' . $review->user->phone }}</span>
                                    <span class="d-block">
                                        {{ translate('Rating') }}: <span
                                            class="rating">{{ renderStarRating($review->rating) }}</span>
                                    </span>
                                </td>
                                <td>{{ $review->comment }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $reviews->appends(request()->input())->links() }}
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">

        function filter_by_rating(el) {
            var rating = $('#rating').val();
            if (rating != '') {
                $('#sort_by_rating').submit();
            }
        }
    </script>
@endsection
