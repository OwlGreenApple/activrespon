@extends('layouts.app')

@section('content')

<!-- Modal Edit Dropdown -->
  <div class="modal fade child-modal" id="createNew" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">
               Details :
            </h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body">
            <div class="col-md-6">Active WA - Paket Enterprise</div>
            <div class="col-md-6">Yearly - Expired on <b>October 1 2020</b></div>
            <div class="col-md-6"><button class="btn btn-default btn-sm">Perpanjang</button></div>
            <div class="col-md-6">WASSENGER STATUS : <b>Expired</b></div>
            <div class="col-md-6"><button class="btn btn-default btn-sm">Go To WASSENGER</button></div>
        </div>
      </div>
      
    </div>
  </div>

<div class="container mb-2">
    <!-- Profile List -->
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                 @if (session('createnew'))
                    <div class="alert alert-success" role="alert">
                        {{ session('createnew') }}
                    </div>
                @endif
                <div class="card-header"><b>Create New</b></div>

                <div class="card-body">
                    <h4>Welcome To ActivWA</h4>

                    <div class="col-md-12 row">
                        <div class="col-md-2">
                             <img src="http://placehold.it/100x100" alt="..." class="img-responsive"/>
                        </div>
                        <div class="col-md-4">
                            <h3>Teknobie</h3>
                        </div>
                        <div class="col-md-4 row">
                            <div class="col-md-6"><button class="btn btn-default btn-sm">SetUp</button></div>
                            <div class="col-md-6"><button id="detail" class="btn btn-default btn-sm">Detail</button></div>
                        </div>
                    </div>
                    <div class="col-md-12"><a href="{{route('createdevice')}}" class="btn btn-primary">Create New</a></div>
                </div>
            </div><!-- end card -->

        </div>
    </div>
<!-- end container -->
</div>  

<script type="text/javascript">
    $(document).ready(function(){
        $("#detail").click(function(){
            $("#createNew").modal();
        });
    });
</script>

@endsection
