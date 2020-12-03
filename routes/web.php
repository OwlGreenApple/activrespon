<?php
 
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('logs-0312', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

Route::get('/','OrderController@pricing')->name('pricing');

//Route::get('ck','ListController@generateRandomListName');
Route::get('preg', 'ApiController@test');

Route::get('send','SenderController@getDeviceId');
Route::get('justcarbon','EventController@JUSTCARBON');

/* API accessed from WP */
Route::post('send-message-queue-system-wp-activtemplate','ApiWPController@send_message_queue_system_WP_activtemplate');
Route::post('send-message-queue-system-wp-celebfans','ApiWPController@send_message_queue_system_WP_celebfans');
Route::post('send-message-queue-system-wp-activflash','ApiWPController@send_message_queue_system_WP_activflash');
Route::post('send-message-queue-system-wp-digimaru','ApiWPController@send_message_queue_system_WP_digimaru');
Route::post('send-message-queue-system-wp-ms','ApiWPController@send_message_queue_system_WP_ms');
Route::post('send-message-queue-system-wp-michaelsugiharto','ApiWPController@send_message_queue_system_WP_michaelsugiharto');

/* API */
Route::post('entry-google-form','ApiController@entry_google_form');
Route::get('testcoupon','ApiController@testcoupon');
Route::get('testmail','ApiController@testmail');
Route::get('testpay','ApiController@testpay');
Route::get('testdirectsendwa','ApiController@testDirectSendWA')->name('testdirectsendwa');
Route::get('testdirectsendmail','ApiController@testDirectSendMail')->name('testdirectsendmail');
Route::post('send-message-queue-system','ApiController@send_message_queue_system');

/* API accessed from automation*/
Route::post('send-simi','ApiController@send_simi');
Route::post('send-message-automation','ApiController@send_message');
Route::post('send-wamate','ApiController@send_wamate');
Route::post('send-image-url-wamate','ApiController@send_image_url_wamate');
Route::post('send-image-url-simi','ApiController@send_image_url_simi');
Route::post('send-image-url','ApiController@send_image_url');
Route::post('send-message-wassenger-automation','ApiController@send_message_wassenger_automation');
Route::post('restart-simi','ApiController@restart_simi');

Route::post('is_pay','ApiController@customerPay');
Route::post('private-list','ApiController@register_list');

Route::get('pricing','OrderController@pricing')->name('pricing');
Route::get('summary','OrderController@summary');
Route::get('checkout/{id}','OrderController@checkout');
Route::get('thankyou','OrderController@thankyou');
Route::post('/check-coupon','OrderController@check_coupon');
// Route::post('/submit-checkout-register','OrderController@submit_checkout_register');
Route::post('/submit-checkout-login','OrderController@submit_checkout_login'); //new system to summary
Route::post('/submit-checkout','OrderController@submit_checkout'); //new system to summary
Route::post('/submit-summary','OrderController@submit_summary'); //new system to summary

/* PROTOTYPE */
//Route::get('createlists', 'HomeController@formList');
//Route::get('lists-create', 'HomeController@createList');
//Route::get('lists', 'HomeController@dataList');
Route::get('add-message-event/{campaign_id}', 'CampaignController@addMessageEvent');
Route::get('add-message-auto-responder/{campaign_id}', 'CampaignController@addMessageAutoResponder');
Route::get('report-reminder', 'HomeController@reportReminder');
Route::get('history-order', 'HomeController@historyOrder');

/* User Customer */
Route::post('loginajax', 'Auth\LoginController@loginAjax');// user login via ajax
Route::post('updateuser', 'HomeController@updateUser')->name('updateuser');

/* WA webhook */
// Route::get('sethook','ChatsController@setWebhook');
Route::get('test_webhook','ChatsController@testWebhook');
Route::post('get-webhook','ChatsController@getWebhook');

/* Admin Woowa*/
Route::group(['middleware'=>['auth','web','is_admin_woowa']],function(){
  Route::get('/list-woowa',function(){
    return view('admin.list-woowa.index');
  });
  Route::get('/list-woowa/load-woowa','Admin\WooWAController@load_woowa');
  Route::post('/list-woowa/create-invoice','Admin\WooWAController@create_invoice');

  Route::get('/list-invoice',function(){
    return view('admin.list-woowa-invoice.index');
  });
  Route::get('/list-invoice/load','Admin\WooWAController@load_invoice');
  Route::get('/list-invoice/load-invoice-order','Admin\WooWAController@load_invoice_order');
	Route::post('/list-invoice/confirm','Admin\WooWAController@confirm_invoice');
});
	
/* Admin */
Route::group(['middleware'=>['auth','web','is_admin']],function(){
	/*Route::get('sendingrate', 'AdminController@SendingRate');
  Route::post('savesettings', 'AdminController@SaveSettings');
	Route::get('csvimport', 'AdminController@importCSVPage')->name('csvimport');
	Route::post('importcustomercsv','AdminController@importCustomerCSV')->name('importcustomercsv');*/
  Route::get('superadmin', 'AdminController@index');
  Route::get('configs', 'AdminController@config');
  Route::get('status-server', 'AdminController@changeStatusServer');
  Route::get('setupconfig', 'AdminController@setupConfig');
  Route::post('save-config', 'AdminController@saveConfig');
  Route::get('config-show', 'AdminController@displayConfig');
  Route::get('loginuser/{id_user}', 'AdminController@LoginUser');
  Route::get('broadcast-admin','AdminController@BroadcastAdmin');
  Route::post('broadcast-user','AdminController@BroadcastUser');
  Route::get('country-code','AdminController@InsertCountry');
  Route::get('country-show','AdminController@showCountry');
  Route::get('country-del','AdminController@delCountry');
  Route::post('save-country','AdminController@saveCountry')->middleware('check_country');

  /* Spiderman */
  Route::get('connect-system','SpidermanController@index');
  Route::get('start','SpidermanController@start');
  Route::get('scan','SpidermanController@scan');
  Route::get('statusmessage','SpidermanController@status');
  Route::post('sendmessage','SpidermanController@sendMessage');
  /* -- */

  //List User 
  Route::get('/list-user','Admin\UserController@index');
  Route::get('/list-user/load-user','Admin\UserController@load_user');
  Route::get('/list-user/add-user','Admin\UserController@add_user');
  Route::get('/list-user/edit-user','Admin\UserController@edit_user');
  Route::get('list-user/view-log','Admin\UserController@load_log');
  Route::post('/import-excel-user','Admin\UserController@import_excel_user');
  
	//List Coupon
	Route::get('/list-coupon','CouponController@index');
	Route::get('/list-coupon/load-coupon','CouponController@load_coupon');
	Route::get('/list-coupon/add','CouponController@add_coupon');
	Route::get('/list-coupon/edit','CouponController@edit_coupon');
	Route::get('/list-coupon/delete','CouponController@delete_coupon');

	//admin order
  Route::get('/list-order',function(){
    return view('admin.list-order.index');
  });
  Route::get('/list-order/load-order','Admin\OrderController@load_list_order');
  Route::get('/list-order/confirm','Admin\OrderController@confirm_order');
  
  //list phone
  Route::get('/list-phone',function(){
    return view('admin.list-phone.index');
  });
  Route::get('/list-phone/load','Admin\PhoneController@load_phone');

  //list message system
  Route::get('/list-message-system','Admin\MessageController@index');
  Route::get('/list-message-system/load','Admin\MessageController@load_message_system');
  Route::get('/list-message-system/resend','Admin\MessageController@resend');
});

/* SETTING */
Route::group(['middleware'=>['auth','web']],function(){
  // Route::get('settings/{mod?}', 'SettingController@index');
  Route::get('settings', 'SettingController@index');
  Route::post('save-settings', 'SettingController@settingsUser')->middleware('usersettings');
  Route::get('load-phone-number', 'SettingController@load_phone_number');
  Route::get('signout', 'Auth\LoginController@logout');
	
	//woowa + simi 
  // Route::get('connect-phone', 'SettingController@connect_phone')->middleware('checkcall');
  Route::post('check-otp', 'SettingController@getOTP');
  Route::post('submit-otp', 'SettingController@submitOTP');
  Route::get('connect-phone', 'SettingController@connect_phone')->middleware('checkcall');
  Route::get('verify-phone', 'SettingController@verify_phone')->middleware('checkphone');
	
  Route::get('delete-phone', 'SettingController@delete_phone');
  Route::get('check-qr', 'SettingController@check_connected_phone');
  Route::get('delete-api/{no}', 'SettingController@delete_api');
  Route::get('status-nomor/{no}', 'SettingController@status_nomor');
  Route::get('get-qr-code/{no}', 'SettingController@get_qr_code');
  Route::get('qr-status/{no}', 'SettingController@qr_status');
  Route::get('take-screenshoot/{no}', 'SettingController@take_screenshot');
  Route::get('get-all-cust', 'SettingController@get_all_cust');
  Route::get('get-key/{no}', 'SettingController@get_key');
  Route::get('send-message-setting', 'SettingController@send_message');
  Route::get('send-image', 'SettingController@send_image_url');
  Route::get('test-send-message-temp', 'SettingController@test_send_message');
  // Route::post('edit-phone', 'SettingController@editPhone');
	
	//Orders 
	Route::get('/order','OrderController@index_order');
	Route::get('/order/load-order','OrderController@load_order');
  Route::get('get-status-upgrade','OrderController@getStatusUpgrade');
	Route::post('order-confirm-payment','OrderController@confirm_payment_order');

});
 
/*** USER ***/
Route::group(['middleware'=>['auth','web','authsettings']],function(){
	/* HOME */
	Route::get('/', 'HomeController@index')->middleware('cors')->name('home');
	Route::get('/home', 'HomeController@index')->middleware('cors')->name('home');
	Route::get('checkphone', 'HomeController@checkPhone');

  Route::get('google-form','HomeController@google_form');
  Route::get('jsonEncode','HomeController@jsonEncode');

  Route::get('stop-start','HomeController@stop_start');
  Route::get('change-speed','HomeController@change_speed');

	/* LIST */
  Route::get('lists', 'ListController@index');
  Route::get('lists-table', 'ListController@dataList');
  Route::get('list-form', 'ListController@formList');
  Route::get('list-create', 'ListController@createList');
  Route::post('list-save','ListController@saveList')->name('savelist'); 
  Route::get('list-delete','ListController@delListContent')->name('deletelist');
  Route::get('list-search','ListController@searchList')->name('searchlist');
  Route::get('list-edit/{list_id}/{mod?}','ListController@editList');
  Route::get('list-additional','ListController@additionalList')->name('additionalList');
  // Route::get('list-contacts/{list_id}','ListController@ListContacts');
  Route::get('list-table-customer','ListController@listTableCustomer');
  Route::get('list-delete-customer','ListController@deleteSubscriber');
  Route::post('list-update','ListController@updateListContent')->middleware('checkadditional')->name('listupdate');
  Route::post('list-duplicate','ListController@duplicateList')->name('duplicatelist');
  Route::post('import_excel_list_subscriber','ListController@importExcelListSubscribers')->middleware('checkimportcsv');
  Route::post('import_excel_list_subscriber_act','ListController@importExcelListSubscribersAct');
  Route::post('changelistname','ListController@changeListName');
  Route::get('export_excel_list_subscriber/{id_list}/{import}','ListController@exportListExcelSubscriber');
  Route::post('save-auto-reply','ListController@save_auto_reply');

  /* ADDITIONAL */
  Route::post('insertoptions','ListController@insertOptions')->name('insertoptions');
  Route::get('browseupload','ListController@browserUploadedImage')->name('browseupload');
  Route::get('editdropfields','ListController@editDropfields')->name('editdropfields');
  Route::post('insertfields','ListController@insertFields')->name('insertfields');
  Route::post('insertdropdown','ListController@insertDropdown')->name('insertdropdown');
  Route::get('delfield','ListController@delField')->name('delfield');
  Route::post('updateadditional','ListController@updateField')->name('updateadditional');
  Route::get('displayajaxfield','ListController@displayAjaxAdditional')->name('displayajaxfield');
  Route::get('customeradditional','ListController@customerAdditional')->name('customeradditional');

  /* CAMPAIGN */
  Route::get('campaign', 'CampaignController@index');
  Route::get('create-campaign', 'CampaignController@CreateCampaign');
  Route::post('send-test-message', 'CampaignController@sendTestMessage');
  Route::post('save-campaign', 'CampaignController@SaveCampaign');
  Route::get('campaign-del','CampaignController@delCampaign'); 
  Route::get('list-campaign/{id}/{isevent}/{active}','CampaignController@listCampaign'); 
  Route::get('list-broadcast-campaign','CampaignController@listBroadcastCampaign'); 
  Route::get('list-event-campaign','CampaignController@listEventCampaign'); 
  Route::get('list-delete-campaign','CampaignController@listDeleteCampaign'); 
  Route::get('list-datatable-campaign','CampaignController@listAutoSchedule'); 
  Route::post('edit-campaign-name','CampaignController@editCampaign'); 
  
  /* EVENT */
  Route::get('event-del','EventController@delEvent');
  Route::post('event-duplicate','EventController@duplicateEvent')->middleware('checkeventduplicate');
  Route::get('load-event','EventController@loadEvent');
  Route::get('delete-event','EventController@deleteEvent');
  Route::get('event','EventController@index');
  Route::get('create-event','EventController@createEvent');
  Route::get('display-event','EventController@loadAjaxEventPage');
  Route::get('event-publish','EventController@publishEvent');
  Route::get('event-list','EventController@displayEventList')->name('eventlist');
  Route::post('event-search','EventController@searchEvent');
  Route::post('edit-event-date','EventController@editEventDate');

  /* REMINDER */
  Route::get('reminder-list','ReminderController@displayReminderList')->name('reminderlist');
  Route::get('reminder-del','ReminderController@delReminder');
  Route::post('reminder-duplicate','ReminderController@duplicateReminder')->middleware('checkresponderduplicate');
  Route::get('load-auto-responder','ReminderController@loadAutoResponder');
  Route::get('delete-auto-responder','ReminderController@deleteAutoResponder');

  /* BROADCAST */
  Route::get('broadcast-list','BroadCastController@displayBroadCast')->name('broadcastlist'); 
  Route::get('broadcast-del','BroadCastController@delBroadcast'); 
  Route::get('broadcast-check','BroadCastController@checkBroadcastType'); 
  Route::post('broadcast-update','BroadCastController@updateBroadcast')->middleware('checkbroadcastduplicate'); 
  Route::post('broadcast-duplicate','BroadCastController@duplicateBroadcast')->middleware('checkbroadcastduplicate'); 

  /* APPOINTMENT */
  Route::get('create-apt','AppointmentController@createAppointment');
  Route::post('save-apt','AppointmentController@saveAppointment')->middleware('save_apt');
  Route::get('display-template-apt','AppointmentController@displayTemplateAppointment');
  Route::post('save-template-appoinments','AppointmentController@saveTemplateAppointment')->middleware('checkeditappt');
  Route::get('appointment','AppointmentController@index')->name('appointment');
  Route::get('list-apt/{id}/{active}','AppointmentController@listAppointment');
  Route::get('list-table-apt','AppointmentController@listTableAppointments');
  // Route::get('list-table-apt-inactiv','AppointmentController@listTableAppointmentInActive');
  Route::post('list-edit-apt','AppointmentController@listAppointmentEdit')->middleware('checkeditformappt');
  Route::get('list-delete-apt','AppointmentController@listAppointmentDelete');
  Route::get('form-apt/{id}','AppointmentController@formAppointment');
  Route::get('edit-apt/{id}','AppointmentController@editAppointment');
  Route::get('edit-appt-template','AppointmentController@editAppointmentTemplate');
  Route::get('delete-appt-template','AppointmentController@deleteAppointmentTemplate');
  Route::get('display-customer-phone','AppointmentController@displayCustomerPhone');
  Route::post('save-appt-time','AppointmentController@saveAppointmentTime')->middleware('checkformappt');
  Route::get('appt-del','AppointmentController@delAppointment');
  Route::get('export_csv_appt/{campaign_id}','AppointmentController@exportAppointment');

  //not used anymore (EVENT)
  ////////////////////////////////////////////////

  // auto reply event
  Route::get('eventautoreply','EventController@eventAutoReply')->name('eventautoreply');
  Route::post('addeventautoreply','EventController@addEventAutoReply')->name('addeventautoreply');
  Route::get('eventautoreplyturn/{id}/{status}','EventController@turnEventAutoReply');
  Route::get('eventstatus/{id}/{status}','EventController@setEventStatus');

	// reminder auto reply
	Route::get('reminderautoreply','ReminderController@reminderAutoReply')->name('reminderautoreply');
	Route::post('addreminderautoreply','ReminderController@addReminderAutoReply')->name('addreminderautoreply');

  /* CHATS */
  Route::get('chats','ChatsController@index');
  Route::get('get_all_chats','ChatsController@get_all_chats');
  Route::get('get_chat_messages','ChatsController@getChatMessages');
  Route::get('chat-members','ChatsController@chat_members');
  Route::get('get_media/{i}/{type}','ChatsController@getHTTPMedia');
  Route::get('get-notification','ChatsController@getNotification');
  Route::post('send_chat_message','ChatsController@sendMessage');
  Route::post('send_chat_media','ChatsController@sendMedia');

  /* RESEND */
  Route::get('resend_auto_eply','ListController@resendAutoReply');
  Route::get('resend_broadcast','BroadCastController@resendMessage');
  Route::get('resend_campaign','CampaignController@resendMessage');

	/* CKEditor */
	Route::get('ckbrowse', 'CKController@ck_browse')->name('ckbrowse');
	Route::get('ckdelete', 'CKController@ck_delete_image')->name('ckdelete');
	Route::post('ckupload', 'CKController@ck_upload_image')->name('ckupload');
});

/* Customers */
// Route::post('customer/add','CustomerController@addCustomer')->middleware('customer')->name('addcustomer');
//Route::post('customer/add','CustomerController@addCustomer')->name('addcustomer');
Route::post('subscriber/save','CustomerController@saveSubscriber')->middleware('customer')->name('savesubscriber');
Route::get('test-send-message','CustomerController@testSendMessage');
Route::get('test-code','CustomerController@testCode');
Route::get('link/activate/{list_name}/{customer_id}','CustomerController@link_activate');
Route::get('link/unsubscribe/{list_name}/{customer_id}','CustomerController@link_unsubscribe');

/* SUBSCRIBE */
Route::get('countries', 'CustomerController@Country');
Route::get('/{list_name}', 'CustomerController@subscriber');
//Route::get('/ev/{list_name}','CustomerController@event'); //register-customer.blade
//Route::get('/{list_name}','CustomerController@index'); //register-customer.blade
