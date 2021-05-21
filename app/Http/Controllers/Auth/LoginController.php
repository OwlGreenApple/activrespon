<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\OrderController;
use Carbon\Carbon;
use App\User;
use App\Order;
use Cookie,Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    //use AuthenticatesUsers;

    use AuthenticatesUsers {
        logout as performLogout;
    }

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request,$user)
    {
        if($request->remember == 1)
        {
            $this->setCookie($request->email,$request->password);
        }
        else {
            $this->delCookie($request->email,$request->password);
        }

			/*	if ($request->ajax()) {
            Auth::loginUsingId($user->id);
						return response()->json([
								'success' => 1,
								'email' => $request->email,
						]);
				}
				else {*/
						if ( $user->is_admin  == 1) {// do your magic here
								return redirect('list-user');
						}
						if ( $user->is_admin  == 2) {// Halaman woowa
								return redirect('list-woowa');
						}
				// }
    }

    public function loginAjax(Request $request)
    {
        $email = $request->email;
        $password = $request->password;
        $upgrade_price = 0;
        $namapaket = session('order')['namapaket'];
        $pricing = session('order')['price'];

        if(Auth::guard('web')->attempt(['email' => $email, 'password' => $password])) 
        {
            $user = Auth::user();
            $reseller_price = 0;
            $package_upgrade = session('order')['upgrade']; //for coupon package upgrade
            $order_session = session()->pull('order', []); // set session to empty
            $upgrade_price = getPackagePrice($namapaket);

            if($namapaket <> null && $package_upgrade == 0)
            {
              // return true if downgrade
              $result_upgrade = $this->checkDowngrade($namapaket,$user);
            }
            else
            {
              $result_upgrade = 0;
            }

            if($result_upgrade == 1)
            {
               /*$order = new OrderController;
               $upgrade_price = $order->getTotalCount($user,$namapaket);*/
               $order_session['status_upgrade'] = 1;  
            }
            else if($result_upgrade == 2)
            {
               $order_session['status_upgrade'] = 2;  
               // 
            }
            else
            {
               $order_session['status_upgrade'] = 0; 
            }      

            session::put('order',$order_session);

            if(session('order')['diskon'] > 0)
            {
              $price = session('order')['price'];
            }
            else
            {
              $price = '';
            }

            if($user->reseller_id > 0)
            {
              $percent = 10;
              $discount = ($pricing * $percent/100);
              $reseller_price = $pricing - $discount;
              $price = (int)$pricing;
            }

            return response()->json([
                'success' => 1,
                'email' => $request->email,
                'status_upgrade'=>session('order')['status_upgrade'],
                'price'=>$price,
                'total'=>number_format($upgrade_price),
                'reseller_price'=>$reseller_price
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Not Credential Account'
            ]);
        }
    }

    public function checkDowngrade($namapaket,$user)
    {
     /* $order = Order::where('user_id',$user->id)
                ->where("status",2)
                ->orderBy('created_at','desc')
                ->first();*/

     /* if($user->membership <> null) 
      {
          $current_package = $order->package;
      }
      else
      {
          $current_package = $user->membership;
      }*/

      $current_package = $user->membership;
      $day_left = $user->day_left;

      if($current_package == null)
      {
         return 0;
      }

      $data = [
          'current_package'=>$current_package,
          'order_package'=>$namapaket,
      ];
      
      $get_status = checkMembershipDowngrade($data);

      if($get_status == true)
      {
        $status_upgrade = 2;
      }
      else
      {
        $status_upgrade = 1;
      }

      return $status_upgrade;
    }

    private function setCookie($email,$password)
    {
        if(!empty($email) && !empty($password))
        {
            Cookie::queue(Cookie::make('email', $email, 1440*7));
            Cookie::queue(Cookie::make('password', $password, 1440*7));
        } else {
            return redirect()->route('login');
        }
    }

    private function delCookie($cookie_email,$cookie_pass)
    {
        if(!empty($cookie_email) && !empty($cookie_pass))
        {
            Cookie::queue(Cookie::forget('email'));
            Cookie::queue(Cookie::forget('password'));
        } else {
            return redirect()->route('login');
        }
    }

    public function logout(Request $request)
    {
        // session_start();
        $this->performLogout($request);
        return redirect('/');
    }

/* end class LoginController */    
}
