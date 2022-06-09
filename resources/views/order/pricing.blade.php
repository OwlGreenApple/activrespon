@extends('layouts.app')
<link href="{{ asset('/assets/css/pricing.css') }}" rel="stylesheet" />

@section('content')
<div class="container mb-5 mt-2">
    <div class="pricing mb-3 price_list_data">
        @include('order.pricing-list') 
    </div> 
</div>

<!-- price list -->
<script src="{{ asset('assets/js/pricing.js') }}" type="text/javascript"></script>
<script type="text/javascript"> 
   
    $(function(){ 
        change_price_list();
    });
</script>
@endsection
