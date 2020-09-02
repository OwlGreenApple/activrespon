@extends('layouts.app')

@section('content')

<!-- Modal Copy Link -->
<div class="modal fade" id="copy-link" role="dialog">
  <div class="modal-dialog">
    
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modaltitle">
          Copy Script
        </h5>
      </div>
      <div class="modal-body">
        You have copied the script!
      </div>
      <div class="modal-footer" id="foot">
        <button class="btn btn-primary" data-dismiss="modal">
          OK
        </button>
      </div>
    </div>
      
  </div>
</div>

<!-- TOP SECTION -->
<div class="container act-tel-dashboard">
  <div class="act-tel-dashboard-left">
    <h2>Google Form Script Generator</h2>
  </div>
  <div class="clearfix"></div>
</div>

<div class="container">
  <div class="col-md-12">
    <div class="act-tel-create-list bg-dashboard">
      <h3>Create Your Script</h3>

      <div align="center">
        @if(session('error'))
          <div class="alert alert-danger col-lg-6">{{ session('error') }}</div>
        @endif

        <form>
          @csrf
          <div class="form-group mt-5">
            <input id="wacol" value="" type="text" class="form-control custom-form" placeholder="WA No Column"/>
          </div>

          <div class="form-group mt-3">
            <textarea name="autoreply" id="divInput-description-post" class="form-control custom-form text-left" placeholder="Message"></textarea>
          </div>
          <div class="text-right">
            <input type="button" class="btn btn-custom" id="btn-generate" value="Generate">
          </div>
          <div class="form-group mt-3" id="div-result-script">
            <span>Copy All<a data-copy="" class="btn-copy icon-copy"></a></span>
            <pre id="text-result-script" class="form-control custom-form text-left" placeholder="" ></pre>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>

<script type="text/javascript">
  $("#divInput-description-post").emojioneArea({
        pickerPosition: "right",
        mainPathFolder : "{{url('')}}",
  });
  
  var tempText;
  str1="function sendMessageWA() { var kolom_no_wa=";
  keyApi = "<?php echo $key; ?>";
  str2=';var key_id="'+keyApi;
  str3='";var template="';
  str4='"; algo(kolom_no_wa,key_id,template);}';
  strAlgo="function algo(kolom_no_wa,key_id,template){    var _0x48d7=['post','fetch','log','getActiveSheet','getRange','getLastRow','getLastColumn','getValues','length','split','[kolom','join','http://116.203.92.59/api/async_send_message','application/json'];(function(_0xc35321,_0x538ee9){var _0x4bf9f3=function(_0x3f3f6e){while(--_0x3f3f6e){_0xc35321['push'](_0xc35321['shift']());}};_0x4bf9f3(++_0x538ee9);}(_0x48d7,0x1a7));var _0xb32f=function(_0xc35321,_0x538ee9){_0xc35321=_0xc35321-0x0;var _0x4bf9f3=_0x48d7[_0xc35321];return _0x4bf9f3;};var ss=SpreadsheetApp[_0xb32f('0x0')]();var rows=ss[_0xb32f('0x1')](0x2,0x2,ss[_0xb32f('0x2')](),ss[_0xb32f('0x3')]())[_0xb32f('0x4')]();var i=rows[_0xb32f('0x5')]-0x2;for(var k=0x0;k<=ss[_0xb32f('0x3')]();k++){var template=template[_0xb32f('0x6')](_0xb32f('0x7')+(k+0x2)+']')[_0xb32f('0x8')](rows[i][k]);}var url=_0xb32f('0x9');var data={'phone_no':rows[i][kolom_no_wa-0x2],'key':key_id,'message':template};var payload=JSON['stringify'](data);var length=payload[_0xb32f('0x5')]['toString']();var headers={'Content-Type':_0xb32f('0xa')};var options={'method':_0xb32f('0xb'),'payload':payload,'headers':headers,'contentLength':length,'muteHttpExceptions':!![]};Utilities['sleep'](0xbb8);var response=UrlFetchApp[_0xb32f('0xc')](url,options);Logger[_0xb32f('0xd')](response);Logger[_0xb32f('0xd')](rows[i][0x1]);Logger[_0xb32f('0xd')](rows[i][0x0]);}";
  $(document).ready(function(){
    $("#div-result-script").hide();
    $('body').on('click', '#btn-generate', function (e) {
      // $("#div-result-script").show();
      strWacol = $("#wacol").val();
      strText = $("#divInput-description-post").emojioneArea()[0].emojioneArea.getText();
// strText.replace(/\n/g, "&\n")
      // console.log(str1+strWacol+str2+str3+strText+str4+strAlgo);

      // $("#text-result-script").html(str1+strWacol+str2+str3+strText.replace(/\n/g, "&\n")+str4+strAlgo);
            $.ajax({
                type : 'GET',
                url : '{{url("jsonEncode")}}',
                data : {'data':str1+strWacol+str2+str3+strText+str4+strAlgo},
                dataType : "text",
                success : function(txt){
                    console.log(txt);
                    var link = txt;

                    var tempInput = document.createElement("input");
                    tempInput.style = "position: absolute; left: -1000px; top: -1000px";
                    tempInput.value = link;
                    document.body.appendChild(tempInput);
                    tempInput.select();
                    document.execCommand("copy");
                    document.body.removeChild(tempInput);

                    $('#copy-link').modal('show');
                }
            });      
    });
  });



</script>
@endsection
