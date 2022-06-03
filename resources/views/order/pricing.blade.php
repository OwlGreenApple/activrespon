@extends('layouts.app')

@section('content')
<div class="container mb-5 mt-2">
    <div class="pricing mb-3 price_list_data"><!--  --></div>
</div>

<!-- price list -->
<script src="{{ asset('assets/js/pricing.js') }}" type="text/javascript"></script>
<script type="text/javascript">
    var target_url = "{{ url('price-list') }}"; 
    var is_account = 0;

    $(function(){
        display_pricelist(null);
        change_price_list();
    });
</script>
@endsection
