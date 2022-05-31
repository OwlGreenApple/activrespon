@extends('layouts.app')

@section('content')

  <!-- TOP SECTION -->
<div class="container act-tel-dashboard">
  <div class="act-tel-dashboard-left">
    <h2>List</h2>
  </div>
  <div class="clearfix"></div>
</div>

<div class="container">

    <div class="act-tel-create-list bg-dashboard">
      <h3>Create Your List</h3>

      <div align="center">
        @if(session('error_number'))
          <div class="alert alert-danger col-lg-6">{{ session('error_number') }}</div>
        @endif

        <form method="GET" action="{{url('list-create')}}">
          @csrf
          <div class="form-group mt-5">
            <input name="listname" value="@if(session('listname')){{ session('listname') }}@endif" type="text" class="form-control custom-form" placeholder="Your List Name"/>
             @error('listname')
                <span class="error">{{ $message }}</span>
             @enderror
          </div>

          <div class="form-check mt-2">
            <input class="form-check-input" type="radio" name="is_secure" id="standardRadio" value="0" checked>
            <label class="form-check-label" for="standardRadio">
              Standard 
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="is_secure" id="secureRadio" value="1">
            <label class="form-check-label" for="secureRadio">
              Secure Auto Reply
            </label>
          </div>

          <div class="form-group text-left mt-2">
            <span class="tooltipstered" title="<div class='panel-heading'>Information :</div><div class='panel-content'>
              - [NAME] for name <br/>
              - [PHONE] for phone <br/>
              Whatsapp : <br/>
              - Bold : *<i>Your Text</i>*<br/>
              - Italics : _<i>Your Text</i>_<br/>
              - Strikethrough : ~<i>Your Text</i>~
            </div>">
              <b>Cara mengisi</b>
          </div>

          <div class="form-group mt-3">
            <textarea name="autoreply" id="divInput-description-post" class="form-control custom-form text-left" placeholder="Auto Reply Text">@if(session('autoreply')){{ session('autoreply') }}@endif</textarea>
          </div>

          <div class="form-group mt-3 secure-group" style="display:none;">
						<label class="text-left" style="display:block;">START Custom Message</label>
            <input type="text" name="start_custom_message" id="start_custom_message" class="form-control custom-form text-left" value="<?php if(session('start_custom_message')) {echo session('start_custom_message');  } else { echo 'Thank you, You have been Subscribed to [LIST_NAME]'; } ?>">
          </div>

          <div class="form-group mt-3 secure-group" style="display:none;">
						<label class="text-left" style="display:block;">UNSUBS Custom Message</label>
            <input type="text" name="unsubs_custom_message" id="unsubs_custom_message" class="form-control custom-form text-left" value="<?php if(session('unsubs_custom_message')) {echo session('unsubs_custom_message');  } else { echo 'Sorry to see you go, You have been Unsubscribed to [LIST_NAME] '; } ?>">
          </div>

          <div class="text-right">
            <button class="btn btn-custom">Buat List</button>
          </div>
        </form>
      </div>

    </div>

</div>

<script type="text/javascript">
  $("#divInput-description-post").emojioneArea({
        pickerPosition: "right",
        mainPathFolder : "{{url('')}}",
  });
  
  var tempText;
  $(document).ready(function(){
    $("body").on("click","#secureRadio",function(){
			$(".secure-group").show();
      tempText = $("#divInput-description-post").emojioneArea()[0].emojioneArea.getText();
      
      $("#divInput-description-post").emojioneArea()[0].emojioneArea.setText('Hi [NAME], \n Terima Kasih sudah mendaftar \n Langkah selanjutnya adalah : \n - Untuk menerima pesan klik > [START] \n - Untuk Unsubs klik > [UNSUBS]');
    });
    $("body").on("click","#standardRadio",function(){
			$(".secure-group").hide();
      $("#divInput-description-post").emojioneArea()[0].emojioneArea.setText(tempText);
    });
  });



</script>
@endsection
