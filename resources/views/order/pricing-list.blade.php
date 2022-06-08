@if(count( $data ) > 0) 
    <div class="ml-auto mr-auto col-lg-6 col-md-6 col-12 mb-4">
        <div class="input-group ml-auto mr-auto col-lg-12 col-md-12 col-12">
            <a role="button" class="pricing_list monthly position-relative @if($default == null) active @endif">
                {{ Lang::get('order.month.t') }}
                <div class="pricing-discount badge rounded-pill">
                    -25%
                </div>
            </a> 
            <a data-total="12" role="button" class="pricing_list yearly position-relative @if($default == 12) active @endif">
                {{ Lang::get('order.month.y') }}
                <div class="pricing-discount badge rounded-pill">
                    -65% 
                </div>
            </a>
        </div>
    </div>

    <div class="row">
    @foreach($data as $index) 
        <div class="col-lg-4 col-md-4 col-12"> 
            <div data-ribbon="15%" class="card card-pricing shadow px-3 mb-4">
                <span class="pricing-title text-capitalize bg-info">{{ getPackage($index,1)['label'] }}</span>
                <div class="bg-transparent card-header pt-4 border-0">
                    <h5 class="price text-center"><span class="text-info"><strike>{{ Lang::get('custom.currency') }}{{ pricingFormat(discount(getPackage($index,1)['price'],getPackage($index,1)['percent'])) }}</strike></span></h5>
                    <h3 class="text-center font-weight-normal text-custom text-center mb-0" data-pricing-value="30">{{ Lang::get('custom.currency') }}&nbsp;<span class="price">{{ str_replace(",",".",number_format(round(getPackage($index,1)['price'] / getPackage($index,1)['duration']))) }}</span>
                    <div class="mt-2 text-muted ml-2 h5 mb-0"><span class="text-capitalize">{{ Lang::get('order.month') }}</span></div></h3>
                    <h5 class="price text-center mt-2"><span class="text-info">{{ Lang::get('custom.currency') }}{{ pricingFormat(getPackage($index,1)['price']) }}</span> per {{ getPackage($index,1)['duration'] }} {{ Lang::get('order.monthly') }}</h5>
                </div>
                <hr>

                <!--  -->
                <div class="card-body pt-0 mx-auto">
                    <ul class="subs list-unstyled mb-4"> 
                        @if($index == 1 || $index == 4)
                            <li><i class="fas fa-check-circle text-info"></i>&nbsp;{{ Lang::get('order.contacts.max') }}</li>
                        @elseif($index == 2 || $index == 5)
                        <li><i class="fas fa-check-circle text-info"></i>&nbsp;{{ Lang::get('order.contacts.max.premium') }}</li>
                        @else
                            <li><i class="fas fa-check-circle text-info"></i>&nbsp;{{ Lang::get('order.contacts') }}</li>
                        @endif
                        <li><i class="fas fa-check-circle text-info"></i>&nbsp;{{ Lang::get('order.message') }}</li>
                        <li>
                            @if($index == 1 || $index == 4) 
                                <i class="fas fa-check-circle text-muted"></i>&nbsp;{{ Lang::get('order.sponsor') }}</li>
                            @else 
                                <i class="fas fa-check-circle text-info"></i>&nbsp;{{ Lang::get('order.sponsor.no') }}</li>
                            @endif 
                    </ul>
                    <div class="text-center">
                        <a href="{{ url('checkout') }}/{{ $index }}" target="_blank" class="btn bg-info text-white mb-3 order-{{ $index }}">{{ Lang::get('order.order') }}</a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    </div>
@endif