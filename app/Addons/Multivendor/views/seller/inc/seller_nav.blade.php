@php
    $logo = get_setting('header_logo');
    $shop = auth()->user()->shop;
@endphp

<div class="aiz-topbar border-bottom px-15px px-lg-25px d-flex align-items-stretch justify-content-between">
    <div class=" d-flex">
        <div class="aiz-topbar-logo-wrap w-xl-240px d-flex align-items-center justify-content-start">
            <a href="{{ route('seller.dashboard') }}" class="d-block">
                @if ($logo != null)
                    <img src="{{ uploaded_asset($logo) }}" class="brand-icon"
                        alt="{{ get_setting('site_name') }}">
                @else
                    <img src="{{ static_asset('assets/img/logo.png') }}" class="brand-icon"
                        alt="{{ get_setting('site_name') }}">
                @endif
            </a>
        </div>
        <div class="aiz-topbar-nav-toggler d-flex align-items-center justify-content-start mx-2 mx-md-3"
            data-toggle="aiz-mobile-nav">
            <button
                class="btn btn-icon btn-outline-secondary border-gray-300 p-0 d-flex align-items-center justify-content-center">
                <span class="aiz-mobile-toggler d-inline-block">
                    <span></span>
                </span>
            </button>
        </div>
    </div>
    <div class="d-flex justify-content-between align-items-stretch flex-grow-xl-1">
        <div class="d-none d-md-flex justify-content-around align-items-center align-items-stretch">
            <div class="aiz-topbar-item align-items-center">
                <a class="btn btn-outline-secondary border-gray-300 d-flex align-items-center px-3"
                    href="{{ route('home') }}" target="_blank">
                    <i class="las la-globe opacity-60"></i>
                    <span class="fw-500 fs-13 ml-2 mr-0 opacity-60">{{ translate('Browse Website') }}</span>
                </a>
            </div>
            @if(seller_package_validity_check($shop->seller_package, $shop->package_invalid_at) == 'active' && $shop->products->count() < $shop->product_upload_limit)
                <div class="aiz-topbar-item align-items-center ml-3">
                    <a class="btn btn-outline-secondary border-gray-300 d-flex align-items-center px-3"
                        href="{{ route('seller.products.create') }}" target="_blank">
                        <i class="las la-plus ts-08 opacity-60"></i>
                        <span class="fw-500 fs-13 ml-2 mr-0 opacity-60">{{ translate('Add new product') }}</span>
                    </a>
                </div>
            @endif
        </div>
        <div class="d-flex justify-content-around align-items-center align-items-stretch">

            <!-- language -->
            @php
                if (Session::has('locale')) {
                    $locale = Session::get('locale', Config::get('app.locale'));
                } else {
                    $locale = env('DEFAULT_LANGUAGE');
                }
                $language = \App\Models\Language::where('code', $locale)->first();
            @endphp
            <div class="aiz-topbar-item ml-3 mr-0">
                <div class="align-items-center d-flex dropdown" id="lang-change">
                    <a class="dropdown-toggle no-arrow" data-toggle="dropdown" href="javascript:void(0);" role="button"
                        aria-haspopup="false" aria-expanded="false">
                        <span class="btn btn btn-outline-secondary border-gray-300 px-3 px-md-4">
                            <img src="{{ static_asset('assets/img/flags/' . $language->flag . '.png') }}" height="11">
                            <span
                                class="fw-500 fs-13 ml-2 mr-0 opacity-60  d-none d-md-inline-block">{{ $language->name }}</span>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right dropdown-menu-xs">

                        @foreach (\App\Models\Language::all() as $key => $language)
                            <li>
                                <a href="javascript:void(0)" data-flag="{{ $language->code }}"
                                    class="dropdown-item @if ($locale == $language->code) active @endif">
                                    <img src="{{ static_asset('assets/img/flags/' . $language->flag . '.png') }}"
                                        class="mr-2">
                                    <span class="language">{{ $language->name }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="aiz-topbar-item ml-3 mr-0">
                <div class="align-items-center d-flex dropdown">
                    <a class="dropdown-toggle no-arrow text-dark" data-toggle="dropdown" href="javascript:void(0);"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            <span class="d-none d-md-block">
                                <span class="d-block fw-500">{{ Auth::user()->name }}</span>
                                <span class="d-block small opacity-60">{{ Auth::user()->user_type }}</span>
                            </span>
                            <span class="avatar avatar-sm ml-md-2 mr-0">
                                <img src="{{ uploaded_asset(Auth::user()->avatar) }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                            </span>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-md">
                        <a href="{{ route('seller.profile') }}" class="dropdown-item">
                            <i class="las la-user-circle"></i>
                            <span>{{ translate('Profile') }}</span>
                        </a>

                        <a href="{{ route('logout') }}" class="dropdown-item">
                            <i class="las la-sign-out-alt"></i>
                            <span>{{ translate('Logout') }}</span>
                        </a>
                    </div>
                </div>
            </div><!-- .aiz-topbar-item -->
        </div>
    </div>
</div><!-- .aiz-topbar -->
