@if(count( $data ) > 0) 
    <div class="ms-auto me-auto col-lg-6 col-md-6 col-12 mb-4">
        <div class="input-group ms-auto me-auto col-lg-6 col-md-6 col-12">
            <button data-total="3" type="button" class="pricing_list tri @if($save == 15) active @endif position-relative @if($account == 1) pricing_fix_account @endif">
                {{ Lang::get('order.month.t') }}
                <span class="position-absolute top-0 start-50 translate-middle badge rounded-pill bg-warning">
                    -15%
                </span>
            </button> 
            <button data-total="12" type="button" class="pricing_list yearly @if($save == 40) active @endif position-relative @if($account == 1) pricing_fix_account @endif">
                {{ Lang::get('order.month.y') }}
                <span class="position-absolute top-0 start-50 translate-middle badge rounded-pill bg-warning">
                    -40%
                </span>
            </button>
        </div>
    </div>

    <div class="row">
    @foreach($data as $index)
        <div class="col-lg-4 col-md-4 col-12">
            <div data-ribbon="@if($index == 4 || $index == 5 || $index == 6) -15% @else -40% @endif" class="card card-pricing @if($index == 1 || $index == 2 || $index == 3) popular @else bestseller @endif shadow px-3 mb-4">
                <span class="h6 w-60 mx-auto px-4 py-1 rounded-bottom bg-custom text-white shadow-sm text-capitalize">{{ $pc->get_price()[$index]['package'] }}</span>
                <div class="bg-transparent card-header pt-4 border-0">
                    <h3 class="text-center @if($account == 1) h4 @else h3 @endif font-weight-normal text-custom text-center mb-0" data-pricing-value="30">{{ Lang::get('custom.currency') }}&nbsp;<span class="price">{{ $pc::format($pc->get_price()[$index]['price']) }}</span>
                    <div class="mt-2 text-muted ml-2 h5 mb-0"><span class="text-capitalize">{{ Lang::get('order.month') }}</span></div></h3>
                </div>
                <hr>

                <!--  -->
                <div class="card-body pt-0 mx-auto">
                    <ul style="@if($account == 1) font-size:14px @else font-size:16px @endif" class="subs list-unstyled mb-4">
                        <li><i class="fas fa-check main-color"></i>&nbsp;Max {{ $pc->get_price()[$index]['contestants'] }} {{ Lang::get('order.contestants') }}</li>
                        <li><i class="fas fa-check main-color"></i>&nbsp;{{ Lang::get('order.campaign') }} : <b>{{ $pc->get_price()[$index]['campaign'] }}</b></li>
                        <li><i class="fas fa-check main-color"></i>&nbsp;{{ Lang::get('order.auto') }}</li>

                        @if($index > 0)
                            <li><i class="fas fa-check main-color"></i>&nbsp;{{ Lang::get('order.history') }}</li>
                            <li><i class="fas fa-check main-color"></i>&nbsp;{{ Lang::get('order.winner') }}</li>
                            <li><i class="fas fa-check main-color"></i>&nbsp;{{ Lang::get('order.export') }}</li>
                        @endif
                        <li>@if($index > 2) <i class="fas fa-check main-color"></i>&nbsp; @else <i class="fas fa-minus-circle text-danger"></i>&nbsp; @endif {{ Lang::get('order.sponsor') }}</li>
                    </ul>
                    <div class="text-center">
                        <a href="{{ url('checkout') }}/{{$index}}" target="_blank" class="btn bg-custom text-white mb-3 order-{{ $index }}">{{ Lang::get('order.order') }}</a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    </div>
@endif