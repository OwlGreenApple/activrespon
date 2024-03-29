<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use App\Customer;
use App\UserList;
use Carbon\Carbon;
use App\Reminder;
use App\ReminderCustomers;
use App\BroadCast;
use App\BroadCastCustomers;
use App\Sender;
use App\Additional;
use App\PhoneNumber;
use App\User;
use App\Server;
use App\Countries;
use App\Message;
use App\Utility;
use App\Province;
use App\Kabupaten;
use App\Console\Commands\SendWA as SendMessage;
use App\Helpers\ApiHelper;
use App\Rules\CheckWANumbers;
use App\Http\Controllers\ApiController as API;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiWPController;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Lang;
use Storage;

class CustomerController extends Controller 
{

    public function subscriber(Request $request, $link_list)
    {
      $check_link = UserList::where([
          ['name','=',$link_list],
          ['status','=',1],
      ])->first();

      if(empty($link_list))
      {
        return redirect('/');
      } 
      elseif(is_null($check_link)) 
      {
        return redirect('/');
      } 
      else 
      {
            $list = UserList::where('name',$link_list)->first();
            $additional = Additional::where('list_id',$list->id)->get();
            $data = array();
            $arr = array();
            $data['fields'] = array();

            if($additional->count() > 0)
            {
                foreach($additional as $row)
                {
                   if($row->id_parent == 0){
                        $data['fields'][] = $row;
                   } 
                }
            }

            if(count($data['fields']) > 0)
            {
                foreach($data['fields'] as $col)
                {
                     # count if name has child or not
                     $doption = Additional::where([['list_id',$list->id],['id_parent',$col->id]])->get();

                     $colname = $col->name;

                     if($doption->count() > 0)
                     {
                         foreach($doption as $rows)
                         {
                            $arr[(int)$col->is_optional][$colname][$col->is_field][] = $rows->name;
                         }
                     } 
                     else 
                     {
                            $arr[(int)$col->is_optional][$colname][$col->is_field] = $col;
                     }
                } 

            }

        // check if user status is 0
        $user = User::find($list->user_id);
        $status = $user->status;

        // UTILITIES
        $countries = $this->get_countries();
        $utils_hobbies = Utility::where([['user_id',$list->user_id],['id_category',2]])->get(); //hobby
        $utils_occupation = Utility::where([['user_id',$list->user_id],['id_category',3]])->get(); //pekerjaan

        $hobby = array();
        if($utils_hobbies->count() > 0)
        {
          foreach($utils_hobbies as $row)
          {
            $hobby[] = $row->id;
          }
        }

        //  if hobbies have descendant
        if(count($hobby) > 0)
        {
          $hobby = $this->extract_hobbies($hobby);
        }

        $utils_hobby = Utility::whereIn('id',$hobby)->get();

        $data = [
          'id'=>encrypt($list->id),
          'label_name'=>$list->label_name,
          'label_last_name'=>$list->label_last_name,
          'label_phone'=>$list->label_phone,
          'label_email'=>$list->label_email,
          'checkbox_email'=>$list->checkbox_email,
          'checkbox_lastname'=>$list->checkbox_lastname,
          'content'=>$list->content,
          'listname'=>$link_list,
          'pixel'=>$list->pixel_text,
          'additional'=>$arr,
          'btn_message'=>$list->button_subscriber,
          'link_add_customer'=>url($link_list),
          'status'=>$status,
          'utils_hobby'=>$utils_hobby,
          'utils_occupation'=>$utils_occupation,
          'religion'=>self::$religion,
          'lists'=>$list,
          'countries'=>$countries,
          'gender'=>self::$gender,
          'marriage'=>self::$marriage 
        ];

        return view('register-customer',$data);
      }
    }

    // GET COUNTRY
    public function get_countries()
    {
      $ctr = Countries::whereIn('id',[13,95,126,192,228,229])->get();
      return $ctr;
    }

    public function extract_hobbies(array $data)
    {
       $arr = [];
       $repeat = false;

       foreach($data as $col)
       {
          $utils = Utility::where('id_category',$col)->get();

          if($utils->count() > 0)
          {
            foreach($utils as $row)
            {
              $arr[] = $row->id;
            }
            $repeat = true;
          }
          else
          {
              $arr[] = $col;
          }
       }

       //CHECK IF CHILD STILL AVAILABLE
       $check_child = Utility::whereIn('id_category',$arr)->get();

       if($check_child->count() > 0)
       {
          return $this->extract_hobbies($arr); 
       }
       else
       {
          return $arr; 
       } 
      
    }

     // display religion
    public static $religion =  ['all','islam','kristen','katolik','budha','hindu'];

    // display gender
    public static $gender =  ['all','pria','wanita'];
    // display marriage status
    public static $marriage =  ['all','Belum Menikah','Sudah Menikah'];

    // GET PROVINCE
    public function get_province(Request $request)
    {
      $val = array();
      $name = $request->name;
      if($name == null)
      {
        return response()->json($val);
      }

      $province = Province::where('nama','LIKE','%'.$name.'%')->get();

      if($province->count() > 0)
      {
        foreach($province as $col)
        {
          $val[$col->id] = $col->nama;
        }
      }

      return response()->json($val);
    }

    // GET PROVINCE ACCORDING ON PROVINCE
    public function get_city(Request $request)
    {
      // dd($request->all());
      $data = array();
      $prov_id = $request->provinsi_id;
      $nama = $request->name;
      $kab = Kabupaten::where([['provinsi_id',$prov_id],['nama','LIKE','%'.$nama.'%']])->get();

      if($kab->count() > 0)
      {
        foreach($kab as $row):
          $data[$row->provinsi_id] = $row->nama;
        endforeach;
      }

      return response()->json($data);
    }

    // GET ZIP / POSTAL CODE ACCORDING ON LIST ID
    public function get_zip(Request $request)
    {
      // dd($request->all());
      $data = array();
      $list_id = $request->list_id;
      $zip = Customer::where([['list_id',$list_id],['user_id',Auth::id()]])->get();

      if($zip->count() > 0)
      {
        foreach($zip as $row):
          if($row->zip !== null)
          {
            $data[$row->id] = $row->zip;
          }
        endforeach;
      }

      return response()->json(array_unique($data));
    }

    public function saveSubscriber(Request $request)
    {
        $birthday = strip_tags($request->birthday);
        $gender = strip_tags($request->sex);
        $country = strip_tags(ucwords($request->country));
        $province = strip_tags(ucwords($request->province));
        $city = strip_tags(ucwords($request->city));
        $zip = strip_tags($request->zip);
        $marriage = strip_tags($request->marriage_status);
        $religion = strip_tags($request->religion);
        $hobby =  $request->hobby;
        $occupation = $request->occupation;
        $hobbies = $occupations = null;

        ($country == null || $country =="")?$country = 0 : $country = $country;
        ($hobby == "")?$hobby = null:$hobby = $hobby;
        ($occupation == "")?$occupation = null:$occupation = $occupation;

      
        if($hobby !== null)
        {
          foreach($hobby as $key=> $row):
            $hobbies .= strip_tags($row).";";
          endforeach;
        }

        if($occupation !== null)
        {
          foreach($occupation as $key=> $row):
            $occupations .= strip_tags($row).";";
          endforeach;
        }

        $arr_data = [
           // Do not allow any shady characters
           'subscribername' => 'max:255|regex:/^[\s\w-]*$/', // alpha num with white space
           'phone_number' => 'max:999999999999999|numeric',
           'email' => 'max:255|email',
           'data_country' => 'max:255|alpha_num',
           'code_country' => 'max:255|regex:/^\+\d{1,3}$/', //https://stackoverflow.com/questions/56161838/laravel-country-code-validation-with-regex
        ];
        if($request->last_name != null)
        {
          $arr_data['last_name'] = 'max:255|regex:/^[\s\w-]*$/';
        }
        $validator = Validator::make($request->all(), $arr_data);
        if ($validator->fails()) {
            $data['success'] = false;
            $data['message'] = $validator->errors()->first();
            return response()->json($data);
        }

        $listname = $request->listname;

        // if add subscriber from API
        if($request->api == false)
        {
          $phone_number = $request->code_country.$request->phone_number;
        }
        else
        {
          $phone_number = $request->phone_number;
        }

        $req = $request->all();
        $list = UserList::where('name','=',$listname)->first();
        $today = Carbon::now();
        $valid_customer = false;

        if(isset($req['data']))
        {
            // for security reason
            foreach($req['data'] as $col => $val)
            {
              $req['data'][$col] = strip_tags($val);
            }
            $addt = json_encode($req['data']);
        } 
        else {
            $addt = null;
        }

        // CASE IN API
        if(is_null($list) && $request->api == true){
           $data['success'] = false;
           $data['message'] = 'Invalid Link';
           return response()->json($data);
        }

        // Filter to avoid unavailable link 
        if(is_null($list)){
            return redirect('/');
        } 
        else {
            // $customer = new Customer;
            // $customer->user_id = $list->user_id;
            // $customer->list_id = $list->id;
            // $customer->name = $request->subscribername;
            // $customer->email = $request->email;
            // $customer->telegram_number = $phone_number;
            // $customer->additional = $addt;
            // if ($list->is_secure) {
              // $customer->status = 0;
            // }
            // $customer->save();
            $check_phone = $this->checkDuplicateSubscriberPhone($phone_number,$list->id);
            $check_email = $this->checkDuplicateSubscriberEmail($request->email,$list->id);

            if($request->overwrite == null && $request->listedit == 1)
            {
                if($check_phone == true)
                {
                  return response()->json(['duplicate'=>1]);
                }
            }

						$status = 1;
            if ($list->is_secure) 
            {
              $status = 0;
            }

            //UPDATED CASE
            if($request->data_update <> null)
            {
              $customer = Customer::find($request->data_update);
              $customer->name = strip_tags($request->subscribername);
              $customer->last_name = strip_tags($request->last_name);
              $customer->email = strip_tags($request->email);
              $customer->code_country = strip_tags($request->data_country);
              $customer->status = 1;
              $customer->telegram_number = "";
        
              if($request->phone_number !== null)
              {
                $customer->telegram_number = strip_tags($phone_number);
              }

              try
              {
                  $customer->save();
                  $data['update'] = true;
                  $data['message'] = 'Success, your contact has updated';
                  $data['newnumber'] = $phone_number;
              }
              catch(QueryException $e)
              {
                  $data['update'] = false;
                  $data['message'] = 'Sorry, our system is too busy-';
              }
              return response()->json($data);
            }

            // AVAILABLE DATA CASE ON LIST EDIT UNDER IMPORT
            if($request->overwrite == 1)
            {
              $customer_phone = Customer::where([['list_id',$list->id],['telegram_number',$phone_number]]);

              $update = array(
                'name' => $request->subscribername,
                'email'=> $request->email,
                'code_country'=>$request->data_country,
                'status'=> 1
              );

              try
              {
                $customer_phone->update($update);
                $data['success'] = true;
                $data['message'] = 'Success, your contact has been overwritten';
              }
              catch(QueryException $e)
              {
                $data['success'] = false;
                $data['message'] = 'Sorry, our system is too busy--';
              } 
              return response()->json($data);
            } 
            else if($check_phone == true || $check_email == true)
            {
               // AVALILABLE USER (EMAIL OR PHONE)
              $reg = array(
                'name' => $request->subscribername,
                'last_name' => $request->last_name,
                'telegram_number' => $phone_number,
                'status' => 1,
              );
    
              if($list->message_conf == null || $list->message_conf == '')
              {
                  $message_conf = Lang::get('custom.message_conf');
              }
              else
              {
                  $message_conf = $list->message_conf;
              }
              
              try
              {
                $customer = Customer::where([['list_id',$list->id],['telegram_number',$phone_number],['user_id',$list->user_id]])->orWhere([['email',$request->email],['list_id',$list->id]]);

                $customer->update($reg);
                $customer_id = $customer->first();
                $customer_id = $customer_id->id;

                // TJAPNJALUK (Tjapnjaluk customer always get WA message when entering lottery code)
                // if($list->id == 346) -- old account
                if($list->id == 374) //cx7930ls
                {
                  self::sendMessageReply($list,$customer_id,$message_conf);
                }
                
                $data['success'] = true;
                $data['message'] = $message_conf;
              }
              catch(QueryException $e)
              {
                $data['success'] = false;
                $data['message'] = Lang::get('custom.db').'---';
              }

              return response()->json($data);
            }
            else
            {
              // NEW CUSTOMER
              $customer = Customer::create([
                 'user_id'  => $list->user_id,
                 'list_id'  => $list->id,
                 'name'     => strip_tags($request->subscribername),
                 'last_name' => strip_tags($request->last_name),
                 'telegram_number'=>strip_tags($phone_number),
                 'code_country'=>strip_tags($request->data_country),
                 'email'=> strip_tags($request->email),
                 'additional' => $addt,
                 'birthday'=>$birthday,
                 'gender'=>$gender,
                 'province'=>$province,
                 'country'=>$country,
                 'city'=>$city,
                 'zip'=>$zip,
                 'marriage'=>$marriage,
                 'hobby'=>$hobbies,
                 'occupation'=>$occupations,
                 'religion'=>$religion,
                 'status'=> $status
              ]);
              $customer_id = $customer->id;
              $customer_join = $customer->created_at;
            }

            $customer::create_link_unsubs($customer->id,$list->id);

            /*
            Kalo is_secure maka akan dikirim langsung message wa nya 
            */
            if ($list->is_secure) 
            {
							$ret= $this->sendListSecure($list->id,$customer_id,$request->subscribername,$list->user_id,$list->name,$phone_number);

							if($ret->getData()->success == false)
              {
								$data['success'] = false;
								$data['message'] = $ret->getData()->message;
								return response()->json($data);
							}
            }

            // if customer successful sign up / NORMAL CASE
            try
            {
              $customer->save();
              $api = new API;

              // TBO TGL 18/DEC/2020
              // if($list->id == 4)
              // {
              //   // send to activcampaign
              //   // $api->listActivCampaign(strip_tags($request->email),strip_tags($request->subscribername),strip_tags($request->last_name),$phone_number,7);
              // }

              // FORM TEKNOBIE
              // if($list->id == 5)
              // {
              //   // send to activcampaign
              //   // $api->listActivCampaign(strip_tags($request->email),strip_tags($request->subscribername),strip_tags($request->last_name),$phone_number,8);               
              // }

              //selzy email
              if($list->id == 372)
              {
                $api->listSelzy(strip_tags($request->email),strip_tags($request->subscribername),strip_tags($request->last_name),"679573");
              }

              // TBO dari tanggal 14/09/2021
              if($list->id == 369)
              {
                // send to sendfox
                //Teknobie Komunitas Bisnis Online
                $api->listSendFox(strip_tags($request->email),strip_tags($request->subscribername),strip_tags($request->last_name),297501);
              }

              //komunitas teknobie content creator
              if($list->id == 372)
              {
                // send to sendfox 
                //Teknobie Komunitas Content Creator 
                $api->listSendFox(strip_tags($request->email),strip_tags($request->subscribername),strip_tags($request->last_name),297502);
              }
              
              if ($list->id == 12)
              {
                //send to celebmail
                $apiWPController = new ApiWPController;
                $apiWPController->sendToCelebmail(strip_tags($request->subscribername).' '.strip_tags($request->last_name),strip_tags($request->email),'hc716nc2ry622');
              }

              if ($list->id == 13)
              {
                //send to celebmail
                $apiWPController = new ApiWPController;
                $apiWPController->sendToCelebmail($request->subscribername.' '.$request->last_name,$request->email,'te7027awnw9f8');
              }
              
              if ($list->id == 14)
              {
                //send to celebmail
                $apiWPController = new ApiWPController;
                $apiWPController->sendToCelebmail($request->subscribername.' '.$request->last_name,$request->email,'wx909tbczb069');
              }
              
              if ($list->id == 11)
              {
                //send to celebmail
                $apiWPController = new ApiWPController;
                $apiWPController->sendToCelebmail($request->subscribername.' '.$request->last_name,$request->email,'of747vmm6q720');
              }
              
              if ($list->id == 258)
              {
                //send to celebmail
                $apiWPController = new ApiWPController;
                $apiWPController->sendToCelebmail($request->subscribername.' '.$request->last_name,$request->email,'sw509ql9lcbf7');
              }
              
              if ($list->id == 259)
              {
                //send to celebmail
                $apiWPController = new ApiWPController;
                $apiWPController->sendToCelebmail($request->subscribername.' '.$request->last_name,$request->email,'bj258f08gh975');
              }
              
              if ($list->id == 260)
              {
                //send to celebmail
                $apiWPController = new ApiWPController;
                $apiWPController->sendToCelebmail($request->subscribername.' '.$request->last_name,$request->email,'rf230bwtbg7c3');
              }
              
              
              if ($list->id == 10)
              {
                $url='https://activproof.com/package/pixel-webhook/4372184ccdfa544983dd5c9259808099';

                $data = array(
                  "subscribername" => $request->subscribername.' '.$request->last_name,
                  "city" => $request->city,
                );

                $data_string = json_encode($data);
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_VERBOSE, 0);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
                curl_setopt($ch, CURLOPT_TIMEOUT, 360);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                  'Content-Type: application/json',
                  'Content-Length: ' . strlen($data_string)
                ));
                $res=curl_exec($ch);
                // return $res;
              }
              

               $user_id = $list->user_id;
               $list_id = $list->id;

               // IF TJAPNJALUK LOTTERY LOGIC
               // if($list->id == 346)
               if($list->id == 374) //cx7930ls
               {
                  if($list->message_conf == null || $list->message_conf == '')
                  {
                      $message_conf = Lang::get('custom.message_conf'); 
                  }
                  else
                  {
                      $message_conf = $list->message_conf;
                  }
                  return self::sendMessageReply($list,$customer_id,$message_conf);
               }
               else
               {
                  return $this->addSubscriber($list_id,$customer_id,$customer_join,$user_id);
               }
            }
            catch(QueryException $e)
            {
               // dd($e->getMessage());
               $data['success'] = false;
               $data['message'] = Lang::get('custom.db').'-.';
            }
          
            return response()->json($data);
        }
    }

    // SEND WA MESSAGE TO EACH TJAPNJALUK CUSTOMER LOTTER
    public static function sendMessageReply($list,$customer_id,$message_conf)
    {
      $reminder = Reminder::where('list_id',$list->id)->where('days',0)->where('is_event',0)->first();

      $customer = Customer::find($customer_id);
      $to = $customer->telegram_number;

      $message = $reminder->message;
      $customer_id = bin2hex($customer_id);
      $link = "https://tjapnjaluk.com/undian/public/lottery?c=".$customer_id;
      $message = str_replace("[CLINK]", $link, $message);

      $admin = PhoneNumber::where('user_id',$list->user_id)->first(); //admin
      $phone = $admin->phone_number;
      $phone_key = $admin->device_key;
      $phone_ip = $admin->ip_server;

      $msg = new Message;
      $msg->user_id = $list->user_id;
      $msg->sender = $phone;
      $msg->phone_number = $to;
      $msg->key = $phone_key;
      $msg->message = $message;
      $msg->status = 11;
      $msg->customer_id = 0;
      $msg->ip_server = $phone_ip;

      try{
        $msg->save();
        $data['success'] = true;
        $data['message'] = $message_conf;
      }
      catch(Queryexception $e)
      {
        $data['success'] = false;
        $data['message'] = Lang::get('custom.db').'---';
      }

      return response()->json($data);
                        
    }
		
		function sendListSecure($list_id,$customer_id,$subscribername,$user_id,$list_name,$phone_number)
		{
			$phoneNumber = PhoneNumber::where("user_id",$user_id)->first();
			// $key = $phoneNumber->filename;

      // this function attached due can cause error user unable to get email
      if(is_null($phoneNumber))
      {
        $data['success'] = true;
        return response()->json($data);
      }

			//pengecekan klo pake simi
			if(env('APP_ENV') !== 'local')
			{
				if ($phoneNumber->mode == 0) 
        {
					$server = Server::where('phone_id',$phoneNumber->id)->first();
					if(is_null($server)){
						$data['success'] = false;
						$data['message'] = Lang::get('custom.db').'.-';
						return response()->json($data);
					}
				}
			}

			$reminder = Reminder::
									where("is_event",0)
									->where("days",0)
									->where("list_id",$list_id)
									->first();
			$message = "";
			if (!is_null($reminder)){
				$message = $reminder->message;
				$message = str_replace( "[NAME]" , $subscribername, $message);
				// $message = str_replace( "[REPLY_CHAT]" , "whatsapp://send/?phone=".$phoneNumber->phone_number."&text=" . "Hi Nama saya ".$request->subscribername.", saya bergabung digroup ini", $message);

        $customer = Customer::find($customer_id);
				$message = str_replace( "[START]" , env("APP_URL")."link/activate/".$list_name."/".$customer_id, $message);
        $list = UserList::find($list_id);
        if (!is_null($list)){
          if ($customer->link_unsubs =="") {
            $message = str_replace( "[UNSUBS]" , env("APP_URL")."link/unsubscribe/".$list->name."/".$customer_id, $message);
          }
          else {
            $message = str_replace( "[UNSUBS]" , $customer->link_unsubs, $message);
          }
        }
			}
			// ApiHelper::send_message($phone_number,$message,$key);
			$message_send = new Message;
			$message_send->phone_number=$phone_number;
			$message_send->message=$message;

			if ($phoneNumber->mode == 0 && env('APP_ENV') !== 'local') {
				$message_send->key=$server->url;
				$message_send->status=8;
			}
			if ($phoneNumber->mode == 1) {
				$message_send->key=$key;
				$message_send->status=9;
			}
			$message_send->customer_id=$customer_id;

      try{
        $message_send->save();
        $data['success'] = true;
        $data['message'] = Lang::get('custom.list_secure');
      }
      catch(QueryException $e)
      {
        // dd($e->getMessage());
        $data['success'] = false;
        $data['message'] = Lang::get('custom.db');
      }
			return response()->json($data);
		}

    public function checkDuplicateSubscriberPhone($wa_number,$list_id)
    {
        $customer = Customer::where([
          ['telegram_number','=',$wa_number],
          ['list_id','=',$list_id]
        ])->first();

        if(is_null($customer))
        {
            return false;
        }
        else
        {
            return true;
        }
    } 

    public function checkDuplicateSubscriberEmail($email,$list_id)
    {
        $customer = Customer::where([
          ['email','=',$email],
          ['list_id','=',$list_id]
        ])->first();

        if(is_null($customer))
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    function addSubscriber($list_id,$customer_id,$customer_join,$user_id)
    {
        $reminder = Reminder::where([['list_id','=',$list_id],['user_id','=',$user_id],['status','=',1]])->get();
        $confirmation = UserList::find($list_id);

        if($reminder->count() > 0)
        {
           //EVENT
          foreach($reminder as $row)
          {
              $is_event = $row->is_event;

              if($is_event == 1 || $is_event == 2)
              {
                  $today = Carbon::now()->toDateString();
                  $days = (int)$row->days;
                  $event_date = Carbon::parse($row->event_time);

                  if($days < 0){
                    $days = abs($days);
                    $event_date->subDays($days);
                  } 
                  else {
                    $event_date->addDays($days);
                  }

                  $event_date = $event_date->toDateString();

                  if($event_date >= $today){
                      $reminder_customer = new ReminderCustomers;
                      $reminder_customer->user_id = $user_id;
                      $reminder_customer->list_id = $list_id;
                      $reminder_customer->reminder_id = $row->id;
                      $reminder_customer->customer_id = $customer_id;
                      $reminder_customer->save();
                  } 
              } //END IF IS_EVENT
              else
              //AUTO RESPONDER -- create auto responder from list standard
              {
                  $days = (int)$row->days;
                  $after_sum_day = Carbon::parse($customer_join)->addDays($days);
                  $validday = $after_sum_day->toDateString();
                  $createdreminder = Carbon::parse($row->created_at)->toDateString();

                  if($validday >= $createdreminder){
                      $reminder_customer = new ReminderCustomers;
                      $reminder_customer->user_id = $user_id;
                      $reminder_customer->list_id = $list_id;
                      $reminder_customer->reminder_id = $row->id;
                      $reminder_customer->customer_id = $customer_id;
                      $reminder_customer->save(); 
                  } 
              }
          }//END FOREACH
        }

        //BROADCAST 
        $broadcast = BroadCast::where([['list_id',$list_id],['user_id',$user_id],['status','=',1]])->get();

        if($broadcast->count() > 0)
        {
          foreach($broadcast as $row)
          {
              $broadcastcustomer = new BroadCastCustomers;
              $broadcastcustomer->broadcast_id = $row->id;
              $broadcastcustomer->customer_id = $customer_id;
              $broadcastcustomer->save();
          }
        }

        // DETERMINE WHETHER APPOINTMENT OR NOT
        $userid = Auth::id();
        if($userid <> null)
        {
          $is_appointment = 1;
        }
        else
        {
          $is_appointment = 0;
        }

        if($is_appointment == 1)
        {
          $cst = Customer::find($customer_id);
          $cst->status = 1;
          $cst->save();
        }
        
        if($confirmation->message_conf == null && $userid <> null)
        {
            $message_conf = 'Your contact has been added';
        }
        else
        {
            $message_conf = $confirmation->message_conf;
        }

        $data['success'] = true;
        $data['message'] = $message_conf;
        $data['is_appointment'] = $is_appointment;
        return response()->json($data);
    }

    // redirect page

    public function link_activate($list_name,$customer_id)
    {
      $list = UserList::where('name','=',$list_name)->first();
      $customer = Customer::find($customer_id);
      if (!is_null($customer)){
        if ($customer->list_id == $list->id ) {
          $customer->status = 1;
          $customer->save();
        }
      }
			$message = "";
			if (!is_null($list)){
				$message = $list->start_custom_message;
				$message = str_replace("[LIST_NAME]",$list->label,$message);
			}
      // return redirect($list->name)->with('message',$message)
			return view('layouts.subscribe',['label'=>$list->label]);
			;
    }

    public function link_unsubscribe($list_name,$customer_id)
    {
      $list = UserList::where('name','=',$list_name)->first();
      $customer = Customer::find($customer_id);
      if (!is_null($customer)){
        if ($customer->list_id == $list->id ) {
          $customer->status = 0;
          $customer->save();
        }
      }
			$message = "";
			if (!is_null($list)){
				$message = $list->unsubs_custom_message;
				$message = str_replace("[LIST_NAME]",$list->label,$message);
			}

      // return redirect($list->name)->with('message',$message);
      return view('layouts.unsubscribe',['label'=>$list->label]);
    }

    public function Country(Request $request)
    {
        $search = $request->search;
        $result = str_replace("+", "", $search);

        $countries = Countries::where('name','LIKE','%'.$search.'%')->orWhere('code','=',$result)->orderBy('name','asc')->get();
        return view('countries',['data'=>$countries]);
    }
    
    
    /************************ OLD CODES ************************/

    //Reminder
    public function index(Request $request, $product_list){
    	$check_link = UserList::where([
            ['name','=',$product_list],
            ['is_event','=',0],
            ['status','=',1],
        ])->first();

    	if(empty($product_list)){
    		return redirect('/');
    	} elseif(is_null($check_link)) {
    		return redirect('/');
    	} else {
            $list = UserList::where('name',$product_list)->first();
            $additional = Additional::where('list_id',$list->id)->get();
            $data = array();
            $arr = array();
            $data['fields'] = array();

            if($additional->count() > 0)
            {
                foreach($additional as $row)
                {
                   if($row->id_parent == 0){
                        $data['fields'][] = $row;
                   } 
                }
            }

            if(count($data['fields']) > 0)
            {
                foreach($data['fields'] as $col)
                {
                     // count if name has child or not
                     $doption = Additional::where([['list_id',$list->id],['id_parent',$col->id]])->get();

                     if($doption->count() > 0)
                     {
                         foreach($doption as $rows)
                         {
                            $arr[$col->name][$col->is_field][] = $rows->name;
                         }
                     } 
                     else 
                     {
                            $arr[$col->name][$col->is_field] = $col;
                     }
                } 

            }
            
    		return view('register-customer',['id'=>encrypt($list->id),'content'=>$list->content,'listname'=>$product_list,'pixel'=>$list->pixel_text,'message'=>$list->message_text,'additional'=>$arr]);
    	}
    }

    //Event
    public function event(Request $request, $product_list){
        $check_link = UserList::where([
            ['name','=',$product_list],
            ['is_event','=',1],
            ['status','=',1],
        ])->first();

        if(empty($product_list)){
            return redirect('/');
        } elseif(is_null($check_link)) {
            return redirect('/');
        } else {
            $list = UserList::where('name',$product_list)->first();
            $additional = Additional::where('list_id',$list->id)->get();
            $data = array();
            $arr = array();
            $data['fields'] = array();

            if($additional->count() > 0)
            {
                foreach($additional as $row)
                {
                   if($row->id_parent == 0){
                        $data['fields'][] = $row;
                   } 
                }
            }

            if(count($data['fields']) > 0)
            {
                foreach($data['fields'] as $col)
                {
                     # count if name has child or not
                     $doption = Additional::where([['list_id',$list->id],['id_parent',$col->id]])->get();

                     if($doption->count() > 0)
                     {
                         foreach($doption as $rows)
                         {
                            $arr[$col->name][$col->is_field][] = $rows->name;
                         }
                     } 
                     else 
                     {
                            $arr[$col->name][$col->is_field] = $col;
                     }
                } 

            }
            return view('register-customer',['id'=>encrypt($list->id),'content'=>$list->content, 'listname'=>$product_list,'pixel'=>$list->pixel_text,'message'=>$list->message_text,'additional'=>$arr]);
        }
    }

    public function addCustomer(Request $request)
    {
        $listname = $request->listname;
        $req = $request->all();

        $get_id_list = UserList::where('name','=',$listname)->first();
        $wa_number = '+62'.$request->wa_number;
        $today = Carbon::now();
        $wassenger = null;
        $evautoreply = false;
        $valid_customer = false;
        $is_event = $get_id_list->is_event;
        //message & pixel
        $list_message = $get_id_list->message_text;
        $list_wa_number = $get_id_list->wa_number;

        if(isset($req['data']))
        {
            $addt = json_encode($req['data']);
        } 
        else {
            $addt = null;
        }

        // Filter to avoid unavailable link 
        if(is_null($get_id_list)){
            return redirect('/');
        } 
        else {
            $customer = new Customer;
            $customer->user_id = $get_id_list->user_id;
            $customer->list_id = $get_id_list->id;
            $customer->name = $request->name;
            $customer->telegram_number = $wa_number;
            $customer->additional = $addt;
            $customer->save();
            $customer::create_link_unsubs($customer->id,$get_id_list->id);
            $customer_subscribe_date = $customer->created_at;
            $customerid = $customer->id;
        }

        // if customer successful sign up 
        if($customer->save() == true){
          $valid_customer = true;
        } 
        else {
          $data['success'] = false;
          $data['message'] = 'Error-000! Sorry there is something wrong with our system';
          return response()->json($data);
        }

        if($is_event == 1 && $valid_customer == true){
            // Event
            $reminder = Reminder::where([
                ['reminders.list_id','=',$get_id_list->id],
                ['lists.is_event','=',1],
                ['reminders.hour_time','<>',null],
                ['reminders.status','=',1],
                ])
                ->leftJoin('lists','reminders.list_id','=','lists.id')
                ->select('reminders.*','lists.event_date')
                ->get();
        } 
        else if($is_event == 0 && $valid_customer == true) {
            // Reminder
             $reminder = Reminder::where([
                ['reminders.list_id','=',$get_id_list->id],
                ['lists.is_event','=',0],
                ['reminders.days','>',0],
                ['reminders.hour_time','=',null],
                ['reminders.status','>',0],
                ])
                ->join('lists','reminders.list_id','=','lists.id')
                ->select('reminders.*')
                ->get(); 
        }
        
        if($reminder->count() > 0 && $is_event == 1)
        {
           //Event
            foreach($reminder as $row)
            {
              $today_event = Carbon::now()->toDateString();
              $days = (int)$row->days;
              $event_date = Carbon::parse($row->event_date);

              if($days < 0){
                $days = abs($days);
                $event_date->subDays($days);
              } 
              else {
                $event_date->addDays($days);
              }

              if($event_date >= $today_event){
                  $reminder_customer = new ReminderCustomers;
                  $reminder_customer->user_id = $row->user_id;
                  $reminder_customer->list_id = $row->list_id;
                  $reminder_customer->reminder_id = $row->id;
                  $reminder_customer->customer_id = $customerid;
                  $reminder_customer->save();
                  $eligible = true;
              } 
              else {
                $eligible = null;
              }

            }

            if($eligible == true){
                //return $this->autoReply($get_id_list->id,$wa_number,$list_message,$list_wa_number,$request->name);
                $data['success'] = true;
                $data['message'] = 'Thank you for join us';
                return response()->json($data);
            } 
            else if($eligible == null) {
                $data['message'] = 'Sorry this event has expired';
                return response()->json($data);
            } 
            else {
                $data['success'] = false;
                $data['message'] = 'Error-001! Sorry there is something wrong with our system';
                return response()->json($data);
            }
        } 
        else if($reminder->count() > 0 && $is_event == 0) 
        {
            // Reminder
            foreach($reminder as $row)
            {
                $days = (int)$row->days;
                $after_sum_day = Carbon::parse($customer_subscribe_date)->addDays($days);
                $validday = $after_sum_day->toDateString();
                $createdreminder = Carbon::parse($row->created_at)->toDateString();

                if($validday >= $createdreminder){
                    $reminder_customer = new ReminderCustomers;
                    $reminder_customer->user_id = $row->user_id;
                    $reminder_customer->list_id = $row->list_id;
                    $reminder_customer->reminder_id = $row->id;
                    $reminder_customer->customer_id = $customerid;
                    $reminder_customer->save(); 
                    $eligible = true; 
                } else {
                    $eligible = null;
                }
            }

            if($is_event == 1){
                $msg = 'event';
            } 
            else {
                $msg = 'reminder';
            }

              // if reminder has been set up into reminder-customer 
            if($eligible == true){
                //return $this->autoReply($get_id_list->id,$wa_number,$list_message,$list_wa_number,$request->name);
                $data['success'] = true;
                $data['message'] = 'Thank you for join us';
            } else if($eligible == null) {
                $data['message'] = 'Sorry this '.$msg.' has expired';
                return response()->json($data);
            } else {
                $data['success'] = false;
                $data['message'] = 'Error-002! Sorry there is something wrong with our system';
                return response()->json($data);
            }    
        } else {
            $data['success'] = true;
            $data['message'] = 'Thank you for join us';
        }
    }

    public function autoReply($listid,$wa_number,$list_message,$list_wa_number,$customer_name)
    {
        // send wa link to send message to list owner
        $list_wa_device = $list_wa_number;
        $list_wa_number = str_replace("+","",$list_wa_number);
        $data['wa_link'] = 'whatsapp://send?phone='.$list_wa_number.'&text='.$list_message.'';
        // $data['wa_link'] = 'https://api.whatsapp.com/send?phone='.$list_wa_number.'&text='.$list_message.'';

         // Sending event auto reply for customer, return true if user has not set auto reply yet
        $autoreply = Reminder::where([
                ['reminders.list_id','=',$listid],
                ['reminders.days','=',0],
                ['reminders.hour_time','=',null],
                ['reminders.status','=',1],
                ])->select('reminders.*')->first();

        if(is_null($autoreply)){
            $data['success'] = true;
            $data['message'] = 'Thank You For Join Us';
            return response()->json($data);
        } 
        else {
             // wassenger
            $user_id = $autoreply->user_id;
            $getsender = Sender::where([['user_id',$user_id],['wa_number','=',$list_wa_device]])->first();
        }

        if(is_null($getsender))
        {
            $data['success'] = false;
            $data['message'] = 'Error-Send! Sorry, looks like if owner of this event had not set WA number yet';
            return response()->json($data);
        }
        else
        {
            $deviceid = $getsender->device_id;
            $message = str_replace('{name}',$customer_name,$autoreply->message);
            $status = $autoreply->status;
        }

        if($status == 1){
            $sendmessage = new SendMessage;
            $wasengger = $sendmessage->sendWA($wa_number,$message,$deviceid);
        } 
        else {
            $wasengger = null;
        }

        // if status from reminder has set to 0 or disabled
        if($wasengger == null && $status > 1){
            $data['success'] = true;
            $data['message'] = 'Thank You For Join Us';
            return response()->json($data);
        } 
        else if($wasengger !== null && $status == 1){
            $data['success'] = true;
            $data['message'] = 'Thank You For Join Us';
            return response()->json($data);
        } 
        else {
            $data['success'] = false;
            $data['message'] = 'Error-WAS! Sorry there is something wrong with our system';
            return response()->json($data);
        }
    }

    public function testCode()
    {
      $user = User::find(1);
      // if( Carbon::parse(null)->lt(Carbon::now()) ){
      if( Carbon::parse($user->created_at)->lt(Carbon::now()) ){
        echo "a";
      }
      echo "b";
    }
/* end of class */
}
