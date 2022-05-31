<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Customer;
use App\UserList;
use App\Message;
use App\Http\Controllers\Controller;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Rules\CheckPlusCode;
use App\Rules\CheckCallCode;
use App\Rules\InternationalTel;
use App\Rules\CheckUserPhone;
use App\Helpers\ApiHelper;
use App\Mail\RegisteredEmail;
use App\Order;
use Auth;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ApiController;

use App\Jobs\SendNotif;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        /*$recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
        $recaptcha_secret = env('GOOGLE_RECAPTCHA_SECRET_KEY');
        $recaptcha_response = $data['recaptcha_response'];

        // Make and decode POST request:
        $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
        $recaptcha = json_decode($recaptcha);
        
        // Take action based on the score returned:
        if ($recaptcha->score >= 0.5) {
            // Verified - send email
        } else {
            // Not verified - show form error
            $error['error_phone'] = 'Error Captcha';
            return response()->json($error);
        }*/


        return Validator::make($data, [
            'username' => ['required','string','max:255'],
            'email' => ['required','string', 'email', 'max:255', 'unique:users'],
            'code_country' => ['required',new CheckPlusCode,new CheckCallCode],
            'phone' => ['required','numeric','digits_between:6,18',new InternationalTel,new CheckUserPhone($data['code_country'],null)]
            //'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $generated_password = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'),0,10);
        $name = $data['username'];
        $timezone = 'Asia/Jakarta';
        $phone = $data['code_country'].$data['phone'];

        $user = User::create([
          'name' => strip_tags($data['username']),
          'email' => strip_tags($data['email']),
          'phone_number'=>strip_tags($data['code_country'].$data['phone']),
          'code_country'=>strip_tags($data['data_country']),
          'password' => Hash::make($generated_password),
          'gender'=>strip_tags($data['gender']),
          'timezone'=>strip_tags($timezone),
        ]);

        if(env('APP_ENV') !== 'local')
        {
					// $list = UserList::find(78);
					$list = UserList::find(15);
					if (!is_null($list) ) {
						$customer = new Customer ;
						$customer->user_id = $list->user_id;
						$customer->list_id = $list->id;
						$customer->name = $user->name;
						$customer->email = $user->email;
						$customer->telegram_number = $phone;
						$customer->is_pay= 0;
						$customer->status = 1;
						$customer->save();
            $customer::create_link_unsubs($customer->id,$list->id);

						if ($list->is_secure) {
							$apiController = new CustomerController;
							$apiController->sendListSecure($list->id,$customer->id,$customer->name,$customer->user_id,$list->name,$phone);
						}
						$customerController = new CustomerController;
						$saveSubscriber = $customerController->addSubscriber($list->id,$customer->id,$customer->created_at,$customer->user_id);
					}
					
          $message = null;
       /*   $message .= 'Welcome to Activrespon,'."\n";
          $message .= 'Your Password is : *'.$generated_password.'*';*/
           
          $message ='';
          $message .= '*Hi '.$data['username']."*, \n\n";
          $message .= '*Welcome to Activrespon*'."\n";
          $message .= "Terima kasih sudah memilih Activrespon sebagai _Tool yang bisa membantu kamu lebih dekat dengan klienmu._ Jika kamu sudah dapat WA ini berarti akunmu sudah terdaftar database kami. \n\n";
          $message .= "Berikut info login kamu : \n";
          $message .= "*Nama :* ".$data['username']."\n";
          $message .= "*Email :* ".$data['email']."\n";
          $message .= '*Password :* '.$generated_password."\n\n";
          $message .= '*Link login:* ';
          $message .= 'https://activrespon.com/dashboard/login'."\n\n";
          $message .= "Oh iya, kalau ada yang ingin ditanyakan, jangan sungkan menghubungi kami di *WA 0877-9663-6121*. \n\n";
          $message .= "Salam hangat, \n";
          $message .= 'Tim Activrespon';

          Mail::to($data['email'])->send(new RegisteredEmail($generated_password,$data['username']));
        }

        return $user;
    }

    protected function ajax_validator(array $data)
    {

        $validator = Validator::make($data, [
            'username' => ['required','string','max:255'],
            'email' => ['required','string', 'email', 'max:255', 'unique:users'],
            'code_country' => ['required',new CheckPlusCode,new CheckCallCode],
            'phone' => ['required','numeric','digits_between:6,18',new InternationalTel,new CheckUserPhone($data['code_country'],null)]
            //'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $err = $validator->errors();
        if($validator->fails() == true)
        {
            $errors = [
              'success'=>0,
              'username'=>$err->first('username'),
              'email'=>$err->first('email'),
              'code_country'=>$err->first('code_country'),
              'phone'=>$err->first('phone'),
            ];
            return response()->json($errors);
        }
        else
        {
            return $this->register_ajax($data);
        }
    }

    public function register(Request $request)
    {   
        $req = $request->all();
        
        if($request->ajax() == true)
        {
          return $this->ajax_validator($req,$request);
        }

        $signup = $this->create($req);
        $order = null;
        

				/* OLD system
        if(session('order') <> null)
        {
          $diskon = 0;
          $kuponid = null;
          $process_order = new OrderController;
          $stat = $process_order->cekharga( session('order')['namapaket'],session('order')['price']);

          $pathUrl = str_replace(url('/'), '', url()->previous());
          if($stat==false)
          {
            return redirect($pathUrl)->with("error", "Paket dan harga tidak sesuai. Silahkan order kembali.");
          }

          if(session('order')['coupon_code'] <> null)
          {
              $arr = $process_order->check_coupon($request->session()->get('order'));

              if($arr['status']=='error')
              {
                return redirect($pathUrl)->with("error", $arr['message']);
              } 
              else 
              {
                $diskon = $arr['diskon'];
                
                if($arr['coupon']!=null){
                  $kuponid = $arr['coupon']->id;
                }
              }
          }

          $data = [
            "user"=> $signup,
            "namapaket"=> session('order')['namapaket'],
            "kuponid"=> $kuponid,
            "price"=> session('order')['price'],
            "priceupgrade"=> 0,
            "diskon"=> $diskon,
            "namapakettitle"=> session('order')['namapakettitle'],
            "phone"=>$req['code_country'].$req['phone'],
            "month"=> session('order')['month'],
          ];
      
          $order = Order::create_order($data);
          Auth::loginUsingId($signup->id);
          return redirect('thankyou');
        }
        else
        {*/
				Auth::loginUsingId($signup->id);
				if ($request->ajax()) {
						return response()->json([
								'success' => 1,
								'email' => $signup->email,
						]);
				}
				else {
          return redirect('home');
				}
        // }

    }

    //REGISTER VIA AJAX
    protected function register_ajax(array $data)
    {
        $signup = $this->create($data);
        $order = null;

        Auth::loginUsingId($signup->id);
        return response()->json([
            'success' => 1,
            'email' => $signup->email,
        ]);
    }

    public function sendToCelebmail($name,$email)
    {
      $fname = "";
      $lname = "";
      $arr_name = explode(" ",$name);
      if (isset($arr_name[0])) {
        $fname = $arr_name[0];
      }
      if (isset($arr_name[1])) {
        $lname = $arr_name[1];
      }
      $lname = "";
      $ch = curl_init();

      //list gabungan curl_setopt($ch, CURLOPT_URL, 'https://celebmail.id/mail/index.php/lists/oj028pjaah5ab/subscribe');
      curl_setopt($ch, CURLOPT_URL, 'https://celebmail.id/mail/index.php/lists/kp521pq7zo813/subscribe');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_POST, 1);
      $post = array(
          'EMAIL' => $email,
          'FNAME' => $fname,
          'LNAME' => $lname,
          'NEWSLETTER_CONSENT' => '1'
      );
      curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

      $result = curl_exec($ch);
      if (curl_errno($ch)) {
          echo 'Error:' . curl_error($ch);
      }
      curl_close($ch);
    }

/* END CONTROLLER */
}
