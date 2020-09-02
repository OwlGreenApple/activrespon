<?php
 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
// use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use App\UserList;
use App\Customer;
use App\User;
use App\Campaign;
use App\Reminder;
use App\ReminderCustomers;
use App\TemplateAppointments;
// use App\Exports\UsersExport;
use App\Helpers\Alert;
use Excel,Storage;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
      $userid = Auth::id();
      $data = array();
      $search = $request->search;
      $paging = 25;

      if($search == null)
        {
            $campaigns = Campaign::where([['campaigns.user_id',$userid],['campaigns.type',3],['lists.status','>',0]])
                    ->join('lists','lists.id','=','campaigns.list_id')
                    ->select('campaigns.*','lists.name AS url','lists.label','lists.id AS list_id')
                    ->orderBy('id','desc')
                    ->paginate($paging);
        }
        else
        {
            $campaigns = Campaign::where([['campaigns.user_id',$userid],['campaigns.type',3],['campaigns.name','LIKE','%'.$search.'%'],['lists.status','>',0]])
                    ->join('lists','lists.id','=','campaigns.list_id')
                    ->select('campaigns.*','lists.name AS url','lists.label','lists.id AS list_id')
                    ->orderBy('id','desc')
                    ->paginate($paging);
        }

        // $total_template = TemplateAppointments::where('campaign_id',$row->id)->get();
                
        $data = array(
          'logic_appointment' => new AppointmentController,
          'templates'=> new TemplateAppointments,
          'paginate'=>$campaigns,
          'appointments'=>$campaigns
        );
        
      if($request->ajax())
      {
        return view('appointment.table_apt',$data);
      }
      return view('appointment.index',$data);
    }

    public function dataAppointment($campaign_id,$cond,$status)
    {
       $userid = Auth::id();
       $appointments = ReminderCustomers::where([['reminders.campaign_id',$campaign_id],['reminders.is_event',2],['reminders.user_id',$userid],['reminder_customers.status',$cond,$status]])
          ->join('reminders','reminders.id','=','reminder_customers.reminder_id')
          ->join('customers','customers.id','=','reminder_customers.customer_id')
          ->select('reminders.campaign_id','reminders.event_time','reminders.days','customers.name','customers.telegram_number','customers.id','reminder_customers.id AS rid','reminder_customers.status')
          ->orderBy('reminder_customers.id','desc')
          ->get();

        return $appointments;
    }

    public function listAppointment($campaign_id,$active)
    {
        /*
          1 = Active
          0 = inactive
        */

        ($active == 0 || $active == 1)?$invalid = false : $invalid = true;

        if($invalid == true)
        {
           return redirect('create-apt');
        }

        $userid = Auth::id();
        if(empty($campaign_id) || $campaign_id==null)
        {
            return redirect('create-apt');
        }

        $checkid = Campaign::where([['id',$campaign_id],['user_id',$userid]])->first();
        $label = UserList::find($checkid->list_id)->label;

        if(is_null($checkid))
        {
            return redirect('create-apt');
        }

        return view('appointment.list_apt',['campaign_id'=>$campaign_id,'campaign_name'=>$checkid->name,'active'=>$active,'list_id'=>$checkid->list_id,'label'=>$label]);
    }

    public function listTableAppointments(Request $request)
    {
        $userid = Auth::id();
        $campaign_id = $request->campaign_id;
        $active = $request->active;

        if($active == 1)
        {
          $appointment = $this->dataAppointment($campaign_id,'=',0);
        }
        else
        {
          $appointment = $this->dataAppointment($campaign_id,'>',0);
        }
       
        return view('appointment.list_table_apt',['campaigns'=>$appointment,'active'=>$active,'alert'=> new Alert,'campaign_id'=>$campaign_id]);
    }

    public function listAppointmentEdit(Request $request)
    {
        $userid = Auth::id();
        $customer_name = $request->customer_name;
        $customer_phone = $request->phone_number;
        $customer_id = $request->customer_id;
        $date_send = $request->date_send;
        $campaign_id = $request->campaign_id;
        $oldtime = $request->oldtime;

        try{
          Reminder::where([['campaign_id',$campaign_id],['is_event',2],['user_id',$userid],['event_time',$oldtime]])->update(['event_time'=>$date_send]);
        }
        catch(QueryException $e)
        {
          $status['success'] = 0;
          $status['message'] = 'Sorry unable to update your appointment, please try again later. -';
          return response()->json($status);
        }

        try
        {
          $data = [
            'name'=>$customer_name,
            'telegram_number'=>$customer_phone,
          ];
          Customer::where('id',$customer_id)->update($data);
          $status['success'] = 1;
          $status['message'] = 'Your appointment list has been updated!';
        }
        catch(QueryException $e)
        {
          $status['success'] = 0;
          $status['message'] = 'Sorry unable to update your appointment, please try again later. --';
        }
        return response()->json($status);
    }

    public function listAppointmentDelete(Request $request)
    {
        $userid = Auth::id();
        $id = $request->reminder_customer_id;

        try {
          ReminderCustomers::where([['id',$id],['user_id',$userid]])->update(['status'=>4]);
          //Reminder::where([['id',$id],['user_id',$userid]])->update(['status'=>0]);
          $data['success'] = 1;
          $data['message'] = 'Your list appointment has been cancelled';
        }
        catch(QueryException $e)
        {
          $data['success'] = 0;
          $data['message'] = 'Sorry unable to cancel your list appointment, please try again later';
        }

        return response()->json($data);
    }

    /*public function listAppointmentDelete(Request $request)
    {
        $userid = Auth::id();
        $campaign_id = $request->campaign_id;
        $customer_id = $request->customer_id;
        $oldtime = $request->oldtime;
        $reminder_id = [];
        $count = 0;
        $get_reminder_id = ReminderCustomers::where([['reminder_customers.user_id',$userid],['reminder_customers.customer_id',$customer_id],['reminders.event_time','=',$oldtime]])
        ->join('reminders','reminders.id','=','reminder_customers.reminder_id')
        ->select('reminders.id')->get();

        if($get_reminder_id->count() > 0)
        {
          foreach($get_reminder_id as $row)
          {
              $reminder_id[] = $row->id;
          }
          $total_reminder_id = count($reminder_id);
        }
        else
        {
            $data['success'] = 0;
            $data['message'] = 'Invalid data!';
            return response()->json($data);
        }

        foreach($reminder_id as $id)
        {
            try {
              ReminderCustomers::where([['reminder_id',$id],['user_id',$userid]])->update(['status'=>4]);
              Reminder::where([['id',$id],['user_id',$userid]])->update(['status'=>0]);
              $count++;
            }
            catch(Exception $e)
            {
              $data['success'] = 0;
              $data['message'] = 'Sorry unable to cancel your list appointment, please try again later';
              return response()->json($data);
            }
        }

        if($count == $total_reminder_id)
        {
            $data['success'] = 1;
            $data['message'] = 'Your list appointment has been cancelled';
        }
        else
        {
            $data['success'] = 0;
            $data['message'] = 'Sorry unable to cancel your list appointment, please try again later';
        }
        return response()->json($data);
    }*/

    function createAppointment()
    {
      $userid = Auth::id();
      $lists = displayListWithContact($userid);
      return view('appointment.create_apt',['lists'=>$lists]);
    }

    public function saveAppointment(Request $request)
    {
      $userid = Auth::id();
      $appt = new Campaign;
      $appt->name = $request->name_app;
      $appt->type = 3;
      $appt->list_id = $request->list_id;
      $appt->user_id = $userid;

      try {
        $appt->save();
        $data['id'] = $appt->id;
        $data['success'] = 1;
        $data['message'] = 'Your appointment has been created';
      }
      catch(QueryException $e)
      {
        $data['success'] = 0;
        $data['message'] = 'Sorry currently our server is too busy, please try again later';
      }
      return response()->json($data);
    }

    public function editAppointment($id)
    {
        $userid = Auth::id();
        if(empty($id) || $id==null)
        {
            return redirect('create-apt');
        }

        $checkid = Campaign::where([['campaigns.id',$id],['campaigns.user_id',$userid],['lists.status','>',0]])->join('lists','lists.id','=','campaigns.list_id')->select('campaigns.*')->first();
        

        if(is_null($checkid))
        {
            return redirect('create-apt');
        }

        return view('appointment.edit_apt',['id'=>$id,'campaign_name'=>$checkid->name]);
    }

    public function displayTemplateAppointment(Request $request)
    {
        $userid = Auth::id();
        $campaign_id = $request->id;
        $reminder = TemplateAppointments::where([['user_id',$userid],['campaign_id',$campaign_id]])->orderBy('days','desc')->get();

        return view('appointment.display_apt_reminder',['reminder'=>$reminder]);
    }

    public function saveTemplateAppointment(Request $request)
    {
        $userid = Auth::id();
        $campaign_id = $request->campaign_id;
        $listid = Campaign::find($campaign_id)->list_id;
        $save = false;
        $count = 0;
        $reminderid = $customerid = $newcustomer = array();
        $path = '';

        if(isset($_POST['day']))
        {
            $days = $request->day;
        }
        else 
        {
            $days = 0;
        }

        if($request->hasFile('imageWA')) {
          //save ke temp local dulu baru di kirim 
          $image_size = getimagesize($request->file('imageWA'));
          $imagewidth = $image_size[0];
          $imageheight = $image_size[1];

          $dt = Carbon::now();
          $ext = $request->file('imageWA')->getClientOriginalExtension();
          $folder = $userid."/appointment-image/";
          $filename = $dt->format('ymdHi').'.'.$ext;

          if(checkImageSize($request->file('imageWA')) == true || $imagewidth > 1280 || $imageheight > 1280)
          {
              $scale = scaleImageRatio($imagewidth,$imageheight);
              $imagewidth = $scale['width'];
              $imageheight = $scale['height'];
              resize_image($request->file('imageWA'),$imagewidth,$imageheight,false,$folder,$filename);
          }
          else
          {
              Storage::disk('s3')->put($folder.$filename,file_get_contents($request->file('imageWA')), 'public');
          }
          $path = $folder.$filename;
        }

        //UPDATE CASE
        if($request->is_update <> 'undefined')
        {
            try 
            {
              $update = array(
                'days'=>$days,
                'time_sending'=>$request->hour,
                'message'=>$request->message,
              );

              TemplateAppointments::where([['user_id',$userid],['id',$request->is_update]])->update($update);

              $update_reminder = array(
                'days'=>$days,
                'hour_time'=>$request->hour,
                'image'=>$path,
                'message'=>$request->message,
              );

              Reminder::where([['tmp_appt_id',$request->is_update],['user_id',$userid],['campaign_id',$campaign_id]])->update($update_reminder);

               $data['success'] = 1;
               $data['message'] = 'Your template appointment has been updated!';
               return response()->json($data);
            }
            catch(QueryException $e)
            {
               $data['success'] = 0;
               $data['message'] = 'Sorry unable to update your template appointment, please try again later';
               return response()->json($data);
            }
        }

        //CREATE NEW CASE
        $template_appt = new TemplateAppointments;
        $template_appt->user_id = $userid;
        $template_appt->campaign_id = $campaign_id;
        $template_appt->days = $days;
        $template_appt->time_sending = $request->hour;
        $template_appt->message = $request->message;
 
        try {
          $template_appt->save();
          $tmp_appt_id = $template_appt->id;
          $save = true;
        }
        catch(Exception $e)
        {
          $data['success'] = 0;
          $data['message'] = 'Sorry, unable to create schedule template, please try again later';
          return response()->json($data);
        }

        if($save == true)
        {
            // TO OBTAIN CUSTOMER FROM PREVIOUS TEMPLATE WITH SAME CAMPAIGN
            $getcustomer = ReminderCustomers::where([['reminders.campaign_id',$campaign_id],['reminder_customers.status','=',0]])
            ->join('reminders','reminders.id','=','reminder_customers.reminder_id')
            ->join('customers','customers.id','=','reminder_customers.customer_id')
            ->select('customers.id','reminders.event_time')
            ->distinct()
            ->get();

            if($getcustomer->count() > 0)
            {
              foreach($getcustomer as $row)
              {
                $reminder = new Reminder;
                $reminder->user_id = $userid;
                $reminder->list_id = $listid;
                $reminder->campaign_id = $campaign_id;
                $reminder->tmp_appt_id = $tmp_appt_id;
                $reminder->is_event = 2;   
                $reminder->days = $days;
                $reminder->hour_time = $request->hour;
                $reminder->event_time = $row->event_time;
                $reminder->image = $path;
                $reminder->message = $request->message;

                try {
                  $reminder->save();
                  $reminderid[] = $reminder->id;
                  $customerid[] = $row->id;
                }
                catch(Exception $e)
                {
                  $data['success'] = 0;
                  $data['message'] = 'Sorry, unable to create schedule template, please try again later';
                  return response()->json($data);
                }
              }
            }
        }

        if(count($reminderid) > 0 && count($customerid) > 0)
        {
          $newcustomer = array_combine($reminderid,$customerid);
        }
        else
        {
          $data['success'] = 1;
          $data['message'] = 'Your appointment template has been created!';
          return response()->json($data);
        }

        if(count($newcustomer) > 0)
        {
          foreach($newcustomer as $reminder_id=>$customer_id)
          {
            $reminder_customer = new ReminderCustomers;
            $reminder_customer->user_id = $userid;
            $reminder_customer->list_id = $listid;
            $reminder_customer->reminder_id = $reminder_id;
            $reminder_customer->customer_id = $customer_id;

            try {
              $reminder_customer->save();
              $count++;
            }
            catch(Exception $e)
            {
              $data['success'] = 0;
              $data['message'] = 'Sorry, unable to create schedule template, please try again later';
              return response()->json($data);
            }
          }
        }

        //use count to make sure data inserted on for loop
        if($count > 0)
        {
          $data['success'] = 1;
          $data['message'] = 'Your appointment template has been created!';
          return response()->json($data);
        }
        
    }

    public function editAppointmentTemplate(Request $request)
    {
        $userid = Auth::id();
        $template_id = $request->id;
        $template_appt = TemplateAppointments::where([['user_id',$userid],['id',$template_id]])->first();

        if(!is_null($template_appt))
        {
            $data = array(
              'day'=>$template_appt->days,
              'time_send'=>$template_appt->time_sending,
              'msg'=>$template_appt->message,
            );
            return response()->json($data);
        }
    }

    public function deleteAppointmentTemplate(Request $request)
    {
        $userid = Auth::id();
        $template_id = (int)$request->id;
        $reminder = Reminder::where('tmp_appt_id',$template_id)->select('id','image')->get();
        $reminder_id = array();

        if($reminder->count() > 0)
        {
          foreach($reminder as $row)
          {
            $reminder_id[$row->id] = $row->image;
            $reminder_customer = ReminderCustomers::where('reminder_id',$row->id)->get();
            if($reminder_customer->count() > 0)
            {
              ReminderCustomers::where('reminder_id',$row->id)->delete();
            }
          }// ENDFOREACH
        }

        if(count($reminder_id) > 0)
        {
          foreach($reminder_id as $id=>$image)
          {
              if(!empty($image) || $image <> "")
              {
                Storage::disk('s3')->delete($image);
              }
              Reminder::find($id)->delete();
              sleep(0.15);
          }
        }

        try 
        {
          TemplateAppointments::where([['user_id',$userid],['id',$template_id]])->delete();
          $data['success'] = 1;
          $data['message'] = 'Your schedule template deleted successfully';
        }
        catch(QueryException $e)
        {
          $data['success'] = 0;
          $data['message'] = 'Sorry, unable to delete your schedule template, please try again later';
        }
        return response()->json($data);
    }

    public function formAppointment($campaign_id)
    {
        $userid = Auth::id();
        if(empty($campaign_id) || $campaign_id==null)
        {
            return redirect('create-apt');
        }

        $checkid = Campaign::where([['id',$campaign_id],['user_id',$userid]])->first();

        if(is_null($checkid))
        {
            return redirect('create-apt');
        }

        $list = UserList::find($checkid->list_id);

        return view('appointment.form_apt',['id'=>$campaign_id,'list_id'=>$checkid->list_id,'list_label'=>$list->label]);
    }

    public function displayCustomerPhone(Request $request)
    {
        $src = $request->value;
        $list_id = $request->list_id;
        $userid = Auth::id();

        $list = UserList::find($list_id);
        $customer = Customer::where([['user_id',$userid],['list_id','=',$list_id]])->where(function($query) use($src){
                $query->where('name','LIKE','%'.$src.'%')
                ->orWhere('telegram_number','LIKE','%'.$src.'%')
                ;
              }) ->get();

        return view('appointment.customer_apt',['customer'=>$customer,'url'=>$list->name]);
    }

    public function saveAppointmentTime(Request $request)
    {
        $user_id = Auth::id();
        $campaign_id = $request->campaign_id;
        $list_id = $request->list_id;
        $customer_id = $request->customer_id;
        $date_send = $request->date_send;
        $count = 0;
        $reminderid = array();
        $appointments = TemplateAppointments::where([['campaign_id',$campaign_id],['user_id',$user_id]])->get();

        if($appointments->count() > 0)
        {
            foreach($appointments as $row)
            {
              $reminder = new Reminder;
              $reminder->user_id = $user_id;
              $reminder->list_id = $list_id;
              $reminder->campaign_id = $campaign_id;
              $reminder->tmp_appt_id = $row->id;
              $reminder->is_event = 2;
              $reminder->days = $row->days;
              $reminder->hour_time = $row->time_sending;
              $reminder->event_time = $date_send;
              $reminder->message = $row->message;

              try {
                $reminder->save();
                $reminderid[] = $reminder->id;
              }
              catch(QueryException $e)
              {
                $result = array(
                    'success'=>0,
                    'message'=>'Sorry unable to create appointment, please try again later',
                );
                return response()->json($result);
              }
            }
        }  
        else
        {
            $result = array(
                'success'=>0,
                'message'=>'Please create <a target="_blank" href="'.url('edit-apt').'/'.$campaign_id.'">message</a> first, then back here',
            );
            return response()->json($result);
        }

        if(count($reminderid) > 0)
        {
           foreach($reminderid as $id)
           {
              $reminder_customer = new ReminderCustomers;
              $reminder_customer->user_id = $user_id;
              $reminder_customer->list_id = $list_id;
              $reminder_customer->reminder_id = $id;
              $reminder_customer->customer_id = $customer_id;

              try {
                $reminder_customer->save();
                $count++;
              }
              catch(Exception $e)
              {
                $result = array(
                    'success'=>0,
                    'message'=>'Sorry unable to create appointment, please try again later',
                );
                return response()->json($result);
              }
           }
        }
        else
        {
            $result = array(
                'success'=>0,
                'message'=>'Sorry unable to create appointment, please try again later',
            );
            return response()->json($result);
        }

        if($count > 0)
        {
            $result = array(
                'success'=>1,
                'message'=>'Your appointment has been created!',
            );
            return response()->json($result);
        }

    }

    public function delAppointment(Request $request)
    {
        $userid = Auth::id();
        $id = $request->id;
        $delcampaign = false;
        $reminder_id = array();

        try {
          Campaign::where([['id',$id],['user_id',$userid],['type',3]])->delete();
          TemplateAppointments::where([['campaign_id',$id],['user_id',$userid]])->delete();
          $delcampaign = true;
        }
        catch(Exception $e)
        {
          $data['message'] = 'Sorry unable to delete your please try again later';
          return response()->json($data);
        }

        if($delcampaign == true)
        {
            $reminder = Reminder::where([['user_id',$userid],['campaign_id',$id],['is_event',2]])->select('id')->get();
        }

        if($reminder->count() > 0)
        {
            foreach($reminder as $row)
            {
                $reminder_id[] = $row->id;
            }
        }
        
        if(count($reminder_id) > 0)
        {
            try
            {
               foreach($reminder_id as $rid)
               {
                  Reminder::where([['user_id',$userid],['id',$rid],['is_event',2]])->delete();
                  ReminderCustomers::where('reminder_id',$rid)->delete();
               }
               $data['message'] = 'Your appointment deleted successfully';
            }
            catch(Exception $e)
            {
               $data['message'] = 'Sorry unable to delete your please try again later';
            }
            return response()->json($data);
        }
        else
        {
           $data['message'] = 'Your appointment deleted successfully';
           return response()->json($data);
        }
    }

    public function exportAppointment($campaign_id)
    {
        $userid = Auth::id();
        $check = Campaign::where([['id',$campaign_id],['user_id',$userid]])->first();
        $day = Carbon::now()->toDateString();
        $filename = 'appointment-'.$check->name.'-'.$day;

        if(is_null($check))
        {
            return redirect('appointment');
        }

        $data = ReminderCustomers::where([['reminders.campaign_id',$campaign_id],['reminders.is_event',2],['reminders.user_id',$userid]])
        ->join('reminders','reminders.id','=','reminder_customers.reminder_id')
        ->join('customers','customers.id','=','reminder_customers.customer_id')
        ->select('reminders.campaign_id','reminders.event_time','customers.name','customers.telegram_number','customers.id')
        ->distinct()
        ->get();   

         $column[0] = $column[1] = array();

         if($data->count()> 0)
         {
            foreach($data as $row)
            {
                $column[] = array(
                    $row->event_time,
                    $row->name,
                    $row->telegram_number
                );
            }
         }
      
        Excel::create($filename, function($excel) use($column) {

            $excel->sheet('Sheetname', function($sheet) use($column) {

                $sheet->fromArray($column, null, 'A1', false, false);

                $sheet->cell('A1', 'Date Appointment'); 
                $sheet->cell('B1', 'Name Contact'); 
                $sheet->cell('C1', 'WA Contact'); 

            });

        })->export('xlsx');
    }

    /*
    public function exportAppointment($campaign_id){
        $userid = Auth::id();
        $check = Campaign::where('id',$campaign_id)->first();
        $day = Carbon::now()->toDateString();
        $filename = 'appointment-'.$check->name.'-'.$day;

        if(is_null($check))
        {
            return redirect('appointment');
        }

        $data = ReminderCustomers::where([['reminders.campaign_id',$campaign_id],['reminders.is_event',2],['reminders.user_id',$userid]])
        ->join('reminders','reminders.id','=','reminder_customers.reminder_id')
        ->join('customers','customers.id','=','reminder_customers.customer_id')
        ->select('reminders.campaign_id','reminders.event_time','customers.name','customers.telegram_number','customers.id')
        ->distinct()
        ->get();        

        $Excel_file = Excel::create($filename, function($excel) use ($data) {
        $excel->sheet('appointment', function($sheet) use ($data) {
        
          $sheet->cell('A1', 'Date Appointment'); 
          $sheet->cell('B1', 'Name Contact'); 
          $sheet->cell('C1', 'WA Contact'); 

          $dtapt = 'A';
          $namec = 'B';
          $wap = 'C';


          foreach ($data as $row) {
            if(is_null($row)){
              continue;
            }
// $sheet->appendRow(array( "Date Appointment : ".$row->event_time ));
// $sheet->appendRow(array( "Name Contact : ".$row->name ));
// $sheet->appendRow(array( "WA Contact : ".$row->telegram_number ));

            // $username = '@'.$row->username;
            // $sheet->cell($dtapt.'3', $row->event_time); 
            // $sheet->cell($namec.'3', $row->name); 
            // $sheet->cell($wap.'3', $row->telegram_number); 

            // $dtapt++;
            // $namec++;
            // $wap++;
          }
          
          $sheet->fromArray($data);
        });
      })->download('xlsx');

        // return Excel::download(new UsersExport($campaign_id), $filename);
    }
    */

/* end of class */
}