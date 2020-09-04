@extends('layouts.app')

@section('content')

  <!-- TOP SECTION -->
<div class="container act-tel-list-data">

  <div class="left">
     <h3>List Name : <span class="listname">{{$label}}</span></h3>
     <span><a id="edit_list_name" class="btn btn-activ icon-edit"></a></span>
  </div>
  <div class="clearfix"></div>
</div>

<div class="container">
  <ul id="tabs" class="row">
      <li class="col-lg-2"><a id="tab1">Form</a></li>
      <li class="col-lg-2"><a id="tab5">Google Form</a></li>
      <li class="col-lg-2"><a id="tab2">Add Contact</a></li>
      <li class="col-lg-3"><a id="tab3">Contact List</a></li>
      <li class="col-lg-3"><a id="tab4">Auto Reply</a></li>

  </ul>

  <!-- TABS CONTAINER -->
  <div class="tabs-content list-tabs">
    <!-- TABS 1 -->
    <div class="tabs-container" id="tab1C">
      <div class="act-tel-tab">
        <div class="wrapper">
          <div class="form-control col-lg-6 message">
            <sb>Saved, click to copy link from</sb> <a class="icon-copy"></a>
          </div>
        </div>

        <form class="form-contact" id="edit_list">
          <div class="wrapper">
            <div class="form-contact">
              <div class="input-group form-group">
                  <input type="button" id="open_ck_editor" class="form-control" value='Create "Form Header"' />
              </div>

              <div class="input-group form-group showeditor">
                <textarea name="editor1" id="editor1" rows="10" cols="80">{!! $data['content'] !!}</textarea>
              </div>

              <div class="input-group form-group">
                  <input type="text" name="label_name" class="form-control" placeholder="Label Name" value="{{ $data['label_name'] }}"/>
                  <div class="error label_name col-lg-12 text-left"></div>
              </div> 

              <div class="form-row">
                  <div class="form-group col-lg-11">
                    <input type="text" name="label_last_name" class="form-control" placeholder="Label Last Name" value="{{ $data['label_last_name'] }}"/>
                  </div>

                  <div class="form-group col-md-1">
                    <input type="checkbox" name="checkbox_lastname" class="mt-2" @if($data['checkbox_lastname'] == 1) checked value="1" @else value="0" @endif />
                  </div>
                  <div class="error label_name col-lg-12 text-left"></div>
              </div>

              <div class="input-group form-group">
                  <input type="text" name="label_phone" class="form-control" placeholder="Label Phone" value="{{ $data['label_phone'] }}"/>
                  <div class="error label_phone col-lg-12 text-left"></div>
              </div>

              <div class="form-row">
                  <div class="form-group col-lg-11">
                    <input type="text" name="label_email" class="form-control" placeholder="Label Email" value="{{ $data['label_email'] }}"/>
                  </div>

                  <div class="form-group col-md-1">
                    <input type="checkbox" name="checkbox_email" class="mt-2" @if($data['checkbox_email'] == 1) checked value="1" @else value="0" @endif />
                  </div>
                  <div class="error label_email col-lg-12 text-left"></div>
              </div>
            </div><!-- end form contact -->
          </div><!-- end wrapper -->
          

           <!-- outer wrapper -->
          <div class="outer-wrapper">
            <div class="form-row">
              <div class="form-group col-md-4 py-2">
                <h6>Custom Fields</h6>
              </div>

              <div class="form-group col-md-7">
                <div class="relativity">
                   <select id="type_fields" class="form-control custom-select">
                      <option value="1">Fields</option>
                      <option value="2">Dropdown</option>
                   </select>
                   <span class="icon-carret-down-circle"></span>
                </div>
              </div>
              <div class="form-group col-md-1">
                <button type="button" class="btn btn-form add-field"><span class="icon-add"></span></button>
              </div>
            </div>

            <div id="additional" class="form-row">
                <!-- additional -->
            </div>

            <div class="wrapper">
              <div class="input-group form-group">
                  <input type="text" name="button_rename" class="form-control" placeholder="Rename Submit Button" value="{{ $data['button_subscriber'] }}"/>
                  <div class="error button_rename col-lg-12 text-left"></div>
              </div>
            </div>

          </div>
          <!-- end outer wrapper -->

          <!-- middle wrapper -->
          <div class="wrapper">
            <div class="form-group text-left">
               <label>Add Script 
                    <span class="tooltipstered" title="<div class='panel-heading'>Media Sosial</div><div class='panel-content'>
										Add your FB Pixel / Google retargeting script here
                    </div>">
                      <i class="fa fa-question-circle "></i>
                    </span>
								</label>
               <textarea name="pixel" class="form-control"></textarea>
            </div> 

            <div class="form-group text-left">
               <label>Success Page <span class="tooltipstered" title="<div class='panel-heading'>Messages</div><div class='panel-content'>
                    Type your message for customer / subscriber after they registered
                    </div>">
                      <i class="fa fa-question-circle "></i></label>

                <textarea name="editor2" id="editor2" rows="10" cols="80">{!! $data['message_conf'] !!}</textarea>
               <div class="error message_conf col-lg-12 text-left"></div>
            </div> 
            
            <div class="text-right">
              <button type="submit" class="btn btn-custom">Save Form</button>
            </div>

             <div class="alerts"><!-- --></div>
          </div>
          <!-- end middle wrapper -->
        </form>

        <!-- last wrapper -->
        <div class="wrapper">
          <div class="form-group text-left">
             <label class="col-md-12 row">FORM URL&nbsp;&nbsp;<a data-link="{{$url}}" class="icon-copy btn-copy"></a></label>
             <input id="linkcopy" value="{{$url}}" type="text" class="form-control-lg" />
          </div>
          
          <div class="form-group text-left">
              <label>COPY / PASTE on your Site&nbsp;&nbsp;<a class="structure-form"><span class="icon-copy"></span></a></label>
              <textarea class="form-control" id="structure-form" readonly="readonly"><iframe src="{{url($listname)}}" style="border:0px;" name="myiFrame" scrolling="no" marginheight="0px" marginwidth="0px" height="600px" width="900px" allowfullscreen allowtransparency="true"></iframe></textarea>
          </div>
        </div>
        <!-- end last wrapper -->
      </div><!-- end actel-tab -->  
    <!-- end tabs -->  
    </div>

    <!-- TABS 5 -->
    <div class="tabs-container" id="tab5C">
      <div class="act-tel-tab">

      <h3>Copy Your Script
				<span class="tooltipstered" title="<div class='panel-heading'>Copy your script</div><div class='panel-content'>
				Make sure column B is Name, C is Phone Number, D is Email
				</div>">
					<i class="fa fa-question-circle "></i>
				</span>
			</h3>

      <div align="center">

        <form>
          @csrf

          <div class="form-group mt-3">
            <textarea name="" id="text-google-script" class="form-control custom-form text-left" rows="25">function init() {
	list_name ="{{$data['list_name']}}";
	myFunctionpost(list_name);
}

function lastValue(column) {
  var lastRow = SpreadsheetApp.getActiveSheet().getMaxRows();
  var values = SpreadsheetApp.getActiveSheet().getRange(column + "1:" + column + lastRow).getValues();

  for (; values[lastRow - 1] == "" && lastRow > 0; lastRow--) {}
  return values[lastRow - 1];
}

function myFunctionpost(list_name) {
var _0x2799=['https://activrespon.com/dashboard/entry-google-form','fetch','application/json','toString','log','stringify','Basic\x20_authcode_'];(function(_0x36ee5d,_0x279935){var _0x646ae4=function(_0x1dc43e){while(--_0x1dc43e){_0x36ee5d['push'](_0x36ee5d['shift']());}};_0x646ae4(++_0x279935);}(_0x2799,0x14a));var _0x646a=function(_0x36ee5d,_0x279935){_0x36ee5d=_0x36ee5d-0x0;var _0x646ae4=_0x2799[_0x36ee5d];return _0x646ae4;};var url=_0x646a('0x6');var b=lastValue('b');var c=lastValue('c');var d=lastValue('d');Logger[_0x646a('0x3')](b['toString']());var data={'list_name':list_name,'name':b[_0x646a('0x2')](),'email':d[_0x646a('0x2')](),'phone_number':c[_0x646a('0x2')]()};var payload=JSON[_0x646a('0x4')](data);var headers={'Accept':'application/json','Content-Type':'application/json','Authorization':_0x646a('0x5')};var options={'method':'POST','contentType':_0x646a('0x1'),'headers':headers,'payload':payload};var response=UrlFetchApp[_0x646a('0x0')](url,options);Logger[_0x646a('0x3')](response);
}</textarea>
          </div>
          <div class="text-right">
            <input type="button" class="btn btn-custom" id="btn-generate" value="Copy All">
          </div>
        </form>
      </div>

        <!-- end last wrapper -->
      </div><!-- end actel-tab -->  
     <!-- end tabs -->  
    </div>
    
		<!-- TABS 2 -->
    <div class="tabs-container" id="tab2C">
      <div class="act-tel-tab">
        <div class="form-control wrapper message mimport">
          If you want add contact more than 1 please click : "<a class="open_import"><b>import contacts</b></a>" <!--or "<b>take from group</b>" if you want -->
        </div>

        <div class="wrapper">
          <div class="error_message"><!-- error --></div>
          <div class="main"><!-- message --></div>
        </div>

        <div id="move_contact">
        <form class="wrapper add-contact">
            <div class="form-group">
              <label>Name:</label>
              <input type="text" name="subscribername" class="form-control" placeholder="Input Your Name" >
              <span class="error name"></span>
            </div>

            <div class="prep1">
              <div class="form-group">
                 <label>Phone Number</label>
                 <div class="col-sm-12 row">
                   <!--  <div class="col-lg-3 row relativity">
                      <input name="code_country" class="form-control custom-select-campaign" value="+62" autocomplete="off" />
                      <span class="icon-carret-down-circle"></span>
                      <span class="error code_country"></span>
                    </div> -->

                    <div class="col-sm-12 row">
                      <input type="text" id="phone" name="phone_number" class="form-control" />
                      <span class="error code_country"></span>
                      <span class="error phone_number"></span>
                    </div>
                    <div class="col-lg-12 pad-fix"><ul id="display_countries"><!-- Display country here... --></ul></div>
                  </div>
              </div>
            </div>

            <div class="form-group">
              <label>Email</label>
              <input type="email" name="email" class="form-control" placeholder="Input Your Email" />
              <span class="error email"></span>
            </div>

            <input type="hidden" name="listname" value="{{$listname}}">
            <input type="hidden" name="listid" value="{{ $listid }}">

            <div class="text-right">
              <button type="submit" class="btn btn-custom">Add Contact</button>
            </div>
        </form>
        </div>

      </div>
    <!-- end tabs -->  
    </div>

    <!-- TABS 3 -->
    <div class="tabs-container" id="tab3C">
      <div class="wrapper del_message"></div>
      <div class="act-tel-tab table-responsive" id="customer_list">
          <!-- display customer list here ... --> 
      </div>
    <!-- end tabs -->  
    </div>
		
    <!-- TABS 4 -->
    <div class="tabs-container" id="tab4C">
      <div class="act-tel-tab">

        <div class="wrapper">
          <div class="form-control col-lg-6 message">
            <sb>Saved, click to copy link from</sb> <a class="icon-copy"></a>
          </div>
        </div>

        <div class="autoreply_error"><!-- display error message --></div>

				<form id="form-auto-reply">
					<input type="hidden" name="idlist">
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

          <div class="form-group mt-3">
            <!-- <div class="resizer_wrapper"> -->
              <textarea name="autoreply" id="divInput-description-post" class="form-control custom-form text-left" placeholder="Auto Reply Text"><?php echo $data['auto_reply_message'];?></textarea>
              <!-- <div id="resizer"></div>
            </div> -->
          </div>
          <div class="form-group mt-3 secure-group" style="display:none;">
						<label class="text-left" style="display:block;">START Custom Message</label>
            <input type="text" name="start_custom_message" id="start_custom_message" class="form-control custom-form text-left" value="<?php echo $data['start_custom_message'];?>">
          </div>

          <div class="form-group mt-3 secure-group" style="display:none;">
						<label class="text-left" style="display:block;">UNSUBS Custom Message</label>
            <input type="text" name="unsubs_custom_message" id="unsubs_custom_message" class="form-control custom-form text-left" value="<?php echo $data['unsubs_custom_message'];?>">
          </div>

          <div class="text-right mb-3">
            <button class="btn btn-custom" id="btn-save-autoreply">Save</button>
          </div>
				</form>

        <!-- datatable -->
        <h5 class="alert alert-primary">Status delivery auto reply's messages</h5>

        <div class="table-responsive">
        <table id="autoreply_table" class="table display w-100">
          <thead class="bg-dashboard">
            <tr>
              <th class="text-center">No</th>
                <th class="text-center">Date Send</th>
                <th class="text-center">Name Contact</th>
                <th class="text-center">WA Contact</th>
                <th class="text-center">Status</th>
            </tr>
          </thead>

          <tbody>
            @php $no = 1; @endphp
            @foreach($data['auto_reply'] as $row)
              @if($row->name == null) 
                @php $row->name = '(Data deleted)'; @endphp 
              @endif
              
              @if($row->telegram_number == null)  
                @php $row->telegram_number = '(Data deleted)'; @endphp
              @endif

              <tr>
                <td>{{ $no }}</td>
                <td>{{ $row->updated_at }}</td>
                <td>{!! $row->name !!}</td>
                <td>{!! $row->telegram_number !!}</td>
                <td>{!! message_status($row->status) !!}</td>
              </tr>
            @php $no++ @endphp
            @endforeach
          </tbody>
        </table>
        </div>

        <!-- end last wrapper -->
      </div><!-- end actel-tab -->  
     <!-- end tabs -->  
    </div>
  <!---------- end tab content ------------>    
  </div>

<!------ end container ------->
</div>

<!-- Modal Import Contact -->
  <div class="modal fade child-modal" id="import-contact" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content -->
      <div class="modal-content">
        <div class="modal-body">
            <div class="text-center">
              <span class="error_notif"></span>
            </div>
            <div class="form-group">
                 <div class="mb-2">
                  <form id="importform">
                    <label>Import Contacts</label>
                      <input class="form-control" name="csv_file" type="file" />
                      <input type="hidden" name="list_id_import" value="{{ $id }}" />
                    <span><i>Upload .xlsx file only</i></span>

                    <div><a href="{{ asset('assets/excel/xlsx-example.xlsx') }}">Download Sample XLSX File Here</a></div>

                    <div class="text-right">
                      <button type="submit" class="btn btn-custom mr-1">Import</button>
                      <button id="btn_close_import" type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    </div>
                  </form>
                </div>
               
            </div>
        </div>
      </div>
      
    </div>
  </div>
  <!-- End Modal -->

  <!-- Modal Add Fields -->
  <div class="modal fade child-modal" id="openFields" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-body">
            <div class="form-group">
                 <div class="mb-2">
                    <input class="btn btn-info btn-sm add-field-column" type="button" value="Add Fields" />
                </div>
               
                <label>Field List</label>
                <form id="addFieldsForm">
                    <span id="append_fields"></span>
                    <input type="hidden" value="{{$id}}" name="field_list"/>
                    <div class="form-group">
                       <button id="cfd" class="btn btn-success btn-sm">Create Fields</button>
                       <button type="button" data-dismiss="modal" class="btn btn-default btn-sm">Close</button>
                    </div>
                </form>
            </div>
        </div>
      </div>
      
    </div>
  </div>

<!-- Modal Dropdown -->
  <div class="modal fade child-modal" id="openDropdown" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-body">
            <div class="form-group">
                 <div class="mb-2">
                    <input class="btn btn-default btn-sm add-option" type="button" value="Add Option" />
                </div>
                 <form id="dropdownForms">

                     <div class="form-group">
                        <label>Dropdown name</label>
                       <input name="dropdowname" type="text" class="form-control" />
                     </div> 
                     <label>Option Value</label>
                      <div id="appendoption" class="form-group">
                         <!-- display input here -->
                      </div> 
                      <input type="hidden" name="dropdownlist"/>
                      <div class="form-group">
                         <button id="cdp" class="btn btn-success btn-sm">Create Dropdown</button>
                         <button type="button" data-dismiss="modal" class="btn btn-default btn-sm">Close</button>
                      </div>

                 </form>
            </div>
        </div>
      </div>
      
    </div>
  </div>

  <!-- Modal Edit Dropdown -->
  <div class="modal fade child-modal" id="editDropdown" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-body">
            <div class="form-group">
                 <div class="mb-2">
                    <input class="btn btn-primary btn-sm add-edit-option" type="button" value="Add Option" />
                </div>
               
                <label>Option List</label>
                <form id="optionform">
                    <div id="editoptions" class="form-group">
                       <!-- display input here -->
                    </div> 

                    <input type="hidden" name="parent_id"/>
                    <input type="hidden" name="list_id"/>
                    <div class="form-group">
                       <button id="edp" class="btn btn-success btn-sm">Edit Dropdown</button>
                       <button type="button" data-dismiss="modal" class="btn btn-default btn-sm">Close</button>
                    </div>
                </form>
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
        <button type="button" class="close" data-dismiss="modal">&times;</button>
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
  <!-- Modal Copy Script -->
<div class="modal fade" id="copy-script" role="dialog">
  <div class="modal-dialog">
    
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modaltitle">
          Copy Script
        </h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
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

<!-- Modal Copy Link -->
<div class="modal fade" id="display_attribute" role="dialog">
  <div class="modal-dialog">
    
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modaltitle">
          Customer Attribute
        </h5>
      </div>
      <div class="modal-body">
        <div id="customer_additional">
          <!-- display customer additional -->
        </div>
      </div>
      <div class="modal-footer" id="foot">
        <button class="btn btn-primary" data-dismiss="modal">
          Close
        </button>
      </div>
    </div>
      
  </div>
</div>

<!-- Modal Import phone available -->
<div class="modal fade" id="duplicate_phone" role="dialog">
  <div class="modal-dialog">
    
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
            <span class="duplicated"></span>
        </h5>
      </div>
      <div class="modal-body">
         <form id="data_serialize"></form>
         <button class="btn btn-primary overwrite" data-overwrite="1">Overwrite</button>
         <button class="btn btn-primary overwrite" data-overwrite="0">Skip</button>
      </div>
    </div>
      
  </div>
</div>

<!-- Modal Import phone available -->
<div class="modal fade" id="duplicate_phone_contact" role="dialog">
  <div class="modal-dialog">
    
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
            <span class="duplicated"></span>
        </h5>
      </div>
      <div class="modal-body">
         <button class="btn btn-primary overwrite_contact" data-overwrite="1">Overwrite</button>
         <button class="btn btn-primary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
      
  </div>
</div>

<!-- Modal Edit List Name -->
<div class="modal fade" id="display_edit_list_name" role="dialog">
  <div class="modal-dialog">
    
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modaltitle">
          Edit List Name
        </h5>
      </div>
      <div class="modal-body">
         <input type="text" name="list_label" class="form-control" placeholder="Input List name" value="{{$label}}" maxlength="100"/>
          <div class="error list_label col-lg-12 text-left"></div>
      </div>
      <div class="modal-footer" id="foot">
        <button id="list_name" class="btn btn-info text-white">Change list name</button>
        <button class="btn btn-secondary" data-dismiss="modal">
          Close
        </button>
      </div>
    </div>
      
  </div>
</div>

<!-- Modal Edit Contact -->
  <div class="modal fade child-modal" id="edit_customer" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content -->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            Edit Customer Data
          </h5>
          <a class="btn btn-danger btn-sm text-white" data-dismiss="modal">Close</a>
        </div>
        <div class="modal-body">
            <div class="text-center">
              <span class="update_notif"></span>
            </div>

            <form class="update-contact">
              <div class="form-group">
                <label>Name:</label>
                <input type="text" name="subscribername" class="form-control" placeholder="Input Customer Name" >
                <span class="error name"></span>
              </div> 

              <div class="form-group">
                <label>Last Name:</label>
                <input type="text" name="last_name" class="form-control" />
                <span class="error last_name"></span>
              </div>

              <div class="form-group">
                 <label>Current Customer Phone Number</label>
                 <div class="col-sm-12 row">
                    <div class="col-sm-12 row">
                      <div class="form-control current_phone_number"></div>
                    </div>
                  </div>
              </div> 

              <div class="form-group">
                 <label>Edit Phone Number</label>
                 <div class="col-sm-12 row">
                    <div class="col-sm-12 row">
                      <input id="phone_number" name="phone_number" class="form-control" />
                      <span class="error code_country"></span>
                      <span class="error phone_number"></span>
                    </div>
                  </div>
              </div>

              <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" placeholder="Input Your Email" />
                <span class="error email"></span>
              </div>

              <input type="hidden" name="listname" value="{{$listname}}">
              <input type="hidden" name="listid" value="{{ $listid }}">

              <div class="text-right">
                <button id="change_btn" type="submit" class="btn btn-success">Update Contact</button>
              </div>
          </form>
            
        </div>
      </div>
      
    </div>
  </div>
  <!-- End Modal -->

<!-- Modal resend -->
<div class="modal fade" id="resend_popup" role="dialog">
  <div class="modal-dialog">
    
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header"><h5>Are you sure to resend auto reply message?</h5></div>
      <div class="modal-body">
         <button id="resend_message" class="btn btn-primary">Resend</button>
         <button class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
      
  </div>
</div>
<!-- End Modal -->

<script src="{{ url('assets/intl-tel-input/callbackplugin.js') }}" type="text/javascript"></script>
<script src="{{ url('assets/intl-tel-input/callback.js') }}" type="text/javascript"></script>

<script type="text/javascript">
  /* CKEditor */

  var settings_ck_editor = {
      allowedContent: true,
      filebrowserBrowseUrl: "{{ route('ckbrowse') }}",
      filebrowserUploadUrl: "{{ route('ckupload') }}",
      extraPlugins: ['uploadimage','image2','justify','colorbutton','videoembed','font'],
      removePlugins : 'image',
  };

  CKEDITOR.replace( 'editor1',settings_ck_editor);
  CKEDITOR.replace( 'editor2',settings_ck_editor);

  CKEDITOR.editorConfig = function( config ) {
      config.extraAllowedContent = true;
      config.extraPlugins = 'uploadimage','image2','justify','colorbutton','videoembed','font';
      config.removePlugins = 'image';
  };

  var fd = new FormData();

  $(document).ready(function() {  
    tabs();
    display_edit_list_name();
    open_ck_editor();  
    getChecked();  
    //Choose();
    openImport();
    excelImportCheck();
    cloneFile();
    overWriteFile();
    checkContact();
    addContact();
    updateContact();
    displayCustomer();
    //column -- edit
    displayAdditional();
    updateList();
    delCols();
    addCols();
    addFields();
    insertDropdown();
    addDropdown();
    editOption();
    addOption();
    insertOption();
    delOption();
    insertFields();
    openAdditional();
    copyLink();
    changeListName();
    dataCustomer();
    customerAttribute();
    delCustomer();
    pagination();
   /* codeCountry();
    putCallCode();*/
    initAutoReply();
		autoReplyButton();
		saveAutoReply();
    fixWidthPhoneInput();
    pastePhoneNumber();
    display_edit_customer_form();
    buttonGenerateGoogleScript();
    data_auto_reply();
    /*onResize();
    stopResize();*/
    addResendBtn('#autoreply_table_length');
    resendBtn();
    triggerButtonMod();
  });

  function addResendBtn(elem)
  {
    var message = "<div class='panel-heading'>You can resend message if status are : 'phone offline or queued'</div>";
    var tooltip='<span style="font-size : 18px" class="tooltipstered" title="'+message+'"><i class="fa fa-question-circle"></i></span>';

    $(elem).append("<label class='ml-2'><button id='resend' class='btn btn-info text-white btn-sm'>Resend</button></label><label class='ml-1'>"+tooltip+"</label>");
  }

  function resendBtn()
  {
    $("body").on('click','#resend',function(){
      $("#resend_popup").modal();
    });
 
    $("body").on('click','#resend_message',function(){
      resend();
    });
  }

  function resend()
  {
    $.ajax({
      type : 'GET',
      url : '{{url("resend_auto_eply")}}',
      data : {list_id : "{!! $data['listid'] !!}"},
      dataType : "json",
      beforeSend: function()
      {
        $('#loader').show();
        $('.div-loading').addClass('background-load');
      },
      success : function(result)
      {
         if(result.success == 1)
         {
            location.href = '{{ url("list-edit") }}/{{ $data["listid"] }}?mod=1'
         }
         else if(result.success == 0)
         {
            $('#loader').hide();
            $('.div-loading').removeClass('background-load');

            $("#resend_popup").modal('hide');
            $('.autoreply_error').html('<div class="alert alert-danger">Sorry, currently our server is too busy, please try again later.</div>')
         }
      },
      error: function(xhr)
      {
        $('#loader').hide();
        $('.div-loading').removeClass('background-load');
        console.log(xhr.responseText);
      }
    }); 
  }

  function triggerButtonMod()
  {
      var mod = "{{ $data['mod'] }}";
      if(mod == 1)
      {
        setTimeout(function(){
          $("#tab4").trigger('click');
        },700);
      }
  }

  var resizeEmojioneArea = function(e){
     var cur_height = $(".emojionearea").height();
     var posY = e.clientY;
     posY = posY/posY;
     cur_height = cur_height + posY;
     $(this).height(cur_height);
  }

  /* Resize Emojione prototype */

  function onResize()
  {
    $("#resizer").mousedown(function(){        
      $("body").on('mousemove','.emojionearea',resizeEmojioneArea);
    })
  }

  function stopResize()
  {
    $("#resizer").mouseup(function(){
      $("body").off('mousemove','.emojionearea',resizeEmojioneArea);
    });
  }

  function data_auto_reply()
  {
    $("#autoreply_table").DataTable({
      "lengthMenu": [ 10, 25, 50, 75, 100, 250, 500 ]
    });
  }

  function getChecked()
  {
    $("input[name='checkbox_lastname'],input[name='checkbox_email']").change(function(){
      var checked = $(this).prop('checked');
      if(checked == true)
      {
        $(this).val(1);
      }
      else
      {
        $(this).val(0);
      }
    });
  }

  function pastePhoneNumber()
  {
    $("#phone_number").on('paste',function(e){
      var pastedData = e.originalEvent.clipboardData.getData('text');
      
      setTimeout(function(){
        var data_code = $(".iti__selected-flag").eq(1).attr('data-code');
        var regx = new RegExp("^\\"+data_code,"g");
        var phone_number = pastedData.replace(regx,'');
        $("#phone_number").val(phone_number);
      },250);
    });
  }

  function fixWidthPhoneInput()
  {
    $(".iti").addClass('w-100');
  }

   function initAutoReply()
  {
    <?php if ($data['is_secure'] > 0) { ?> 
      $("#secureRadio").trigger("click");
			$(".secure-group").show();
    <?php } ?> 
  }

  function saveAutoReply()
  {
		$("body").on("click","#btn-save-autoreply",function(e){
			var data = $('#form-auto-reply').serializeArray();
			data.push(
				{name:'idlist', value:<?php echo $data['listid']; ?>,
			);
			e.preventDefault();
       $.ajax({
					headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					type : 'POST',
          url : '{{url("save-auto-reply")}}',
          data : data,
          dataType : "json",
          beforeSend: function()
          {
            $('#loader').show();
            $('.div-loading').addClass('background-load');
          },
          success : function(result){
             $('#loader').hide();
             $('.div-loading').removeClass('background-load');


             if(result.status == 'success')
             {
                $(".autoreply_error").html("<div class='alert alert-success'>"+result.message+"</div>");
             }
             else
             {
                $(".autoreply_error").html("<div class='alert alert-danger'>"+result.message+"</div>");
             }
            $("body .alert").delay(3000).fadeOut(2000); 
          },
          error: function(xhr)
          {
            $('#loader').hide();
            $('.div-loading').removeClass('background-load');
            console.log(xhr.responseText);
          }
      });		
		});
	}

  $(function () {   
      $("#divInput-description-post").emojioneArea();
  });
	
	var tempText="";
  function autoReplyButton()
  {
    $("body").on("click","#secureRadio",function(){
			$(".secure-group").show();
      tempText = $("#divInput-description-post").emojioneArea()[0].emojioneArea.getText();
      $("#divInput-description-post").emojioneArea()[0].emojioneArea.setText('Hi [NAME],'+"\n"+' Terima Kasih sudah mendaftar'+"\n"+'Langkah selanjutnya adalah :'+"\n"+'- Reply Chat ini klik [REPLY_CHAT]'+"\n"+'- Untuk menerima pesan klik > [START]'+"\n"+'- Untuk Unsubs klik > [UNSUBS]');
    });

    $("body").on("click","#standardRadio",function(){
			$(".secure-group").hide();
      $("#divInput-description-post").emojioneArea()[0].emojioneArea.setText(tempText);
    });
	}
	
  function open_ck_editor()
  {
      $(".showeditor").hide();
      $("#open_ck_editor").click(function(){
        $(".showeditor").slideToggle(1000);
      });
  }

  function display_edit_list_name()
  {
    $("#edit_list_name").click(function(){
      $("#display_edit_list_name").modal();
    });
  } 

  // Jquery Tabs
  function tabs() {    
      $('#tabs li a:not(:first)').addClass('inactive');
      $('.tabs-container').hide();
      $('.tabs-container:first').show();
      $("#tab1").addClass('inactive');
      $("#tab1C").hide();
      $("#tab3").removeClass('inactive');
      $("#tab3C").fadeIn('slow');

      $('#tabs li a').click(function(){
        var t = $(this).attr('id');
        if($(this).hasClass('inactive')){ //this is the start of our condition 
          $('#tabs li a').addClass('inactive');
          $(this).removeClass('inactive');

          $('.tabs-container').hide();
          var check = (".add-contact").length;
          clearField();

          $('#'+ t + 'C').fadeIn('slow');
        }
      });

      $("body").on('click','#tab-contact',function(){
        $("#tab1").addClass('inactive');
        $("#tab2").removeClass('inactive');

        $('.tabs-container').hide();
        $('#tab2C').fadeIn('slow');
      }); 

      $("body").on('click','#tab-form',function(){
         $("#tab1").addClass('inactive');
         $("#tab3").removeClass('inactive');

         $('.tabs-container').hide();
         $('#tab3C').fadeIn('slow');
      });
  }

   //ajax pagination
  function pagination()
  {
      $(".page-item").removeClass('active').removeAttr('aria-current');
      var mulr = window.location.href;
      getActiveButtonByUrl(mulr)
    
      $('body').on('click', '.pagination .page-link', function (e) {
          e.preventDefault();
          var url = $(this).attr('href');
          window.history.pushState("", "", url);
          loadPagination(url);
      });
  }

  function loadPagination(url) {
      $.ajax({
        beforeSend: function()
          {
            $('#loader').show();
            $('.div-loading').addClass('background-load');
          },
        url: url
      }).done(function (data) {
          $('#loader').hide();
          $('.div-loading').removeClass('background-load');
          getActiveButtonByUrl(url);
          $('#display_list').html(data);
      }).fail(function (xhr,attr,throwable) {
          $('#loader').hide();
          $('.div-loading').removeClass('background-load');
          alert("Sorry, Failed to load data! please contact administrator");
          console.log(xhr.responseText);
      });
  }

  function getActiveButtonByUrl(url)
  {
    var page = url.split('?');
    if(page[1] !== undefined)
    {
      var pagevalue = page[1].split('=');
      $(".page-link").each(function(){
         var text = $(this).text();
         if(text == pagevalue[1])
          {
            $(this).attr('href',url);
            $(this).addClass('on');
          } else {
            $(this).removeClass('on');
          }
      });
    }
    else {
        var mod_url = url+'?page=1';
        getActiveButtonByUrl(mod_url);
    }
  }

  //end ajax pagination

  // Display Country

  function delay(callback, ms) {
    var timer = 0;
    return function() {
      var context = this, args = arguments;
      clearTimeout(timer);
      timer = setTimeout(function () {
        callback.apply(context, args);
      }, ms || 0);
    };
  }

  function codeCountry()
  { 
    $("input[name='code_country']").click(function(){$("input[name='code_country']").val('');});

    $("body").on('keyup focusin',"input[name='code_country']",delay(function(e){
        $("input[name='code_country']").removeAttr('update');
        var search = $(this).val();
        $.ajax({
          type : 'GET',
          url : '{{ url("countries") }}',
          data : {'search':search},
          dataType : 'html',
          success : function(result)
          {
            $("#display_countries").show();
            $("#display_countries").html(result);
          },
          error : function(xhr)
          {
            console.log(xhr.responseText);
          }
        });
    },500));

    $("input[name='code_country']").on('focusout',delay(function(e){
        var update = $(this).attr('update');
        if(update == undefined)
        {
          $("input[name='code_country']").val('+62');
          $("#display_countries").hide();
        }
    },200));
  }

  function putCallCode()
  {
    $("body").on("click",".calling_code",function(){
      var code = $(this).attr('data-call');
      $("input[name='code_country']").attr('update',1);
      $("input[name='code_country']").val(code);
      $("#display_countries").hide();
    });
  }
  // End Display Country

  function Choose(){
    $("input[name=usertel]").prop('disabled',true);
    $(".ctel").hide();

    $(".dropdown-item").click(function(){
       var val = $(this).attr('id');

       if(val == 'ph')
        {
          $("input[name=phone_number]").prop('disabled',false);
          $("input[name=usertel]").prop('disabled',true);
          $(".cphone").show();
          $(".ctel").hide();
          $("#selectType").val("ph");
        }
        else {
          $("input[name=phone_number]").prop('disabled',true);
          $("input[name=usertel]").prop('disabled',false);
          $(".cphone").hide();
          $(".ctel").show();
          $("#selectType").val("tl");
        }
    });
  }


  function display_edit_customer_form()
  {
    $("body").on("click",".edit_customer",function(){
      var customer_id = $(this).attr('id');
      var name = $(this).attr('data-name');
      var last_name = $(this).attr('data-last_name');
      var email = $(this).attr('data-email');
      var phone = $(this).attr('data-phone');
      var code = $(this).attr('data-code');

      $("#change_btn").attr('data_update',customer_id);
      $("input[name='subscribername']").val(name);
      $("input[name='last_name']").val(last_name);
      $("input[name='email']").val(email);
      $("#edit_customer").modal();
      $(".current_phone_number").html(phone);
    });
  }

  function displayCustomer()
  {
     $.ajax({
      type : 'GET',
      url : '{{ url("list-table-customer") }}',
      data : {list_id : '{{ $id }}' },
      dataType : 'html',
      beforeSend: function()
      {
        $('#loader').show();
        $('.div-loading').addClass('background-load');
      },
      success : function(result)
      {
        $('#loader').hide();
        $('.div-loading').removeClass('background-load');
        $("#customer_list").html(result);
      },
      error: function(xhr)
      {
        $('#loader').hide();
        $('.div-loading').removeClass('background-load');
        console.log(xhr.responseText);
      }
     });
  }  

  function openImport() {
    $(".open_import").click(function(){
      $("#import-contact").modal();
    });
  }

  function cloneFile()
  {
    var clone;
    $("input[name='csv_file']").change(function(){
      // prevent double action
      fd.delete('csv_file');
      fd.delete('list_id_import');
      fd.delete('overwrite'); 
  
      var value = this.files;
      if(value.length > 0)
      {
        // clone = value[0].slice(0, value[0].size, value[0].type);
        fd.append("csv_file", value[0]);
        fd.append("list_id_import", '{{ $id }}');
      }
    });
  }

  function excelImportCheck()
  {
    $("body").on('submit','#importform',function(e){
        e.preventDefault();
        var data = new FormData($(this)[0]);
        $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
        });
        $.ajax({
            type : 'POST',
            url : "{{ url('import_excel_list_subscriber') }}",
            data : data,
            contentType: false,
            processData: false,
            beforeSend: function()
            {
              $('#loader').show();
              $('.div-loading').show().addClass('background-load');
            },
            success : function(result){
              $('.div-loading').hide().removeClass('background-load');
              $('#loader').hide();
              $('input[name="csv_file"]').val('');
    
              if(result.success == 1)
              {
                  $("#btn_close_import").trigger("click");
                  $(".main").html("<div class='alert alert-success'>"+result.message+"</div>");
                  $("body .alert-success").delay(5000).fadeOut(2000);
              }

              if(result.duplicate == 1)
              {   
                  $("#btn_close_import").trigger("click");
                  $(".duplicated").html("There is available phone on your xlsx file,<br/>Do you want to overwrite?");
        
                  $("#duplicate_phone").modal({
                      backdrop: 'static', 
                      keyboard: false
                  });
                  // console.log(data);
              }
              else
              {
                  excelImport(data);
              }
            },
            error: function (xhr, ajaxOptions, thrownError) {
              $('#loader').hide();
              $('.div-loading').removeClass('background-load');
             /* var err = eval("(" + xhr.responseText + ")");
              var msg = '';
              for ( var property in err.errors ) {
                msg += err.errors[property][0]+"\n"; // get message by object name
              }*/
              $(".error_notif").html('<div class="alert alert-danger">Error, sorry unable to import, maybe your csv file is corrupt or data unavailable</div>');
              $('input[name="csv_file"]').val('');
              displayCustomer();
            }
        });/* end ajax */
    });
  }

  function overWriteFile()
  {
    $("body").on('click','.overwrite',function(){
      var overwrite = $(this).attr('data-overwrite');
      $("#duplicate_phone").modal('hide');
      fd.append('overwrite',overwrite);
      excelImport(fd);
    });
  }

  function excelImport(data)
  {
      $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
      });
      $.ajax({
          type : 'POST',
          url : "{{ url('import_excel_list_subscriber_act') }}",
          data : data,
          contentType: false,
          processData: false,
          beforeSend: function()
          {
            $('#loader').show();
            $('.div-loading').show().addClass('background-load');
          },
          success : function(result){
            $('.div-loading').hide().removeClass('background-load');
            $('#loader').hide();
            $('input[name="csv_file"]').val('');
  
            if(result.success == 1)
            {
                $("#btn_close_import").trigger("click");
                displayCustomer();
                $(".main").html("<div class='alert alert-success'>"+result.message+"</div>");
                $("body .alert-success").delay(5000).fadeOut(2000);
            }
            else
            {   
                var errors = '';
                var errname, errphone, erremail;
                (result.name !== undefined)?errors+=result.name+"\n":errname='';
                (result.phone !== undefined)?errors+=result.phone+"\n":errphone='';
                (result.email !== undefined)?errors+=result.email:erremail='';
                $(".error_notif").html('<div class="alert alert-danger">'+errors+'</div>');

                if(result.message !== undefined)
                {
                    $(".error_notif").html("<div class='alert alert-danger'>"+result.message+"</div>");
                }
            }
          },
          error: function (xhr, ajaxOptions, thrownError) {
            $('#loader').hide();
            $('.div-loading').removeClass('background-load');
           /* var err = eval("(" + xhr.responseText + ")");
            var msg = '';
            for ( var property in err.errors ) {
              msg += err.errors[property][0]+"\n"; // get message by object name
            }*/
            $(".error_notif").html('<div class="alert alert-danger">Error, sorry unable to import, maybe your csv file is corrupt or data unavailable</div>');
            $('input[name="csv_file"]').val('');
            displayCustomer();
          }
      });/* end ajax */
  }

  function checkContact()
  {
    $(".add-contact").submit(function(e){
        e.preventDefault();
        var code_country = $(".iti__selected-flag").attr('data-code');
        var data_country = $(".iti__selected-flag").attr('data-country');
        var data = $(this).serializeArray();

        data.push(
          {name:'code_country', value:code_country},
          {name:'data_country', value:data_country},
          {name:'listedit',value:1}
        );
        customerAdding(data);
      });
  }

  function updateContact(){
    $(".update-contact").submit(function(e){
        e.preventDefault();
        var data_update = $("#change_btn").attr('data_update');
        var data_country = $(".iti__selected-flag").eq(1).attr('data-country');
        var code_country = $(".iti__selected-flag").eq(1).attr('data-code');
        var data = $(this).serializeArray();

         data.push(
            {name:'code_country', value:code_country},
            {name:'data_country', value:data_country},
            {name:'data_update',value:data_update}
         );
        customerAdding(data);
      });
  }

  function addContact()
  {
    $(".overwrite_contact").click(function(){
      var data_overwrite = $(this).attr('data-overwrite');
      var code_country = $(".iti__selected-flag").attr('data-code');
      var data_country = $(".iti__selected-flag").attr('data-country');

        var data = $('.add-contact').serializeArray();
        data.push(
          {name:'code_country', value:code_country},
          {name:'data_country', value:data_country},
          {name:'listedit',value:1},
          {name:'overwrite',value:1},
        );
        customerAdding(data);
    });
  }

  function customerAdding(data)
  {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
            type : "POST",
            url : "{{ route('savesubscriber') }}",
            data : data,
            beforeSend: function()
            {
              $('#loader').show();
              $('.div-loading').addClass('background-load');
            },
            success : function(result){
              $('#loader').hide();
              $('.div-loading').removeClass('background-load');
            
              if(result.duplicate == 1)
              {   
                  $("#btn_close_import").trigger("click");
                  $(".duplicated").html("This phone is available,<br/>Do you want to overwrite?");
        
                  $("#duplicate_phone_contact").modal({
                      backdrop: 'static', 
                      keyboard: false
                  });
                  // console.log(data);
              }

              if(result.success == true && result.update == true)
              {
                 $(".update_notif").html('<div class="alert alert-success text-center">'+result.message+'</div>')
                 $(".error").hide();
                 $(".alert-success").delay(3000).fadeOut(3000);
                 $('input[name="phone_number"]').val('');
                 $(".current_phone_number").html(result.newnumber)
                 displayCustomer();
              }
              else if(result.success == true)
              {
                  $("#duplicate_phone_contact").modal('hide');
                  $(".main").html('<div class="alert alert-success text-center">Your contact has been added.</div>')
                  // $(".main").html('<div class="alert alert-success text-center">'+result.message+'</div>')
                  clearField();
                  $(".error").hide();
                  displayCustomer();
              }  
              else
              {
                  $(".error").show();
                  $(".name").html(result.name);
                  $(".error_message").html(result.main);
                  $(".error_message").html(result.list);
                  $(".email").html(result.email);
                  $(".phone_number").html(result.phone);
                  $(".code_country").html(result.code_country);

                  /*if(result.message !== undefined){
                       $(".error_message").html('<div class="alert alert-danger text-center">'+result.message+'</div>');
                  }*/

                  $(".error").delay(2000).fadeOut(5000);
              }
            },
            error: function(xhr)
            {
               $('#loader').hide();
               $('.div-loading').removeClass('background-load');
               console.log(xhr.responseText);
            }
        });
        /*end ajax*/
  }

  function clearField()
  {
      $('input[name="subscribername"],input[name="phone_number"],input[name="email"]').val("");
  }

  /* Column Additional */

  var limit = 'You only can create 5 fields only';

    function displayAdditional(){
        $.ajax({
            type : 'GET',
            data : {'id': {!! $id !!}},
            url : "{{route('additionalList')}}",
            dataType : "json",
            success : function(result){
               var box_html = '';
               var is_option = {};
               var options = '';

               $.each(result.additional,function(key, value){
                  var len = key;
                  // dropdown
                  if(value.is_field == 1 && value.id_parent == 0)
                  {

                    box_html += '<div class="form-group col-md-8 pos-'+len+' dropdown">';
                    box_html += '<input field="1" id='+value.id+' pos="'+value.id+'" class="cidlen colfields dropfields form-control" value="'+value.name+'" />';
                    box_html += '</div>';
                    box_html += '<div class="form-group col-md-3 pos-'+len+'">';
                    box_html += '<a class="btn btn-form edit-option" id="'+value.id+'" list_id = '+value.list_id+'>Edit Option</a>';
                    box_html += '</div>';
                    box_html += '<div class="form-group col-md-1 pos-'+len+'">';
                    box_html += '<a id="'+len+'" class="del_fields btn btn-form" idbase = '+value.id+' listid = '+value.list_id+'><span class="icon-delete"></span></a>';
                    box_html += '</div>';

                   /*box_html += '<div class="col-md-9 row dropdown pos-'+len+'"><input field="1" id='+value.id+' pos="'+value.id+'" class="cidlen colfields dropfields form-control col-sm-6" value="'+value.name+'" /><a id="'+len+'" class="del_fields mb-2 col-sm-3 btn btn-warning" idbase = '+value.id+' listid = '+value.list_id+'>Delete</a><a class="btn btn-info col-sm-2 mb-2 btn-sm edit-option" id="'+value.id+'" list_id = '+value.list_id+'>Edit Option</a></div>';
                   */
                  }

                  if(value.is_field == 0 && value.id_parent == 0)
                  {

                    box_html += '<div class="form-group col-md-8 pos-'+len+'">';
                    box_html += '<input field="0" id='+value.id+' name="field[]" class="cidlen form-control fields colfields pos-'+len+'" value="'+value.name+'" />';
                    box_html += '</div>';
                    box_html += '<div class="form-group col-md-3 pos-'+len+'">';
                    box_html += '<select name="is_option[]" class="is_option pos-'+len+' form-control selopt-'+len+'"><option value="0">Optional</option><option value="1">Require</option></select></div>';
                    box_html += '</div>';
                    box_html += '<div class="form-group col-md-1 pos-'+len+'">';
                    box_html += '<a id="'+len+'" class="pos-'+len+' del_fields btn btn-form" idbase = '+value.id+' listid = '+value.list_id+'><span class="icon-delete"></span></a>';
                    box_html += '</div>';

                     /*box_html += '<div class="col-md-3 text-md-right pos-'+len+'"></div><div class="col-md-9 row pos-'+len+'"><input field="0" id='+value.id+' name="field[]" class="cidlen form-control mb-2 col-md-6 fields colfields pos-'+len+'" value="'+value.name+'" /><a id="'+len+'" class="del_fields pos-'+len+' mb-2 col-md-2 btn btn-warning" idbase = '+value.id+' listid = '+value.list_id+'>Delete</a><select name="is_option[]" class="is_option pos-'+len+' form-control col-md-3 selopt-'+len+'"><option value="0">Optional</option><option value="1">Require</option></select></div>';
                       is_option[len] = value.is_optional;
                    */
                    is_option[len] = value.is_optional;
                  }  
                 
               });

               $("#additional").html(box_html);
               $.each(is_option,function(key, value){
                  $(".selopt-"+key+"").val(value);
               });
               //CKEDITOR.instances.editor1.setData( result.content );
               var clen = $(".fields").length;
                if(clen == 0)
                {
                  $("#cid").hide();
                } else {
                  $("#cid").show();
                }
            }
        });
    }

    /* EDIT OR UPDATE LIST */
    function updateList(){
        $("#edit_list").submit(function(e){
            e.preventDefault();
             var databutton = $("input[name='page_position']").val(); // get data button position
             databutton = parseInt(databutton) -1;
            
             var fields = $(".fields");
             var isoption = $(".is_option");
             var dropfields = $(".dropfields");
             var datafields = {};
             var datadropfields = {};

             //fields
             for(i=0;i<fields.length;i++)
             {  
                var values = fields.eq(i).val();
                var idfields = fields.eq(i).attr('id');
                var fieldoption = isoption.eq(i).val();
                datafields[i] = {field:values, idfield : idfields, isoption : fieldoption};
             }

             //dropfields
             for(j=0;j<dropfields.length;j++)
             {  
                var dropvalues = dropfields.eq(j).val();
                var dropid = dropfields.eq(j).attr('id');
                datadropfields[j] = {field:dropvalues, idfield : dropid};
             }

             // all data
             var data = {
                id : {!! $id !!},
                label_name : $("input[name='label_name']").val(),
                label_last_name : $("input[name='label_last_name']").val(),
                label_phone : $("input[name='label_phone']").val(),
                label_email : $("input[name='label_email']").val(),
                checkbox_email : $("input[name='checkbox_email']").val(),
                checkbox_lastname : $("input[name='checkbox_lastname']").val(),
                button_rename : $("input[name='button_rename']").val(),
                editor : CKEDITOR.instances.editor1.getData(),
                pixel : $("textarea[name='pixel']").val(),
                conf_message : CKEDITOR.instances.editor2.getData(),
                fields : datafields,
                dropfields : datadropfields,
             };

            $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  }
            });
             $.ajax({
                type : 'POST',
                url : '{{route("listupdate")}}',
                data : data,
                dataType : "json",
                beforeSend: function()
                {
                  $('#loader').show();
                  $('.div-loading').addClass('background-load');
                },
                success : function(result){
                   $('#loader').hide();
                   $('.div-loading').removeClass('background-load');

                   if(result.error == undefined)
                   {
                      $(".list_label").html('');
                      $(".alerts").html('<div class="alert alert-success mt-2">'+result.message+'</div>');
                      $(".error").hide();
                      displayAdditional();
                      $(".alerts").delay(2000).fadeOut(3000);
                   }
                   else if(result.additionalerror == false)
                   {
                      $(".alerts").html('<div class="alert alert-success mt-2">'+result.message+'</div>');
                   }
                   else
                   {
                      $(".error").show();
                      $(".label_name").html(result.label_name);
                      $(".label_phone").html(result.label_phone);
                      $(".label_email").html(result.label_email);
                      $(".button_rename").html(result.button_rename);
                      $(".message_conf").html(result.conf_message);

                      if(result.additionalerror == true)
                      {
                        $(".alerts").html('<div class="alert alert-danger">'+result.message+'</div>');
                      }
                   }
                },
                error: function(xhr,attr,throwable)
                {
                   $('#loader').hide();
                   $('.div-loading').removeClass('background-load');
                   console.log(xhr.responseText);
                }
            });

        });
    }

    function openAdditional()
    {
       $("#cid").click(function(){
          var listid = $("input[name='idlist']").val();
          $("input[name='listidaddt']").val(listid);
          $("#editFields").modal();
       });
    }

     /* Datetimepicker */
     $("body").on('focus','.evd',function () {
          $('#datetimepicker').datetimepicker({
            format : 'YYYY-MM-DD HH:mm',
          });
      });

     /* Fix bootstrap modal if stuck after open another modal */
     function fixModal()
     {
      $(document).find('.child-modal').on('hidden.bs.modal', function () {
          //console.log('hiding child modal');
          $('body').addClass('modal-open');
      });
     }

    /* RESTORE HTML AFTER UPDATE OR DELETE */
    function displayAjaxCols(id)
    {
      var box_html = '';
      var is_option = {};
      $.ajax({
        type : 'GET',
        url : '{{route("displayajaxfield")}}',
        data : {'id':id},
        dataType : "json",
        success : function(result){
            if(result.additional !== null)
            {
                $.each(result.additional,function(key, value){
                var len = key;
                      // DROPDOWN
                      if(value.is_field == 1 && value.id_parent == 0)
                      {
                        box_html += '<div class="col-md-9 row dropdown pos-'+len+'"><input field="1" id='+value.id+' pos="'+value.id+'" class="cidlen colfields dropfields form-control col-sm-6" value="'+value.name+'" /><a id="'+len+'" class="del_fields mb-2 col-sm-3 btn btn-warning" idbase = '+value.id+' listid = '+value.list_id+'>Delete</a><a class="btn btn-info col-sm-2 mb-2 btn-sm edit-option" id="'+value.id+'" list_id = '+value.list_id+'>Edit Option</a></div>';
                      }

                      /*option
                      if(value.is_field == 0 && value.id_parent > 0)
                      {
                        box_html += '<div class="col-md-9 row hiddendropdown togglepos-'+value.id_parent+'"><input id='+value.id+' pos="'+len+'" class="fields pos-'+len+' form-control col-sm-6 float-left dropdownopt" value="'+value.name+'" /><a id="'+len+'" class="del_fields pos-'+len+' mb-2 col-sm-3 btn btn-warning" idbase = '+value.id+' listid = '+value.list_id+'>Delete</a></div><div class="clearfix"></div>';
                      }*/

                      if(value.is_field == 0 && value.id_parent == 0)
                      {
                           box_html += '<div class="col-md-3 text-md-right pos-'+len+'"></div><div class="col-md-9 row pos-'+len+'"><input field="0" id='+value.id+' name="field[]" class="cidlen form-control mb-2 col-md-6 fields colfields pos-'+len+'" value="'+value.name+'" /><a id="'+len+'" class="del_fields pos-'+len+' mb-2 col-md-2 btn btn-warning" idbase = '+value.id+' listid = '+value.list_id+'>Delete</a><select name="is_option[]" class="is_option pos-'+len+' form-control col-md-3 selopt-'+len+'"><option value="0">Optional</option><option value="1">Require</option></select></div>';
                           is_option[len] = value.is_optional;
                      }  

                 });

                 $("#additional").html(box_html);
                 //to make is optionnal choosen according on DB
                 $.each(is_option,function(key, value){
                      $(".selopt-"+key+"").val(value);
                 });
            }
        }
      });  
     
    }

    /* EDIT DROPDOWN OPTIONS */
    function editOption()
    {
      $("body").on("click",".edit-option",function(){
         var id = $(this).attr('id');
         var box_html = '';

         $("#editDropdown").modal();
         $("input[name='parent_id']").val(id);
         $("input[name='list_id']").val({!! $id !!});

         $.ajax({
            type : 'GET',
            url : '{{route("editdropfields")}}',
            data : {'id':id},
            dataType : 'json',
            success : function(result)
            {
               $.each(result.dropfields,function(key, value){
                  var len = key;
                  box_html += '<input id='+value.id+' class="dropdownopt form-control mb-2 col-sm-9 float-left doption opt-'+len+'" value="'+value.name+'" />';
                  box_html += '<a id="opt-'+len+'" class="del_fields mb-2 col-sm-2 btn btn-danger deloption" idbase = '+value.id+' listid = '+value.list_id+'><span class="icon-delete"></span></a>';
                  /*
                  box_html += '<input id='+value.id+' class="dropdownopt form-control mb-2 col-sm-8 float-left doption opt-'+len+'" value="'+value.name+'" /><a id="opt-'+len+'" class="del_fields mb-2 col-sm-3 btn btn-warning deloption" idbase = '+value.id+' listid = '+value.list_id+'>Delete</a>';
                  */
                });
                $("#editoptions").html(box_html);
            }
         });

      });
    }

    /* ADD DROPDOWN OPTIONS */
    function addOption()
    {
        $("body").on("click",".add-edit-option",function(){
            var len = $(".doption").length;
            var dropdownOptions = '<input class="newoption form-control mb-2 col-sm-9 float-left doption opt-'+len+'" /><a id="opt-'+len+'" class="deloption mb-2 col-sm-2 btn btn-danger"><span class="icon-delete"></span></a>';

            $("#editoptions").append(dropdownOptions);
        });
    }

    /* SAVE DROPDOWN OPTIONS TO DATABASE */
    function insertOption()
    {
        $("body").on("submit","#optionform",function(e){
          e.preventDefault();
          var dataedit = {};
          var dlen = $(".dropdownopt");
          var values = [];
          var id = [];
          var parent_id = $("input[name='parent_id']").val();
          var list_id = $("input[name='list_id']").val();
          var newopt = $(".newoption");
          var data = [];
  
          for(i=0;i<dlen.length;i++)
          {
            values[i] = dlen.eq(i).val();
            id[i] = dlen.eq(i).attr('id');
          }

          for(j=0;j<newopt.length;j++)
          {
            data[j] = newopt.eq(j).val();
          }

          dataedit = {'editid':id, 'values':values, 'parent_id':parent_id, 'list_id':list_id, 'data':data};

          $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          });
          $.ajax({
            type : 'POST',
            url : '{{route("insertoptions")}}',
            data : dataedit,
            dataType : 'json',
            beforeSend: function()
            {
              $('#loader').show();
              $('.div-loading').addClass('background-load');
            },
            success : function(response)
            {
              $('#loader').hide();
              $('.div-loading').removeClass('background-load');

              alert(response.msg);
              //displayAjaxCols(response.listid);
              displayAdditional();
            }
          });

        });
    }

    /* DELETE DROPDOWN OPTIONS */
    function delOption()
    {
      $("body").on("click",".deloption",function(){
          var opt = $(this).attr('id');
          $('#'+opt).remove();
          $('.'+opt).remove();
      });
    }

    /* ADD FIELD */
    function addCols(){
      $("body").on('click','.add-field',function(){
        var type = $("#type_fields").val();
        var len = $(".colfields").length;
        $("input[name='field_list'], input[name='dropdownlist']").val({!! $id !!});
       
        if(type == 1)
        {
            //$("#cid").show();
            $("#openFields").modal();
        } 
        else {
             $("#openDropdown").modal();
        }

        /*
        if(len < 5 && type == 1)
        {
            //$("#cid").show();
            $("#openFields").modal();
        } 
        else if(len < 5 && type == 2) {
            $("#openDropdown").modal();
        }
        else 
        {
            alert(limit);
        }
        */
      });
    } 


    function addFields()
    {
       $("body").on('click','.add-field-column',function(){
           var len = $(".colfields").length;
           var box_html = '';

           box_html += '<div class="col-md-12 row field-pos-'+len+' field-col">';
           box_html += '<input name="fields[]" class="cidlen form-control mb-2 col-md-6 colfields fieldinput field-pos-'+len+'" />';
           box_html += '<select class="field-pos-'+len+' form-control col-md-3 field-col" name="is_option[]"><option value="0">Optional</option><option value="1">Require</option></select>';
           box_html += '<a id="field-pos-'+len+'" class="del_fields field-col mb-2 col-md-2 btn btn-danger field-pos-'+len+'"><span class="icon-delete"></span></a>';
           box_html += '</div>';
           /*
           box_html = '<div class="col-md-12 row field-pos-'+len+' field-col"><input name="fields[]" class="cidlen form-control mb-2 col-md-6 colfields fieldinput field-pos-'+len+'" /><a id="field-pos-'+len+'" class="del_fields field-col mb-2 col-md-2 btn btn-warning field-pos-'+len+'">Delete</a><select class="field-pos-'+len+' form-control col-md-3 field-col" name="is_option[]"><option value="0">Optional</option><option value="1">Require</option></select></div>';
           */

          if(len < 5)
          {
              $("#append_fields").append(box_html);
          } 
          else 
          {
              alert(limit);
          }
          
       });
    }

    /* SAVE FIELDS */
    function insertFields()
    {
        $("body").on("submit","#addFieldsForm",function(e)
        {
            e.preventDefault();
            var data = $(this).serialize();
            var len = $(".colfields").length;
            var inputlen = $(".fieldinput").length;
            var valid = [];
            var duplicated = 0;

            var check = $(".colfields");
            check.each(function(i, result){
               valid.push($(result).val());
            });

            var recipientsArray = valid.sort(); 
            var reportRecipientsDuplicate = [];
            for (var i = 0; i < recipientsArray.length - 1; i++) {
                if (recipientsArray[i + 1] == recipientsArray[i]) {
                    duplicated = 1;
                }
            }

            if(len > 5)
            {
               alert(limit);
            }
            else if(inputlen < 1)
            {
               alert("You should create at least 1 input");
            }
            else if(duplicated == 1)
            {
               alert("Input field cannot be same");
            }
            else 
            {
                $("#cfd").html("Loading...");
                $.ajaxSetup({
                      headers: {
                          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                      }
                });
                $.ajax({
                    type : 'POST',
                    url : '{{route("insertfields")}}',
                    data : data,
                    dataType : "json",
                    beforeSend: function()
                    {
                      $('#loader').show();
                      $('.div-loading').addClass('background-load');
                    },
                    success : function(result){
                      $("#cfd").html("Create New Fields");
                      $('#loader').hide();
                      $('.div-loading').removeClass('background-load');
                      if(result.error == false)
                      {
                           if(result.listid.length > 0)
                            {
                               //displayAjaxCols(result.listid);
                               displayAdditional();
                            }
                      }
                     
                      if(result.error == true)
                      {
                        $(".errfield").html('<div class="alert alert-danger">'+result.msg+'</div>');
                      } else {
                        $(".field-col").remove();
                        alert(result.msg);
                      }
                    }
                });
            }
            
        });
    }

    /* ADD DROPDOWN */
    function addDropdown()
    {
        $("body").on("click",".add-option",function(){
            var flen = $(".colfields").length;
            var len = $(".doption").length;
            var checkdropdown = $("input[name='dropdowname']").val();
            var valid = 1;

            var check = $(".colfields");
            check.each(function(i, result){
                if($(result).val() == checkdropdown)
                {
                    valid = 0;
                }
                //console.log($(result).val());
            });

            var dropdown = '<input name="doptions[]" class="form-control mb-2 col-sm-9 float-left doption opt-'+len+'" /><a id="opt-'+len+'" class="deloption mb-2 col-sm-2 btn btn-warning"><span class="icon-delete"></span></a>';

            if(flen < 5 && valid == 1 && checkdropdown.length > 0)
            {
                $("#appendoption").append(dropdown);
            } 
            else if(checkdropdown.length == 0)
            {
                alert('Field cannot be empty');
            } 
            else if(valid == 0) 
            {
                alert('Field value cannot be same');
            }
            else 
            {
                alert(limit);
            }
        });
    }

    /* SAVE DROPDOWN */
    function insertDropdown()
    {
         $("body").on("click","#cdp",function(e){
            e.preventDefault();
            var len = $(".colfields").length;
            var data = $("#dropdownForms").serialize();
            var opt = $(".doption").length;
            if(len >= 5)
            {
                alert(limit);
            }
            else if(opt < 1)
            {
                alert('You should create at least 1 input');
            }
            else
            {
              $("#div-loading").show();
              $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
                });
              $.ajax({
                  type : 'POST',
                  url : '{{route("insertdropdown")}}',
                  data : data,
                  dataType : 'json',
                  success : function(response)
                  {
                      $("#div-loading").hide();
                      alert(response.msg);
                      $(".doption, .deloption").remove();

                      if(response.listid.length > 0)
                      {
                         //displayAjaxCols(response.listid);
                         displayAdditional();
                      }
                  }
                });
            }
         });
    }

    function displayDropdownMenu()
    {
        $("body").on("click",".toggledropdown",function(){
            var id = $(this).attr('pos');
            $(".togglepos-"+id).slideToggle();
        });
    }


    /* DELETE FIELDS */
    function delCols(){
      $("body").on("click",".del_fields",function(){
        var len = $(".cidlen").length;
        var pos = $(this).attr('id');
        var id_attribute = $(this).attr('idbase');
        var listid = $(this).attr('listid');

        if(id_attribute !== undefined && listid !== undefined)
        {
          var conf = confirm('Are you sure want to delete this fields?');
          if(conf == true)
            {
              $.ajax({
                type : 'GET',
                url : '{{route("delfield")}}',
                data : {'id':id_attribute, 'list_id':listid},
                beforeSend: function()
                {
                  $('#loader').show();
                  $('.div-loading').addClass('background-load');
                },
                success : function(response){
                  $('#loader').hide();
                  $('.div-loading').removeClass('background-load');

                  alert(response.msg);
                  //displayAjaxCols(response.listid);
                  displayAdditional();
                }
              });
            } 
            else 
            {
              return false;
            }
        } else {
          $("."+pos).remove();
          $("#"+pos).remove();
          $(".pos-"+pos).remove();
        }

      });
    }  

    function copyLink(){
      $( ".btn-copy" ).click(function(e) 
      {
        e.preventDefault();
        e.stopPropagation();

        var link = $(this).attr("data-link");

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

  function changeListName()
  {
    $("#list_name").click(function(){
        var list_label = $("input[name='list_label']").val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
          type : 'POST',
          url : '{{url("changelistname")}}',
          data : {id : {!! $data['listid'] !!}, list_name : list_label},
          dataType : 'json',
          beforeSend: function()
          {
            $('#loader').show();
            $('.div-loading').addClass('background-load');
          },
          success : function(result)
          {
            $('#loader').hide();
            $('.div-loading').removeClass('background-load');
            //alert(result.response);
            if(result.status == 'success')
            {
              $(".listname").html(list_label);
              $("#display_edit_list_name").modal('hide');
            }
            else
            {
              $(".error").show();
              $(".list_label").html(result.response);
            }
          },
          error : function(xhr){
            $('#loader').hide();
            $('.div-loading').removeClass('background-load');
            console.log(xhr.responseText);
          }
        });
    });
  }

  function dataCustomer()
  {
    $("#data-customer").DataTable({
        "pageLength": 5
    });
  }

  function customerAttribute()
  {
    $(".view").click(function(){
      var attribute = $(this).attr('additional');
      var box = '';
      $("#display_attribute").modal();

      $.each( jQuery.parseJSON(attribute), function( key, value ) {
          box += '<div class="form-group row">';
          box += '<label class="col-sm-4 col-form-label">'+key+'</label>';
          box += '<div class="form-control form-control-sm col-sm-6 col-form-label">'+value+'</div>';
          box += '</div>';
      });
      $("#customer_additional").html(box);
    });
  }

  function delCustomer()
  {
      $("body").on("click",".del-customer",function(){
        var url = window.location.href;
        var id = $(this).attr('id');
        var warning = confirm('Are you sure to delete this customer?');

        if(warning == true)
        {
          $.ajax({
            type : 'GET',
            url : '{{ url("list-delete-customer") }}',
            data : {id_customer : id, list_id : {!! $data['listid'] !!}},
            dataType : 'json',
            beforeSend: function()
            {
              $('#loader').show();
              $('.div-loading').show().addClass('background-load');
            },
            success : function(response)
            {
              $('.div-loading').hide().removeClass('background-load');
              $('#loader').hide();
              $(".del_message").html('<div class="alert alert-success text-center">'+response.message+'</div>');
              if(response.success == 1)
              {
                  /*$('#loader').show();
                  $('.div-loading').addClass('background-load');*/
                  displayCustomer(); 
              }
              $("body .alert-success").delay(5000).fadeOut(2000);
            },
            error: function(xhr)
            {
              $('#loader').hide();
              $('.div-loading').removeClass('background-load');
              console.log(xhr.responseText);
            }
          })
        }
        else
        {
            return false;
        }

      });
  }

	function copyToClipboard(elem) {
			// create hidden text element, if it doesn't already exist
			var targetId = "_hiddenCopyText_";
			var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
			var origSelectionStart, origSelectionEnd;
			if (isInput) {
					// can just use the original source element for the selection and copy
					target = elem;
					origSelectionStart = elem.selectionStart;
					origSelectionEnd = elem.selectionEnd;
			} else {
					// must use a temporary form element for the selection and copy
					target = document.getElementById(targetId);
					if (!target) {
							var target = document.createElement("textarea");
							target.style.position = "absolute";
							target.style.left = "-9999px";
							target.style.top = "0";
							target.id = targetId;
							document.body.appendChild(target);
					}
					target.textContent = elem.textContent;
			}
			// select the content
			var currentFocus = document.activeElement;
			target.focus();
			target.setSelectionRange(0, target.value.length);
			
			// copy the selection
			var succeed;
			try {
					succeed = document.execCommand("copy");
			} catch(e) {
					succeed = false;
			}
			// restore original focus
			if (currentFocus && typeof currentFocus.focus === "function") {
					currentFocus.focus();
			}
			
			if (isInput) {
					// restore prior selection
					elem.setSelectionRange(origSelectionStart, origSelectionEnd);
			} else {
					// clear temporary content
					target.textContent = "";
			}
			return succeed;
	}
	
	function buttonGenerateGoogleScript(){
		$("body").on("click","#btn-generate",function(e){
			// var tempInput = document.createElement("input");
			// tempInput.style = "position: absolute; left: -1000px; top: -1000px";
			// tempInput.value = $("#text-google-script").html();
			// document.body.appendChild(tempInput);
			// tempInput.select();
			// document.execCommand("copy");
			// document.body.removeChild(tempInput);
			copyToClipboard(document.getElementById("text-google-script"));
			$('#copy-script').modal('show');
		});
	}
		
  /*
  function radioCheck(){
      $("#tab2, #tab-contact").click(function(){
        $(".move_radio").prependTo($(".prep1"));
      });

      $("#tab3, #tab-form").click(function(){
        $(".move_radio").prependTo($(".prep2"));
      });
  }
  */

</script>

@endsection
