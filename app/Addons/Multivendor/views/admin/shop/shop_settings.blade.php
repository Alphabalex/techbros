@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar mt-2 mb-4">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Shop Settings')}}</h1>
        </div>
    </div>
</div>

{{-- Basic Info --}}
<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{ translate('Basic Info') }}</h5>
    </div>
    <div class="card-body">
        <form class="" action="{{ route('admin.shop_setting.update', $shop->id) }}" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="_method" value="PATCH">
            @csrf
            <div class="row">
                <label class="col-md-2 col-form-label" for="shop_name">{{ translate('Shop Name') }} <span class="text-danger text-danger">*</span></label>
                <div class="col-md-10">
                    <input type="text" name="name" value="{{ $shop->name }}" class="form-control mb-3" placeholder="{{ translate('Shop Name')}}" id="shop_name" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-md-2 col-form-label">{{ translate('Shop Logo') }}<small>(100x100)</small></label>
                <div class="col-md-10">
                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                        <div class="input-group-prepend">
                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                        </div>
                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                        <input type="hidden" name="logo" value="{{ $shop->logo }}" class="selected-files">
                    </div>
                    <div class="file-preview box sm">
                    </div>
                </div>
            </div>
            <div class="row">
                <label class="col-md-2 col-form-label" for="shop_phone">{{ translate('Shop Phone') }}</label>
                <div class="col-md-10">
                    <input type="text" name="phone" value="{{ $shop->phone }}" class="form-control mb-3" placeholder="{{ translate('Phone')}}" id="shop_phone" required>
                </div>
            </div>
            <div class="row">
                <label class="col-md-2 col-form-label" for="shop_address">{{ translate('Shop Address') }} <span class="text-danger text-danger">*</span></label>
                <div class="col-md-10">
                    <input type="text" name="address" value="{{ $shop->address }}" class="form-control mb-3" placeholder="{{ translate('Address')}}" id="shop_address" required>
                </div>
            </div>
            <div class="row">
                <label class="col-md-2 col-form-label" for="meta_title">{{ translate('Meta Title') }} <span class="text-danger text-danger">*</span></label>
                <div class="col-md-10">
                    <input type="text" name="meta_title" value="{{ $shop->meta_title }}" class="form-control mb-3" placeholder="{{ translate('Meta Title')}}" id="meta_title" required>
                </div>
            </div>
            <div class="row">
                <label class="col-md-2 col-form-label">{{ translate('Meta Description') }} <span class="text-danger text-danger">*</span></label>
                <div class="col-md-10">
                    <textarea name="meta_description" rows="3" class="form-control mb-3" required>{{ $shop->meta_description }}</textarea>
                </div>
            </div>
            <div class="form-group mb-0 text-right">
                <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
            </div>
        </form>
    </div>
</div>

{{-- Banner Settings --}}
<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{ translate('Shop page Settings') }}</h5>
    </div>
    <div class="card-body">
        <form class="" action="{{ route('admin.shop_setting.update', $shop->id) }}" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="_method" value="PATCH">
            @csrf

            <div class="row mb-3">
                <label class="col-md-2 col-form-label">{{ translate('Main Banners') }} (1920x360)</label>
                <div class="col-md-10">
                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                        <div class="input-group-prepend">
                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                        </div>
                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                        <input type="hidden" name="banners" value="{{ $shop->banners }}" class="selected-files">
                    </div>
                    <div class="file-preview box sm">
                    </div>
                    <small class="text-muted">{{ translate('We had to limit height to maintian consistancy. In some device both side of the banner might be cropped for height limitation.') }}</small>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-md-2 col-form-label">{{ translate('Front page Featured Products') }}</label>
                <div class="col-md-10">
                    <select name="featured_products[]" class="aiz-selectpicker form-control" data-title="{{ translate('Select products') }}" data-live-search="true" data-selected-text-format="count" multiple data-selected="{{ $shop->featured_products }}" data-max-options="10">
                        @foreach ($shop->products as $product)
                            <option value="{{ $product->id }}">{{ $product->getTranslation('name') }}</option>                            
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row mb-3 pt-3 border-top">
                <div class="col-md-2 ">
                    <label class="col-form-label">{{ translate('All products page banner') }}</label>
                    <small>{{ translate('Recommended size').' 1025x120' }}</small>
                </div>
                <div class="col-md-10">
                    <div class="banner-1-target">
                        @foreach (json_decode($shop->products_banners ?? '[]') as $key => $banner)
                            <div class="row">
                                <div class="col-lg-5">
                                    <div class="form-group">
                                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                                            <input type="hidden" name="products_banners_images[]" class="selected-files" value="{{ $banner->img }}">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                            </div>
                                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        </div>
                                        <div class="file-preview box sm"></div>
                                    </div>
                                </div>
                                <div class="col-lg">
                                    <input type="text" placeholder="{{ translate('Link') }}" name="products_banners_links[]" value="{{ $banner->link }}" class="form-control">
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
                                        <i class="las la-times"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="">
                        <button
                            type="button"
                            class="btn btn-soft-secondary btn-sm"
                            data-toggle="add-more"
                            data-content='<div class="row gutters-5">
                                <div class="col-lg-5">
                                    <div class="form-group">
                                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                            </div>
                                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                            <input type="hidden" name="products_banners_images[]" class="selected-files">
                                        </div>
                                        <div class="file-preview box sm"></div>
                                    </div>
                                </div>
                                <div class="col-lg">
                                    <input type="text" placeholder="{{ translate('Link') }}" name="products_banners_links[]" class="form-control">
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
                                        <i class="las la-times"></i>
                                    </button>
                                </div>
                            </div>'
                            data-target=".banner-1-target">
                            {{ translate('Add New') }}
                        </button>
                    </div>
                </div>
            </div>
            <div class="row mb-3 pt-3 border-top">
                <div class="col-md-2 ">
                    <label class="col-form-label">{{ translate('Front page Banner section one') }}</label>
                    <small>{{ translate('Recommended size').' 420x200' }}</small>
                </div>
                <div class="col-md-10">
                    <div class="banner-1-target">
                        @foreach (json_decode($shop->banners_1 ?? '[]') as $key => $banner)
                            <div class="row">
                                <div class="col-lg-5">
                                    <div class="form-group">
                                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                                            <input type="hidden" name="banner_section_one_images[]" class="selected-files" value="{{ $banner->img }}">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                            </div>
                                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        </div>
                                        <div class="file-preview box sm"></div>
                                    </div>
                                </div>
                                <div class="col-lg">
                                    <input type="text" placeholder="{{ translate('Link') }}" name="banner_section_one_links[]" value="{{ $banner->link }}" class="form-control">
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
                                        <i class="las la-times"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="">
                        <button
                            type="button"
                            class="btn btn-soft-secondary btn-sm"
                            data-toggle="add-more"
                            data-content='<div class="row gutters-5">
                                <div class="col-lg-5">
                                    <div class="form-group">
                                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                            </div>
                                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                            <input type="hidden" name="banner_section_one_images[]" class="selected-files">
                                        </div>
                                        <div class="file-preview box sm"></div>
                                    </div>
                                </div>
                                <div class="col-lg">
                                    <input type="text" placeholder="{{ translate('Link') }}" name="banner_section_one_links[]" class="form-control">
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
                                        <i class="las la-times"></i>
                                    </button>
                                </div>
                            </div>'
                            data-target=".banner-1-target">
                            {{ translate('Add New') }}
                        </button>
                    </div>
                </div>
            </div>
            <div class="row mb-3 pt-3 border-top">
                <div class="col-md-2 ">
                    <label class="col-form-label">{{ translate('Front page Banner section two') }}</label>
                    <small>{{ translate('Recommended size').' 1300x360' }}</small>
                </div>
                <div class="col-md-10">
                    <div class="banner-2-target">
                        @foreach (json_decode($shop->banners_2 ?? '[]') as $key => $banner)
                            <div class="row">
                                <div class="col-lg-5">
                                    <div class="form-group">
                                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                                            <input type="hidden" name="banner_section_two_images[]" class="selected-files" value="{{ $banner->img }}">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                            </div>
                                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        </div>
                                        <div class="file-preview box sm"></div>
                                    </div>
                                </div>
                                <div class="col-lg">
                                    <input type="text" placeholder="{{ translate('Link') }}" name="banner_section_two_links[]" value="{{ $banner->link }}" class="form-control">
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
                                        <i class="las la-times"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="">
                        <button
                            type="button"
                            class="btn btn-soft-secondary btn-sm"
                            data-toggle="add-more"
                            data-content='<div class="row gutters-5">
                                <div class="col-lg-5">
                                    <div class="form-group">
                                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                            </div>
                                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                            <input type="hidden" name="banner_section_two_images[]" class="selected-files">
                                        </div>
                                        <div class="file-preview box sm"></div>
                                    </div>
                                </div>
                                <div class="col-lg">
                                    <input type="text" placeholder="{{ translate('Link') }}" name="banner_section_two_links[]" class="form-control">
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
                                        <i class="las la-times"></i>
                                    </button>
                                </div>
                            </div>'
                            data-target=".banner-2-target">
                            {{ translate('Add New') }}
                        </button>
                    </div>
                </div>
            </div>
            <div class="row mb-3 pt-3 border-top">
                <div class="col-md-2 ">
                    <label class="col-form-label">{{ translate('Front page Banner section three') }}</label>
                    <small>{{ translate('Recommended size').' 640x290' }}</small>
                </div>
                <div class="col-md-10">
                    <div class="banner-3-target">
                        @foreach (json_decode($shop->banners_3 ?? '[]') as $key => $banner)
                            <div class="row">
                                <div class="col-lg-5">
                                    <div class="form-group">
                                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                                            <input type="hidden" name="banner_section_three_images[]" class="selected-files" value="{{ $banner->img }}">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                            </div>
                                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        </div>
                                        <div class="file-preview box sm"></div>
                                    </div>
                                </div>
                                <div class="col-lg">
                                    <input type="text" placeholder="{{ translate('Link') }}" name="banner_section_three_links[]" value="{{ $banner->link }}" class="form-control">
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
                                        <i class="las la-times"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="">
                        <button
                            type="button"
                            class="btn btn-soft-secondary btn-sm"
                            data-toggle="add-more"
                            data-content='<div class="row gutters-5">
                                <div class="col-lg-5">
                                    <div class="form-group">
                                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                            </div>
                                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                            <input type="hidden" name="banner_section_three_images[]" class="selected-files">
                                        </div>
                                        <div class="file-preview box sm"></div>
                                    </div>
                                </div>
                                <div class="col-lg">
                                    <input type="text" placeholder="{{ translate('Link') }}" name="banner_section_three_links[]" class="form-control">
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
                                        <i class="las la-times"></i>
                                    </button>
                                </div>
                            </div>'
                            data-target=".banner-3-target">
                            {{ translate('Add New') }}
                        </button>
                    </div>
                </div>
            </div>
            <div class="row mb-3 pt-3 border-top">
                <div class="col-md-2 ">
                    <label class="col-form-label">{{ translate('Front page Banner section four') }}</label>
                    <small>{{ translate('Recommended size').' 1300x360' }}</small>
                </div>
                <div class="col-md-10">
                    <div class="banner-4-target">
                        @foreach (json_decode($shop->banners_4 ?? '[]') as $key => $banner)
                            <div class="row">
                                <div class="col-lg-5">
                                    <div class="form-group">
                                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                                            <input type="hidden" name="banner_section_four_images[]" class="selected-files" value="{{ $banner->img }}">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                            </div>
                                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        </div>
                                        <div class="file-preview box sm"></div>
                                    </div>
                                </div>
                                <div class="col-lg">
                                    <input type="text" placeholder="{{ translate('Link') }}" name="banner_section_four_links[]" value="{{ $banner->link }}" class="form-control">
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
                                        <i class="las la-times"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="">
                        <button
                            type="button"
                            class="btn btn-soft-secondary btn-sm"
                            data-toggle="add-more"
                            data-content='<div class="row gutters-5">
                                <div class="col-lg-5">
                                    <div class="form-group">
                                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                            </div>
                                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                            <input type="hidden" name="banner_section_four_images[]" class="selected-files">
                                        </div>
                                        <div class="file-preview box sm"></div>
                                    </div>
                                </div>
                                <div class="col-lg">
                                    <input type="text" placeholder="{{ translate('Link') }}" name="banner_section_four_links[]" class="form-control">
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
                                        <i class="las la-times"></i>
                                    </button>
                                </div>
                            </div>'
                            data-target=".banner-4-target">
                            {{ translate('Add New') }}
                        </button>
                    </div>
                </div>
            </div>

            <div class="form-group mb-0 text-right">
                <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
            </div>
        </form>
    </div>
</div>

@endsection