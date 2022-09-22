<div class="aiz-sidebar-wrap">
    <div class="aiz-sidebar bg-white left c-scrollbar-light py-3 pl-3 pr-3 pr-xl-0 d-flex flex-column">
        <div class="bg-dark rounded px-4 py-3 text-center text-white mb-3">
            @php $shop = auth()->user()->shop @endphp
            <img src="{{ uploaded_asset($shop->logo) }}" class="size-60px" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
            <div class="fs-16 fw-700 mt-2">{{ $shop->name }}</div>
            <div>{{ $shop->phone }}</div>
        </div>
        <div class="aiz-side-nav-wrap border rounded p-3 flex-grow-1">
            <ul class="aiz-side-nav-list" data-toggle="aiz-side-menu">
                <li class="aiz-side-nav-item">
                    <a href="{{ route('seller.dashboard') }}" class="aiz-side-nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                            <path id="Path_18917" data-name="Path 18917"
                                d="M3.889,11.889H9.222A.892.892,0,0,0,10.111,11V3.889A.892.892,0,0,0,9.222,3H3.889A.892.892,0,0,0,3,3.889V11A.892.892,0,0,0,3.889,11.889Zm0,7.111H9.222a.892.892,0,0,0,.889-.889V14.556a.892.892,0,0,0-.889-.889H3.889A.892.892,0,0,0,3,14.556v3.556A.892.892,0,0,0,3.889,19Zm8.889,0h5.333A.892.892,0,0,0,19,18.111V11a.892.892,0,0,0-.889-.889H12.778a.892.892,0,0,0-.889.889v7.111A.892.892,0,0,0,12.778,19ZM11.889,3.889V7.444a.892.892,0,0,0,.889.889h5.333A.892.892,0,0,0,19,7.444V3.889A.892.892,0,0,0,18.111,3H12.778A.892.892,0,0,0,11.889,3.889Z"
                                transform="translate(-3 -3)" fill="#707070" />
                        </svg>
                        <span class="aiz-side-nav-text">{{ translate('Dashboard') }}</span>
                    </a>
                </li>

                <li class="aiz-side-nav-item">
                    <a href="{{route('seller.products')}}" class="aiz-side-nav-link {{ areActiveRoutes(['seller.products', 'seller.products.create', 'seller.products.edit', 'seller.product.show']) }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                            <g id="Group_23" data-name="Group 23" transform="translate(-126 -590)">
                              <path id="Subtraction_31" data-name="Subtraction 31" d="M15,16H1a1,1,0,0,1-1-1V1A1,1,0,0,1,1,0H4.8V4.4a2,2,0,0,0,2,2H9.2a2,2,0,0,0,2-2V0H15a1,1,0,0,1,1,1V15A1,1,0,0,1,15,16Z" transform="translate(126 590)" fill="#707070"/>
                              <path id="Rectangle_93" data-name="Rectangle 93" d="M0,0H4A0,0,0,0,1,4,0V4A1,1,0,0,1,3,5H1A1,1,0,0,1,0,4V0A0,0,0,0,1,0,0Z" transform="translate(132 590)" fill="#707070"/>
                            </g>
                        </svg>   
                        <span class="aiz-side-nav-text">{{translate('Products')}}</span>
                    </a>
                </li>

                @if (get_setting('pos_activation_for_seller') == 1)
                     <li class="aiz-side-nav-item">
                        <a href="{{route('point-of-sales.seller_index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['point-of-sales.seller_index']) }}">
                            <svg id="Group_22661" data-name="Group 22661" xmlns="http://www.w3.org/2000/svg" width="16" height="18.563" viewBox="0 0 16 18.563">
                                <path id="Path_10" data-name="Path 10" d="M12.041,7H3.42A1.189,1.189,0,0,0,2.26,8.16V20.285A1.2,1.2,0,0,0,3.42,21.5h8.621a1.2,1.2,0,0,0,1.2-1.2V8.16A1.189,1.189,0,0,0,12.041,7ZM5.369,19.6h-1.1V18.5h1.1Zm0-2.732h-1.1v-1.1h1.1Zm0-2.732h-1.1v-1.1h1.1ZM8.27,19.6H7.179V18.5H8.287Zm0-2.732H7.179v-1.1H8.287Zm0-2.732H7.179v-1.1H8.287Zm2.9,5.465h-1.1V18.5h1.1Zm0-2.732h-1.1v-1.1h1.1Zm0-2.732h-1.1v-1.1h1.1Zm.377-3.481a.2.2,0,0,1-.191.2H4.087a.2.2,0,0,1-.191-.2V9.083a.191.191,0,0,1,.191-.191h7.3a.191.191,0,0,1,.191.191Zm5.906-1.682h-.261V19.519h.29a.777.777,0,0,0,.777-.777V9.756a.777.777,0,0,0-.806-.783Z" transform="translate(-2.26 -2.939)" fill="#707070"/>
                                <rect id="Rectangle_10" data-name="Rectangle 10" width="1.7" height="10.552" transform="translate(11.516 6.033)" fill="#707070"/>
                                <rect id="Rectangle_11" data-name="Rectangle 11" width="0.731" height="10.552" transform="translate(13.691 6.033)" fill="#707070"/>
                                <path id="Path_11" data-name="Path 11" d="M14.971,1.038a1.033,1.033,0,0,0-.3-.737,1.056,1.056,0,0,0-.737-.3,1.038,1.038,0,0,0-1.056,1.038v.615h2.077Zm-2.553,0a.882.882,0,0,1,0-.168.789.789,0,0,1,0-.122h.012A.58.58,0,0,1,12.488.58a.5.5,0,0,1,.041-.116,1.387,1.387,0,0,1,.168-.3A.58.58,0,0,1,12.743.1l.081-.1h-4.7A.946.946,0,0,0,7.18.94V3.515H12.4Z" transform="translate(-4.326 0)" fill="#707070"/>
                            </svg>
                            <span class="aiz-side-nav-text">{{translate('POS Manager')}}</span>
                        </a>
                    </li>
                @endif
               

                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="16" viewBox="0 0 14 16">
                            <path id="Subtraction_83" data-name="Subtraction 83" d="M7.086,16a.924.924,0,0,1-.106-.006h-.04a.667.667,0,0,1-.179-.025A9.27,9.27,0,0,1,.012,6.734V4a.788.788,0,0,1,.3-.58.873.873,0,0,1,.542-.187.891.891,0,0,1,.111.007H1.1A5.96,5.96,0,0,0,6.28.328.286.286,0,0,1,6.332.28.525.525,0,0,1,6.44.152a.837.837,0,0,1,.127-.1H6.7A.69.69,0,0,1,6.872,0h.311l.186.056h.084L7.512.1a.738.738,0,0,1,.127.1.759.759,0,0,1,.109.12V.376A5.878,5.878,0,0,0,12.821,3.2H13.1a.869.869,0,0,1,.581.218A.781.781,0,0,1,13.941,4c.05.871.05,1.8.05,2.775a8.868,8.868,0,0,1-1.758,5.7,9.7,9.7,0,0,1-5.041,3.523A.924.924,0,0,1,7.086,16ZM4.3,7.111a.18.18,0,0,0-.178.089.287.287,0,0,0,0,.415L5.339,8.8l-.3,1.688a.389.389,0,0,0,.029.178.319.319,0,0,0,.267.16.282.282,0,0,0,.148-.042L7,9.985l1.511.8a.224.224,0,0,0,.148.03h.059a.306.306,0,0,0,.237-.356l-.3-1.688L9.871,7.585a.164.164,0,0,0,.089-.148A.293.293,0,0,0,9.9,7.2a.227.227,0,0,0-.172-.09L8.034,6.874,7.265,5.333a.233.233,0,0,0-.119-.119A.285.285,0,0,0,7,5.173a.315.315,0,0,0-.268.16L5.99,6.874,4.3,7.111h0Z" transform="translate(0)" fill="#bdbdbd"/>
                        </svg>                                                       
                        <span class="aiz-side-nav-text">{{ translate('Packages') }}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <ul class="aiz-side-nav-list level-2">
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('seller.package_select') }}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{ translate('Upgrade') }}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('seller.package_purchase_history') }}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{ translate('Package Payments') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="aiz-side-nav-item">
                    <a href="{{ route('seller.orders') }}" class="aiz-side-nav-link {{ areActiveRoutes(['seller.orders_show']) }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                            <path id="Subtraction_32" data-name="Subtraction 32" d="M15,16H1a1,1,0,0,1-1-1V1A1,1,0,0,1,1,0H15a1,1,0,0,1,1,1V15A1,1,0,0,1,15,16ZM7,11a1,1,0,1,0,0,2h6a1,1,0,0,0,0-2ZM3,11a1,1,0,1,0,1,1A1,1,0,0,0,3,11ZM7,7A1,1,0,1,0,7,9h6a1,1,0,0,0,0-2ZM3,7A1,1,0,1,0,4,8,1,1,0,0,0,3,7ZM7,3A1,1,0,1,0,7,5h6a1,1,0,0,0,0-2ZM3,3A1,1,0,1,0,4,4,1,1,0,0,0,3,3Z" fill="#707070"/>
                        </svg>
                        <span class="aiz-side-nav-text">{{translate('Orders')}}</span>
                    </a>
                </li>

                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="15.999" viewBox="0 0 16 15.999">
                            <g id="Group_8937" data-name="Group 8937" transform="translate(-24 -40)">
                              <path id="Subtraction_81" data-name="Subtraction 81" d="M-1-527H-15a1,1,0,0,1-1-1v-14a1,1,0,0,1,1-1H-1a1,1,0,0,1,1,1v14A1,1,0,0,1-1-527Zm-10-6.6a2.344,2.344,0,0,0,.684,1.752,3.167,3.167,0,0,0,1.964.786V-530h.933v-1.056a2.916,2.916,0,0,0,1.734-.662,1.882,1.882,0,0,0,.637-1.466,1.931,1.931,0,0,0-.2-.909,2.1,2.1,0,0,0-.552-.662,4,4,0,0,0-.836-.507c-.312-.145-.659-.29-1.031-.431a2.493,2.493,0,0,1-.793-.436.805.805,0,0,1-.247-.617.825.825,0,0,1,.214-.6.809.809,0,0,1,.6-.219.81.81,0,0,1,.67.294,1.327,1.327,0,0,1,.235.841H-5a2.315,2.315,0,0,0-.613-1.648A2.741,2.741,0,0,0-7.3-538.87V-540h-.933v1.109a3.073,3.073,0,0,0-1.785.67,1.855,1.855,0,0,0-.669,1.464,2.007,2.007,0,0,0,.187.9,2.008,2.008,0,0,0,.54.657,3.745,3.745,0,0,0,.84.5c.326.144.684.288,1.063.426a2.2,2.2,0,0,1,.8.447.9.9,0,0,1,.229.652.766.766,0,0,1-.229.586.894.894,0,0,1-.628.212,1.11,1.11,0,0,1-.842-.312,1.282,1.282,0,0,1-.3-.912Z" transform="translate(40 583)" fill="#bdbdbd"/>
                            </g>
                        </svg>                                                      
                        <span class="aiz-side-nav-text">{{ translate('Earnings') }}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <ul class="aiz-side-nav-list level-2">
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('seller.payouts.index') }}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{ translate('Payouts') }}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('seller.payouts.request') }}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{ translate('Payout Requests') }}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('seller.commission_log.index') }}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{ translate('Commission History') }}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('seller.payout_settings') }}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{ translate('Payout Settings') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="{{ route('seller.coupons.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['seller.coupons.create', 'seller.coupons.edit']) }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                            <g id="discount_vouchers" data-name="discount vouchers" transform="translate(-1 -84.12)">
                              <rect id="Rectangle_16364" data-name="Rectangle 16364" width="0.471" height="0.314" transform="translate(4.699 96.989)" fill="none"/>
                              <path id="Path_18974" data-name="Path 18974" d="M221.059,177.34l-2.6,4.159.519.274,2.6-4.159Z" transform="translate(-210.637 -87.389)" fill="none"/>
                              <path id="Path_18975" data-name="Path 18975" d="M194.371,161.376a1.076,1.076,0,0,0,.3-.782v-.282a1.085,1.085,0,0,0-.3-.788,1.315,1.315,0,0,0-1.705,0,1.082,1.082,0,0,0-.305.784v.282a1.069,1.069,0,0,0,.307.782,1.145,1.145,0,0,0,.855.311A1.121,1.121,0,0,0,194.371,161.376Zm-1.184-.426a.548.548,0,0,1-.116-.356v-.282a.57.57,0,0,1,.115-.36.394.394,0,0,1,.331-.148.4.4,0,0,1,.333.148.565.565,0,0,1,.117.36v.282a.554.554,0,0,1-.115.356.394.394,0,0,1-.327.144A.409.409,0,0,1,193.186,160.95Zm2.876,1.259a1.136,1.136,0,0,0-.852.314,1.075,1.075,0,0,0-.307.782v.282a1.073,1.073,0,0,0,.309.784,1.148,1.148,0,0,0,.857.314,1.128,1.128,0,0,0,.847-.311,1.082,1.082,0,0,0,.3-.784v-.282a1.08,1.08,0,0,0-.3-.784A1.138,1.138,0,0,0,196.063,162.209Zm.45,1.378a.6.6,0,0,1-.1.367.4.4,0,0,1-.34.137.414.414,0,0,1-.331-.148.531.531,0,0,1-.126-.356V163.3a.565.565,0,0,1,.115-.356.4.4,0,0,1,.334-.148.393.393,0,0,1,.333.149.556.556,0,0,1,.117.356Z" transform="translate(-185.356 -69.828)" fill="none"/>
                              <path id="Path_18976" data-name="Path 18976" d="M217.983,181.118a.4.4,0,0,0-.334.148.565.565,0,0,0-.116.356v.282a.531.531,0,0,0,.126.356.413.413,0,0,0,.331.148.4.4,0,0,0,.34-.137.6.6,0,0,0,.1-.367v-.282a.556.556,0,0,0-.114-.356A.4.4,0,0,0,217.983,181.118Zm-2.213-1.851a.554.554,0,0,0,.115-.356v-.282a.565.565,0,0,0-.117-.36.446.446,0,0,0-.664,0,.569.569,0,0,0-.114.361v.282a.548.548,0,0,0,.117.356.407.407,0,0,0,.336.144A.4.4,0,0,0,215.77,179.267Z" transform="translate(-207.276 -88.5)" fill="#bdbdbd"/>
                              <path id="Path_18977" data-name="Path 18977" d="M16.405,90.72a2.1,2.1,0,0,0-1.211-.586V85.2a1.1,1.1,0,0,0-1.118-1.078H2.118A1.1,1.1,0,0,0,1,85.2v5.2a.231.231,0,0,0,.235.227,1.5,1.5,0,1,1,0,3A.231.231,0,0,0,1,93.845v5.2a1.1,1.1,0,0,0,1.118,1.078H14.076a1.1,1.1,0,0,0,1.118-1.078V94.117a2.068,2.068,0,0,0,1.693-1.339A1.958,1.958,0,0,0,16.405,90.72ZM5.169,98.941H4.7v-.587h.471Zm0-2.174H4.7V96.18h.471Zm0-2.174H4.7v-.587h.471Zm0-2.174H4.7v-.587h.471Zm0-2.174H4.7v-.587h.471Zm0-2.174H4.7v-.587h.471Zm0-2.174H4.7v-.589h.471ZM7,90.527v-.272a1.021,1.021,0,0,1,.305-.756,1.155,1.155,0,0,1,.85-.3,1.159,1.159,0,0,1,.855.3,1.027,1.027,0,0,1,.3.759v.272a1.017,1.017,0,0,1-.3.754,1.15,1.15,0,0,1-.847.3,1.173,1.173,0,0,1-.855-.3A1.014,1.014,0,0,1,7,90.527Zm.819,3.225,2.6-4.01.519.264-2.6,4.01Zm4.043-.339a1.015,1.015,0,0,1-.3.756,1.162,1.162,0,0,1-.847.3,1.173,1.173,0,0,1-.857-.3,1.015,1.015,0,0,1-.309-.756v-.272a1.017,1.017,0,0,1,.307-.754,1.158,1.158,0,0,1,.852-.3,1.16,1.16,0,0,1,.854.3,1.02,1.02,0,0,1,.3.756Z" fill="#bdbdbd"/>
                            </g>
                        </svg>                          
                        <span class="aiz-side-nav-text">{{translate('Coupons')}}</span>
                    </a>
                </li>

                @if (addon_is_activated('multi_vendor') && get_setting('conversation_system') == 1)
                    @php
                        $conversation = \App\Models\Conversation::where('receiver_id', Auth::user()->id)
                            ->where('receiver_viewed', 0)
                            ->get();
                    @endphp
                    <li class="aiz-side-nav-item">
                    <a href="{{ route('seller.querries.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['seller.querries.index', 'seller.querries.show']) }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                            <g id="Group_8863" data-name="Group 8863" transform="translate(-4 -4)">
                                <path id="Path_18925" data-name="Path 18925"
                                    d="M18.4,4H5.6A1.593,1.593,0,0,0,4.008,5.6L4,20l3.2-3.2H18.4A1.6,1.6,0,0,0,20,15.2V5.6A1.6,1.6,0,0,0,18.4,4ZM7.2,9.6h9.6v1.6H7.2Zm6.4,4H7.2V12h6.4Zm3.2-4.8H7.2V7.2h9.6Z"
                                    fill="#707070" />
                            </g>
                        </svg>
                       <span class="aiz-side-nav-text">{{ translate('Product Querries') }}</span>
                        @if (count($conversation) > 0)
                            <span
                                class="badge badge-inline badge-danger p-2">({{ count($conversation) }})</span>
                        @endif
                    </a>
                </li>
                @endif
                

                <li class="aiz-side-nav-item">
                    <a href="{{ route('seller.uploaded_files') }}" class="aiz-side-nav-link {{ areActiveRoutes(['seller.uploaded_files', 'seller.uploaded_files.create']) }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="16" viewBox="0 0 14 16">
                            <path id="Path_18924" data-name="Path 18924" d="M4.4,4.78v8.553A3.407,3.407,0,0,0,7.67,16.66l.23.007h6.18A2.1,2.1,0,0,1,12.1,18H7.2A4.1,4.1,0,0,1,3,14V6.667A2.01,2.01,0,0,1,4.4,4.78ZM14.9,2A2.052,2.052,0,0,1,17,4v9.333a2.052,2.052,0,0,1-2.1,2h-7a2.052,2.052,0,0,1-2.1-2V4A2.052,2.052,0,0,1,7.9,2Z" transform="translate(-3 -2)" fill="#bdbdbd"/>
                        </svg>                          
                        <span class="aiz-side-nav-text">{{translate('Uploaded Files')}}</span>
                    </a>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="{{ route('seller.product_reviews.index') }}" class="aiz-side-nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                            <g id="Group_8935" data-name="Group 8935" transform="translate(-4 -4)">
                              <path id="Path_18925" data-name="Path 18925" d="M18.4,4H5.6A1.593,1.593,0,0,0,4.008,5.6L4,20l3.2-3.2H18.4A1.6,1.6,0,0,0,20,15.2V5.6A1.6,1.6,0,0,0,18.4,4ZM7.2,9.6h9.6v1.6H7.2Zm6.4,4H7.2V12h6.4Zm3.2-4.8H7.2V7.2h9.6Z" fill="#bdbdbd"/>
                            </g>
                        </svg>
                        <span class="aiz-side-nav-text">{{translate('Reviews')}}</span>
                    </a>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="{{ route('seller.shop.index') }}" class="aiz-side-nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                            <g id="Group_8866" data-name="Group 8866" transform="translate(-3.185 -7)">
                              <path id="Path_18928" data-name="Path 18928" d="M13.688,20.6a6.064,6.064,0,0,0,1.331-.768l-.033.048,1.68.624a.826.826,0,0,0,1.015-.352l1.4-2.336a.79.79,0,0,0-.2-1.024L17.464,15.7l-.033.048a6.021,6.021,0,0,0,.083-.768,6.021,6.021,0,0,0-.083-.768l.033.048,1.414-1.088a.79.79,0,0,0,.2-1.024l-1.4-2.336a.845.845,0,0,0-1.015-.352l-1.68.624.033.048A7.559,7.559,0,0,0,13.688,9.4l-.283-1.728A.8.8,0,0,0,12.591,7H9.8a.8.8,0,0,0-.815.672L8.7,9.4a6.064,6.064,0,0,0-1.331.768L7.4,10.12,5.7,9.5a.826.826,0,0,0-1.015.352l-1.4,2.336a.79.79,0,0,0,.2,1.024L4.906,14.3l.033-.048A5.485,5.485,0,0,0,4.856,15a6.021,6.021,0,0,0,.083.768l-.033-.048L3.493,16.808a.79.79,0,0,0-.2,1.024l1.4,2.336A.845.845,0,0,0,5.7,20.52l1.68-.624-.017-.064A6.065,6.065,0,0,0,8.7,20.6l.283,1.712A.8.8,0,0,0,9.8,23h2.794a.8.8,0,0,0,.815-.672ZM7.867,15a3.329,3.329,0,1,1,3.326,3.2A3.275,3.275,0,0,1,7.867,15Z" transform="translate(0)" fill="#bdbdbd"/>
                            </g>
                        </svg>                          
                        <span class="aiz-side-nav-text">{{translate('Shop Settings')}}</span>
                    </a>
                </li>
            </ul><!-- .aiz-side-nav -->
        </div><!-- .aiz-side-nav-wrap -->
    </div><!-- .aiz-sidebar -->
    <div class="aiz-sidebar-overlay"></div>
</div><!-- .aiz-sidebar -->
