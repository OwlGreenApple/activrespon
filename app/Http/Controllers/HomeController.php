<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\ApiUserController;
use App\Imports\UsersImport;
use App\User;
use App\PhoneNumber;
use App\Config;
use App\Server;
use App\UserList;
use App\Customer;
use App\Campaign;
use App\Reminder;
use App\ReminderCustomers;
use App\BroadCast;
use App\BroadCastCustomers;
use App\Order;
use App\Reseller;
use App\Phoneapis;
use DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //session_start();
        $user = Auth::user();
        $id = $user->id;
        $user_name = $user->name;
        $folder = $user_name.'-'.$id;

        /*if (env('APP_ENV') == 'local'){
          $directory = public_path().'/ckeditor/'.$folder;
        }
        else {
          $directory = 'home2/activwa/public_html/ckfinder/'.$folder;
        }*/

        $directory = public_path().'/ckeditor/'.$folder;

        if(!file_exists($directory))
        {
            mkdir($directory, 0755,true);
            //$path = $directory;
            //File::makeDirectory($path, $mode = 0741, true, true);
        }

        $lists = UserList::where('user_id',$id)->get()->count();
        $campaign = Campaign::where('user_id',$id)->get()->count();
        $contact = Customer::where('user_id',$id)->get()->count();
        $phone_number = PhoneNumber::where('user_id',$id)->first();
        $server = Config::where('config_name','status_server')->first();
        
        if(!is_null($server))
        {
          if($server->value == 'active')
          {
             $server_status = '<span class="span-connected">'.$server->value.'</span>'; 
          }
          else
          {
             $server_status = '<span class="down">'.$server->value.'</span>';
          }
        }
        else
        {
          $server_status = '-';
        } 

        if(!is_null($phone_number))
        {
          $phone_id = $phone_number->id;
          if($phone_number->status == 2)
          {
            $phone_status = '<span class="span-connected">Connected</span>';
          }
          else
          {
            $phone_status = '<span class="down">Disconnected</span>';
          }
          
        }
        else
        {
          $phone_status = '-';
        }
        

        $latest_list = DB::select('select * from lists where user_id ='.$id.' and DATE(created_at) > (NOW() - INTERVAL 7 DAY)');
        (count($latest_list) > 0)? $latest = '+'.count($latest_list) : $latest = count($latest_list);

        $reminder = ReminderCustomers::where([['user_id','=',$id],['status','=',0]])->get()->count();

        $reminder_sent = ReminderCustomers::where([['user_id','=',$id],['status','>',0]])->get()->count();

        $broadcast = BroadCast::where([['broad_casts.user_id','=',$id],['broad_cast_customers.status','=',0]])
            ->join('broad_cast_customers','broad_cast_customers.broadcast_id','=','broad_casts.id')->get()->count();

        $broadcast_sent = BroadCast::where([['broad_casts.user_id','=',$id],['broad_cast_customers.status','>',0]])
            ->join('broad_cast_customers','broad_cast_customers.broadcast_id','=','broad_casts.id')->get()->count();

        $total_message = $reminder + $broadcast;
        $total_sending_message = $reminder_sent + $broadcast_sent;

        $expired = Carbon::now()->addDays($user->day_left)->toDateString();
        $phone = PhoneNumber::where('user_id',$id)->first();

        if(is_null($phone))
        {
            $max_counter = 0;
        }
        else
        {
            $max_counter = number_format($phone->max_counter);
        }

        // STATISTIC

        //LIST
        $graphlist = Customer::where('user_id',$id)->select(DB::raw('COUNT(DATE_FORMAT(created_at,"%Y-%m-%d")) AS total_contacts,DATE_FORMAT(created_at,"%Y-%m-%d") AS join_date'))->groupBy('join_date')->orderBy('join_date','asc');

        $total_graphic = $graphlist->get()->count() - 30;

        if($total_graphic < 30)
        {
          $graph_contacts = $graphlist->get();
        }
        else
        {
          $graph_contacts = $graphlist->skip($total_graphic)->take(30)->get();
        }

        $graph_list = array();
        if($graph_contacts->count() > 0)
        {
          foreach($graph_contacts as $row)
          {
            $graph_list[$row->join_date] = $row->total_contacts;
          }
        }

        //MESSAGE
        $graph_message = array();
        $graph_message = DB::select(DB::raw('SELECT COUNT(*) AS total_messages, updated_at  FROM (
            SELECT DATE_FORMAT(broad_cast_customers.updated_at,"%Y-%m-%d") AS updated_at FROM broad_cast_customers
              JOIN broad_casts ON broad_casts.id = broad_cast_customers.broadcast_id
                WHERE broad_casts.user_id = "'.$id.'" AND broad_cast_customers.status > 0
  
              UNION ALL
  
              SELECT DATE_FORMAT(updated_at,"%Y-%m-%d") FROM reminder_customers WHERE user_id = "'.$id.'" AND status > 0
            ) AS baseview
            GROUP BY updated_at
            ORDER BY updated_at ASC
            '));

        $graph_send_message = array();
        if(count($graph_message) > 0)
        {
          foreach($graph_message as $row)
          {
            $graph_send_message[$row->updated_at] = $row->total_messages;
          }
        }

        $total_send_message = count($graph_send_message) - 30; 

        if($total_send_message < 30)
        {
          $graph_send = $graph_send_message;
        }
        else
        {
          $start_position = $total_send_message - 1;
          $graph_send = array_slice($graph_send_message,$start_position,29);//due the array start from 0
        }
        
        $data = array(
          'lists'=>$lists,
          'latest_lists'=>$latest,
          'campaign'=>$campaign,
          'contact'=>$contact,
          'total_message'=>$total_message,
          'total_sending_message'=>$total_sending_message,
          'membership'=>$user->membership,
          'expired'=>Date("d M Y",strtotime($expired)),
          'status'=>$user->status,
          'quota'=>$max_counter,
          'phone_status'=>$phone_status,
          'server_status'=>$server_status,
          'graph_contacts'=>$graph_list,
          'graph_messages'=>$graph_send,
          'user'=>$user,
        );

        return view('home',$data);
    }

    public function jsonEncode(Request $req)
    {
      return json_encode($req->data);
    }

    public function google_form()
    {
      $user = Auth::user();
      $key = "";
      $phoneNumber = PhoneNumber::
                      where("user_id",$user->id)
                      ->first();
      if (!is_null($phoneNumber)) {
        $key = $phoneNumber->filename;
      }      
      return view('google-form',["key"=>$key]);
    }

    public function updateUser(Request $request){
        $user = User::where('id','=',Auth::id())->update(
            [
                'name'=> $request->name,
                'password'=>Hash::make($request->password)
            ]
        );

        if($user == true){
            return redirect('home')->with('message','Your data has been updated successfully');
        } else {
            return redirect('home')->with('message','Error!,Your data failed to update');
        }
    }

    public function checkPhone(){
      $userid = Auth::id();
      $phone = PhoneNumber::where('user_id',$userid)->get();
      $lists = UserList::where('user_id',$userid)->get();
      $user = User::find($userid);
      $user_status = $user->status;
      
      if($user_status < 1 && $lists->count() < 1)
      {
        $data['status'] = 'buy';
      }
      elseif($user_status < 1 && $lists->count() > 0) 
      {
        $data['status'] = 'exp';
      } 
      elseif($phone->count() < 1) 
      {
        $data['status'] = 'phone';
      }
      else {
        $data['status'] = 0;
      }
      
      return response()->json($data);
    }

    /* test */
    public function historyOrder()
    {
      return view('auth.history-order');
    }

    public function stop_start(Request $request){
        $user = Auth::user();
        if ($user->is_started) {
          $user->is_started = 0;
        }
        else {
          $user->is_started = 1;
        }
        $user->save();

        $arr['isStarted'] = $user->is_started;
        $arr['status'] = "success";
        return $arr;
    }

    public function change_speed(Request $request){
        $user = Auth::user();
        $user->speed = $request->speed;
        $user->save();

        $arr['status'] = "success";
        return $arr;
    }    

    /* RESELLER */

    public function reseller_home()
    {
       return view('reseller.home');
    }

    /* DISPLAY AVAILABLE / DELETED USER DATA */
    public function reseller_user_data(Request $request)
    {
      $is_delete = $request->is_del;
      $data = Phoneapis::where([['user_id',Auth::id()],['is_delete','=',$is_delete]])->orderBy('id','desc')->get();
      return view('reseller.home-data',['data'=>$data]);
    }

    /* DISPLAY INVOICE */
    public function invoice(Request $request)
    {
      $orders = Order::where([['user_id',Auth::user()->id],['package','LIKE',"%WA Reseller%"]])
                ->orderBy('created_at','desc')
                ->paginate(15);

       if($request->ajax())
       {
          return view('reseller.content',['orders'=>$orders,'pager'=>$orders]);
       }

       return view('reseller.index',['orders'=>$orders,'pager'=>$orders]);
    }

    /* DISPLAY DETAIL INVOICE */
    public function monthly_report($current_month,$userid)
    {
      if(env('APP_ENV') == 'local')
      {
        $connection = 'activrespons.phone_apis AS pa';
      }
      else
      {
        $connection = 'activres_project.phone_apis AS pa';
      }

      // DETERMINE DATA TAKE BY ADMIN OR BY RESELLER USER
      if($userid == 0)
      {
        $id = Auth::id();
      }
      elseif(Auth::user()->is_admin == 1 && $userid <> 0)
      {
        $id = $userid;
      }
      else
      {
        return 'Invalid Page';
      }
      
      // $current_month = Carbon::now()->format('m-Y');
      $order = Reseller::where([['resellers.user_id','=',$id],['resellers.period','=',$current_month]])
              ->join($connection,'resellers.phone_api_id','=','pa.id')
              ->select('resellers.*','pa.phone_number','pa.quota')
              ->get();

      //total invoice
      $total = Reseller::where([['user_id','=',$id],['period','=',$current_month]])->selectRaw('SUM(total) AS gt')->first();

    /*  // total phone numbers
      $total_phone = Reseller::where([['resellers.user_id','=',$id],['resellers.period','=',$current_month],['pa.phone_number','<>',null]])
              ->join('activrespons.phone_apis AS pa','resellers.phone_api_id','=','pa.id')
              ->selectRaw('COUNT(*) AS gt')->first();

      // total NO phone numbers
      $total_no_phone = Reseller::where([['resellers.user_id','=',$id],['resellers.period','=',$current_month],['pa.phone_number','=',null]])
              ->join('activrespons.phone_apis AS pa','resellers.phone_api_id','=','pa.id')
              ->selectRaw('COUNT(*) AS gt')->first();*/

      // total packages 1
      $total_package_1 = Reseller::where([['user_id','=',$id],['period','=',$current_month],['package','=','Paket 1 WA']])->selectRaw('COUNT(*) AS gt')->first();

      // total packages 2
      $total_package_2 = Reseller::where([['user_id','=',$id],['period','=',$current_month],['package','=','Paket 2 WA']])->selectRaw('COUNT(*) AS gt')->first();

      // total packages 3
      $total_package_3 = Reseller::where([['user_id','=',$id],['period','=',$current_month],['package','=','Paket 3 WA']])->selectRaw('COUNT(*) AS gt')->first();

      $data = [
        'data'=>$order,
        'total'=>$total,
        'total_package_1' =>$total_package_1,
        'total_package_2' =>$total_package_2,
        'total_package_3' =>$total_package_3,
        'package'=> new ApiUserController
      ];

      return view('reseller.reseller',$data);
    }

/* end class HomeController */
}
