@extends('layouts.app')

@section('content')

<!-- TOP SECTION -->
<div class="container act-tel-dashboard">
  <div class="act-tel-dashboard-left">
    <h2>GENERATE TOKEN RESELLER</h2>
  </div>
  <div class="clearfix"></div>
</div>

<!-- FORM TOKEN -->
<div class="container act-tel-dashboard">
  <div class="act-tel-list-board">
    <div class="act-tel-list-left">Generate Token</div>
    <div class="act-tel-list-right"><span class="icon-carret-down-circle"></span></div>
    <div class="clearfix"></div>
  </div>

  <div class="act-tel-list bg-dashboard">
   
      <div class="row">
         <div class="col-lg-12">
          <div class="input-group input-group-lg">
            <input type="text" class="form-control input-lg mr-1" readonly="readonly" value="{{ $user->reseller_token }}" id="token" />
            <span class="input-group-btn">
              <button id="generate_token" type="button" class="btn btn-success btn-lg mr-1">Generate Token</button>
            <button type="button" class="btn btn-primary btn-lg btn-copy">Copy</button>
            </span>
          </div>
        </div>
      </div>

    </div>
</div>

<!-- Modal Copy Link -->
<div class="modal fade" id="copy-link" role="dialog">
  <div class="modal-dialog">
    
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modaltitle">
          Copy Link
        </h5>
      </div>
      <div class="modal-body">
        You have copied the link!
      </div>
      <div class="modal-footer" id="foot">
        <button class="btn btn-primary" data-dismiss="modal">
          OK
        </button>
      </div>
    </div>
      
  </div>
</div>

<script type="text/javascript">
  
  function generate_reseller_token()
  {
    $("#generate_token").click(function(){
       $.ajax({
        type : 'GET',
        url : '{{url("reseller-token")}}',
        dataType : 'text',
        beforeSend: function()
        {
          $('#loader').show();
          $('.div-loading').addClass('background-load');
        },
        success : function(result){
          $('#loader').hide();
          $('.div-loading').removeClass('background-load');
          $("#token").val(result);
        }
      });
      // end ajax
    });
  }

  function copyLink(){
    $( "body" ).on("click",".btn-copy",function(e) 
    {
      e.preventDefault();
      e.stopPropagation();

      var link = $("#token").val();

      var tempInput = document.createElement("input");
      tempInput.style = "position: absolute; left: -1000px; top: -1000px";
      tempInput.value = link;
      document.body.appendChild(tempInput);
      tempInput.select();
      document.execCommand("copy");
      document.body.removeChild(tempInput);
      $('#copy-link').modal('show');
    });
  }
  
  $(document).ready(function(){
    generate_reseller_token();
    copyLink();
  });

</script>
@endsection
