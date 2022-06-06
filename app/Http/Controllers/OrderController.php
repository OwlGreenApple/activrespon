<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Account;
use App\HistorySearch;
use App\User;
use App\Group;
use App\Save;
use App\Coupon;
use App\Order;
use App\UserLog;
use App\Notification;

use App\Helpers\NewCustomHelpers;
use Carbon\Carbon;
use Auth,Mail,Validator,Storage,DateTime,Crypt,Session,stdClass;

class OrderController extends Controller
{   
  public function cekharga($namapaket, $price){
    //cek paket dengan harga
    $paket = getPackagePrice($namapaket);
   
    if((int)$price !== $paket){
      return false; 
    } else {
      return true;
    }
  }

  public function summary(){
    //halaman order user
    if(session('order') == null)
    {
      return redirect('pricing');
    }

    return view('order.summary');
  }
  
  public function pricing(Request $request)
  {
    return view('order.pricing');
  }

  public function pricing_list(Request $request)
  {
       if($request->default == null)
       {
          $arr = [1,2,3]; /* 3 month */
          $save = 15;
       }
       else
       {
          $arr = [4,5,6]; /* yearly */
          $save = 40;
       }
       
       return view('order.pricing-list',['data'=>$arr,'save'=>$save,'default'=>$request->default]);
  }

  public function checkout($id,$coupon_reseller = null){
    //halaman checkout

    if(session('order') <> null)
    {
      session::forget('order');
    }

    // DELETE SESSION RESELLER PRICE
    if(session('reseller_coupon_price') <> null)
    {
      session::forget('reseller_coupon_price');
    }

		$priceupgrade = 0;
		$dayleft = 0;
    $auth_reseller = FALSE;

		if (Auth::check()) {
			$user = Auth::user();
			$order = Order::where('user_id',$user->id)
								->where("status",2)
                ->orderBy('created_at','desc')
								->first();
			if (!is_null($order)) {
				$priceupgrade = $order->total;
			}
			$dayleft = $user->day_left;

      // TO CHECK WHEN LOGGED IN USER ARE RESELLER MEBER
      if($user->reseller_id > 0)
      {
        $auth_reseller = TRUE;
      }
      /*if($chat == 1 && !is_null($order)){
        $priceupgrade+=100000;
      }*/
		}

    // coupon reseller
    $is_coupon = false;
    $coupon = Coupon::where([['kodekupon',$coupon_reseller],['used','=',0]])->first();

    if(!is_null($coupon) || $auth_reseller == TRUE)
    {
      $is_coupon = true;
    }

    return view('order.checkout')->with(array(
              'id'=>$id,
              'priceupgrade'=>$priceupgrade,
              'dayleft'=>$dayleft,
              'is_coupon'=>$is_coupon,
              'coupon_reseller'=>$coupon_reseller,
              'prices'=>getPackage()
            ));
  }

  public function check_coupon(Request $request)
  {
    // dd($request->all());
    $user = NULL;
    if (Auth::check()) {
      $user = Auth::user(); 
    }

    $status_upgrade = $request->status_upgrade;
    $reseller_coupon = $request->reseller_coupon;

    if($reseller_coupon == "0")
    {
      $reseller_coupon = null;
    }

    //cek kodekupon

    $package = substr($request->namapaket,0,-1);

    if($package == 'basic')
    {
      $chat_price = 100000;
    }
    elseif($package == 'bestseller')
    {
      $chat_price = 200000;
    }
    else
    {
      $chat_price = 300000;
    }

    if($request->harga == null)
    {
       $pricing = (int)$request->price;
    }
    else
    {
       $pricing = (int)$request->harga;
    }

    if($request->chat == 1)
    {
      $pricing += $chat_price;
    }

    $arr['status'] = 'success';
    $arr['message'] = '';
    $arr['totaltitle'] = number_format($pricing, 0, '', '.');
    $arr['total'] = $pricing;
    $arr['diskon'] =  0;
    $arr['dayleft'] =  0;
    $arr['coupon'] = null;
    $arr['price'] = '';
    $arr['packageupgrade'] = 0;
    $arr['upgrade_price'] = 0;
    $arr['membership'] = null;
    $total = 0;
    $diskon = 0;
    $check_membership = null;

    /*if(Auth::check())
    {
      $check_membership = $this->check_upgrade($request);
      $arr['membership'] = $check_membership['membership'];
      $arr['total'] = $check_membership['priceupgrade'];
      $arr['upgrade_price'] = $check_membership['upgrade_price'];
      $arr['dayleft'] = $check_membership['dayleft'];
      $arr['packageupgrade'] = $check_membership['packageupgrade'];
    }
    */
    if($status_upgrade == null)
    {
      $arr['total'] = $pricing;
    }

    /*RESELLER COUPON*/
    if($reseller_coupon !== null):
      $coupon = Coupon::where([['kodekupon',$reseller_coupon],['used',0]])->first();

      if(is_null($coupon))
      {
        $arr['status'] = 'error';
        $arr['message'] = 'Kupon tidak valid.';
      }
      else
      {
        $diskon = $pricing * ($coupon->diskon_percent/100);
        $total = $pricing - round($diskon);
        $arr = self::box($total,$diskon,$coupon,$pricing);
        $id_coupon = $coupon->id;

        $reseller = [
          'price'=>$total,
          'kuponid'=>$id_coupon,
        ];
        session(['reseller_coupon_price'=>$reseller]);
      }

      return $arr;
    endif; /*END RESELLER COUPON */

    /*REGISTERED RESELLER*/
    if($user !== null)
    {
      $percent = 10;
      $reseller_id = $user->reseller_id;
      if($reseller_id > 0)
      {
        $diskon = $pricing * ($percent/100);
        $total = $pricing - round($diskon);
        $arr = self::box($total,$diskon,null,$pricing,'reg');
        return $arr;
      }
    }

    if($request->kupon <> null)
    {
      $user_id = 0;
      if (!is_null($user)) {
        $user_id = $user->id;
      }
      $coupon = Coupon::where('kodekupon',$request->kupon)
              ->where(function($query) use ($request) {
                $query->where('package_id',$request->idpaket)
                      ->orwhere('package_id',0);
              })
              ->where(function($query) use ($user_id) {
                $query->where('user_id',$user_id)
                      ->orwhere('user_id',0);
              })
              ->first();

      if(is_null($coupon)){
        $arr['status'] = 'error';
        $arr['message'] = 'Invalid coupon.';
        return $arr;
      } 
			else {
        // $now = new DateTime();
        // $date = new DateTime($coupon->valid_until);

        if($arr['total'] <> 0)
        {
          $pricing = $arr['total'];
        }

        $now = Carbon::now();
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $coupon->valid_until);
        
        if($date->lt($now)){
          $arr['status'] = 'error';
          $arr['message'] = 'Coupon has expired.';
          return $arr;
        } 
        else 
        {
          /*if($coupon->valid_to=='new' and Auth::check()){
              //
          } else if($coupon->valid_to=='extend' and !Auth::check()){
              //
          } 
          else */
          if(($coupon->valid_to=='') || ($coupon->valid_to=='expired-membership') || ($coupon->valid_to=='all') && $coupon->coupon_type == 1 )
          {
            if($coupon->diskon_value == 0 && $coupon->diskon_percent <> 0){
              $diskon = $pricing * ($coupon->diskon_percent/100);
              $total = $pricing - round($diskon);
            } else {
              $diskon = $coupon->diskon_value;
              $total = $pricing - $coupon->diskon_value;
            }

            if($total < 0)
            {
              $total = 0;
            }

           /* $arr['status'] = 'success';
            $arr['message'] = 'Coupon valid, can be use now';
            $arr['totaltitle'] = number_format($total, 0, '', '.');
            $arr['total'] = $total;
            $arr['diskon'] = $diskon;
            $arr['coupon'] = $coupon;
            $arr['price'] = (int)$pricing;*/
            $arr = self::box($total,$diskon,$coupon,$pricing);
            return $arr;
          }
          elseif($coupon->coupon_type == 2)
          {
            $check_package = $this->filter_package($request->idpaket);

            if($check_package == true)
            {
              return $this->getUpgradeCoupon($request->idpaket,$coupon);
            }
            else
            {
              $arr['status'] = 'error';
              $arr['message'] = 'Invalid package';
              return $arr;
            }
          }
          elseif($coupon->coupon_type == 3)
          {
            $check_package = $this->filter_package($request->idpaket);
            if($check_package == true)
            {
              return self::check_watchermarket_coupon($request->idpaket,$coupon);
            }
            else
            {
              $arr['status'] = 'error';
              $arr['message'] = 'Invalid package';
              return $arr;
            }
          }
          /**/
        }

      }
    } /*end request->coupon logic*/

    return $arr;
  }

  private static function box($total,$diskon,$coupon,$pricing,$reg_user = null)
  {
    if($reg_user == null)
    {
      $message = 'Kupon valid dan bisa dipakai';
    }
    else
    {
      $message = '';
    }

    $arr['status'] = 'success';
    $arr['message'] = $message;
    $arr['totaltitle'] = number_format($total, 0, '', '.');
    $arr['total'] = $total;
    $arr['diskon'] = $diskon;
    $arr['coupon'] = $coupon;
    $arr['price'] = (int)$pricing;
    return $arr;
  }

  private static function check_watchermarket_coupon($package_id,$coupon)
  {
    $package = getPackage($package_id,1)['price'];

    if($package_id < 2)
    {
         $arr['status'] = 'error';
         $arr['message'] = 'Kupon hanya berlaku untuk pembelian paket minimal bestseller1';
         $arr['total'] = $package;
         return $arr;
    }

    $total = $package - $coupon->diskon_value;

    $arr['status'] = 'success';
    $arr['message'] = 'Kupon valid dan bisa dipakai';
    $arr['coupon'] = $coupon;
    $arr['total'] = $total;
    $arr['price'] = (int)$package;
    $arr['diskon'] = $coupon->diskon_value;
    return $arr;
  }

  public function getUpgradeCoupon($package_id,$coupon)
  {
      $previous_price = getPackage($package_id,1)['price'];

      if($package_id <= 3 )
      {
         $arr['status'] = 'error';
         $arr['message'] = 'Kupon hanya berlaku untuk pembelian paket diatas paket1 (basic1, bestseller1, supervalue1)';
         $arr['total'] = $previous_price;
         return $arr;
      }

      $id_upgarde = (int)$package_id - 3;
      $upgrade_promo = getPackage($id_upgarde,1);

      $arr['status'] = 'success';
      $arr['message'] = 'Kupon berhasil dipakai & berlaku sekarang';
      $arr['totaltitle'] = number_format($upgrade_promo['price'], 0, '', '.');
      $arr['coupon'] = $coupon;
      $arr['total'] = $upgrade_promo['price'];
      $arr['price'] = (int)$previous_price;
      $arr['diskon'] = 0;
      $arr['upgrade'] = 1;
      return $arr;
  }

	public function check_upgrade(Request $request)
  {
		$arr['status'] = 'success';
    $arr['membership'] = 0;
    /*$arr['upgrade_price'] = 0;
    $arr['dayleft'] = 0;
    $arr['packageupgrade'] = 0;*/

     //check package
    $getPackage = getPackage($request->idpaket,1);
    $package_name = $getPackage['package'];
    // $package_price = $getPackage['price'];

		$priceupgrade = 0;
		$dayleft = 0;

		if (Auth::check()) 
    {
			$user = Auth::user();
      //$dayleft = $user->day_left;

			/*$order = Order::where('user_id',$user->id)
								->where("status",2)
                ->orderBy('created_at','desc')
								->first();*/

			if($user->membership == null) 
      {
          return $arr;
			}
      else
      {
          $package_order = $user->membership;
      }

      // new order
     /* if($package_order == null || $dayleft < 1)
      {
         $arr['priceupgrade'] = $request->harga;
         return $arr;
      }

      //if downgrade or upgrade later
      if($request->status_upgrade == 2 || $request->status_upgrade == null || $dayleft == 0)
      {
        $arr['priceupgrade'] = $package_price;
        return $arr;
      }*/

      $data = [
        'current_package'=>$package_order,
        'order_package'=>$package_name,
      ];

      $check_membership = checkMembershipDowngrade($data);

      //return true if downgrade, false if upgrade
      if($check_membership == true)
      {
        //downgrade
        $arr['status'] = 'success';
        $arr['membership'] = 2;
        // $arr['priceupgrade'] = $package_price;
      }
      else
      {
        //upgrade
        $arr['status'] = 'success';
        $arr['membership'] = 1;
        //$arr['priceupgrade'] = $package_price;

       /* $get_new_order_day = getAdditionalDay($package_name);
        $get_old_order_day = getAdditionalDay($package_order);

        $remain_day_price = $this->getUpgradeNow($package_price,$get_new_order_day,$oldpackage_price,$get_old_order_day,$dayleft);

        $arr['upgrade_price'] = $package_price;
        $arr['dayleft'] = $dayleft;
        $arr['packageupgrade'] = round($remain_day_price);
        $arr['priceupgrade'] = round($package_price + $remain_day_price);*/
      }
    }

    return $arr;
	}

  public function filter_package($package_id)
  {
    /**
      TO PREVENT USER CHANGE PACKAGE VALUE ON PACKAGE LIST
    **/
      
    $packages = array(
      1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27
    );

    if(in_array($package_id, $packages))
    {
        return true;
    }
    else
    {
        return false;
    }

  }

  public function getUpgradeNow($newpackage,$package_day,$oldpackage,$old_package_day,$day_left)
  {
    $upgrade_new = $newpackage/$package_day * $day_left;
    $upgrade_old = $oldpackage/$old_package_day * $day_left;
    $upgrade_now = $upgrade_new - $upgrade_old;
    return $upgrade_now;
  }
	
	//store to session at first
  public function submit_checkout(Request $request) {
		// ditaruh ke session dulu
    $stat = $this->cekharga($request->namapaket,$request->price);
    $status_upgrade = $chat_price = 0;
    $base_price = (int)$request->price;
    $coupon = array();

    $pathUrl = str_replace(url('/'), '', url()->previous());
    if($stat==false){
      // return redirect("checkout/1")->with("error", "Paket dan harga tidak sesuai. Silahkan order kembali.");
      return redirect($pathUrl)->with("error", "Paket dan harga tidak sesuai. Silahkan order kembali.");
    }

    $arr = $this->check_coupon($request);
    if($arr['status']=='error'){
      // return redirect("checkout/1")->with("error", $arr['message']);
      return redirect($pathUrl)->with("error", $arr['message']);
    }

		$month = 1;
		if(substr($request->namapaket,0,5) === "basic"){
			$month = 1;
      // $chat_price = 100000;
    }
		if(substr($request->namapaket,0,10) === "bestseller"){
			$month = 2;
      // $chat_price = 200000;
    }
		if(substr($request->namapaket,0,10) === "supervalue"){
			$month = 3;
      // $chat_price = 300000;
    }

		$total =  $base_price;
    $diskon = 0;
    // $total = $request->price;
    $kuponid = $upgrade_package = null;

    /*CHAT ENABLE*/
    /*if($request->chat == 1)
    {
      if($request->kupon == null)
      {
         $total += $chat_price;
      }
      $base_price += $chat_price;
    }*/

    // USE NORMAL COUPON
    if($request->kupon <> null){
      $coupon = $this->check_coupon($request);

      if($coupon['status']=='error')
      {
        return redirect($pathUrl)->with("error", $coupon['message']);
      } else {
        $diskon = $coupon['diskon'];
        // $total = $arr['total'];
        
        if($coupon['coupon']!=null){
          $kuponid = $coupon['coupon']->id;
        }

        $total = $coupon['total'];

        /*if(isset($arr['upgrade']))
        {
          $upgrade_package = $arr['total'];
        }*/
      /**/
      }
    }
    
    /*RESELLER COUPON*/
    $reseller = false;
    if(session('reseller_coupon_price') !== null)
    {
      $total = session('reseller_coupon_price')['price'];
      $diskon = (int)$base_price - (int)$total;
      $kuponid = session('reseller_coupon_price')['kuponid'];
      $reseller = true;
    }

    $order = array(
      "price"=>$base_price,
      "namapaket"=>$request->namapaket,
      "namapakettitle"=>$request->namapakettitle,
      "coupon_code"=>$request->kupon,
      "idpaket" => $request->idpaket,
      "month" => $month,
      "kuponid" => $kuponid,
      "priceupgrade" => $request->priceupgrade,
      "diskon" => $diskon,
      "total"=> $total,
      // "upgrade"=>$upgrade_package,
      "upgrade"=>0,
      "status_upgrade"=>$status_upgrade,
      "priceupgrade"=>0,
      "chat_price"=> $chat_price,
      "chat"=> 0,
      "reseller"=>$reseller
    );

    // dd($order);

    if(session('order') == null)
    {
      session(['order'=>$order]);
    }
    
    return redirect('summary');
  }

   public function submit_summary(Request $request){
    $user = Auth::user();

    if(session('order') == null)
    {
        return redirect('pricing');
    }

    if($request->status_upgrade <> null)
    {
        $status_upgrade = $request->status_upgrade;
    }
    else
    {
        $status_upgrade = session('order')['status_upgrade'];
    }

    $diskon = session('order')['diskon'];
     // in case if user have reseller_id
    $disc_reseller = 10; //change this variable if discount change
    if($user->reseller_id > 0)
    {
      $total = session('order')['price'];
      $diskon = ($total * $disc_reseller)/100;
    }

    $data = [
      "user"=> $user,
      "namapaket"=> session('order')['namapaket'],
      "kuponid"=> session('order')['kuponid'],
      "price"=> session('order')['price'],
      "priceupgrade"=> session('order')['priceupgrade'],
      "diskon"=> $diskon,
      "namapakettitle"=> session('order')['namapakettitle'],
      "phone"=>$user->phone_number,
      "month"=> session('order')['month'],
      "total"=>session('order')['total'],
      "upgrade"=>session('order')['upgrade'],
      "status_upgrade"=>$status_upgrade,
      "chat"=>session('order')['chat'],
      "reseller"=>session('order')['reseller']
    ];

    $order = Order::create_order($data);
    return view('order.thankyou')->with(array(
              'order'=>$order,    
            ));
  }

  // for submit summary
  public function getTotalCount($user,$namapaket)
    {
      $order = Order::where('user_id',$user->id)
                ->where("status",2)
                ->orderBy('created_at','desc')
                ->first();

      if (!is_null($order)) 
      {
        $current_package = $order->package;
      }
      else
      {
        $current_package = $user->membership;
      }

      $dayleft = $user->day_left;
      $diskon = session('order')['diskon'];

      $package_price = getPackagePrice($namapaket);
      $oldpackage_price = getPackagePrice($current_package);

      $get_new_order_day = getAdditionalDay($namapaket);
      $get_old_order_day = getAdditionalDay($current_package);

      if($dayleft < 1 || $current_package == null)
      {
          return $package_price;
      } 

      $remain_day_price = $this->getUpgradeNow($package_price,$get_new_order_day,$oldpackage_price,$get_old_order_day,$dayleft);
      $remain_day_price = round($remain_day_price);

      $total_price = $package_price + $remain_day_price - $diskon;

      $order = session()->pull('order', []); 
      $order['price'] = $package_price;
      $order['priceupgrade'] = $remain_day_price;
      session::put('order',$order);
     
      return $total_price;
  }

  public function getStatusUpgrade(Request $request)
  {
      $user = Auth::user();
      $namapaket = session('order')['namapaket'];
      $diskon = session('order')['diskon'];

      if($request->status_upgrade == 2 || $request->status_upgrade == null)
      {
          $upgrade_price = getPackagePrice($namapaket);
          $total_price = $upgrade_price - $diskon;

          $order = session()->pull('order', []); 
          $order['price'] = $upgrade_price;
          $order['priceupgrade'] = 0;
          session::put('order',$order);
      }
      else
      {
          $total_price = $this->getTotalCount($user,$namapaket); 
      }

      if($diskon > 0)
      {
          $price = session('order')['price'];
      }
      else
      {
          $price = '';
      }
      
      return response()->json(['total'=>$total_price,'price'=>$price]);
  }

	/*//order dengan register
  public function submit_checkout_register(Request $request) {
		// ditaruh ke session dulu
    $stat = $this->cekharga($request->namapaket,$request->price);

    $pathUrl = str_replace(url('/'), '', url()->previous());
    if($stat==false){
      // return redirect("checkout/1")->with("error", "Paket dan harga tidak sesuai. Silahkan order kembali.");
      return redirect($pathUrl)->with("error", "Paket dan harga tidak sesuai. Silahkan order kembali.");
    }

    $arr = $this->check_coupon($request);
    if($arr['status']=='error'){
      // return redirect("checkout/1")->with("error", $arr['message']);
      return redirect($pathUrl)->with("error", $arr['message']);
    }

		$month = 1;
		if(substr($request->namapaket,0,5) === "basic"){
			$month = 1;
    }
		if(substr($request->namapaket,0,10) === "bestseller"){
			$month = 2;
    }
		if(substr($request->namapaket,0,10) === "supervalue"){
			$month = 3;
    }

    $order = array(
      "price"=>$request->price,
      "namapaket"=>$request->namapaket,
      "namapakettitle"=>$request->namapakettitle,
      "coupon_code"=>$request->kupon,
      "idpaket" => $request->idpaket,
      "month" => $month,
    );

    if(session('order') == null)
    {
      session(['order'=>$order]);
    }
    
    return redirect('register');
  }*/

	
  //checkout klo uda login
  public function submit_checkout_login(Request $request){
    //buat order user lama
    $stat = $this->cekharga($request->namapaket,$request->price);

    $pathUrl = str_replace(url('/'), '', url()->previous());
    if($stat==false){
      // return redirect("checkout/1")->with("error", "Paket dan harga tidak sesuai. Silahkan order kembali.");
      return redirect($pathUrl)->with("error", "Paket dan harga tidak sesuai. Silahkan order kembali.");
    }

    $check_package = $this->filter_package($request->idpaket);
    if($check_package == false)
    {
        return redirect($pathUrl)->with("error", "Paket dan harga tidak sesuai. Silahkan order kembali.");
    }

    /*if($arr['status']=='error'){
      // return redirect("checkout/1")->with("error", $arr['message']);
      return redirect($pathUrl)->with("error", $arr['message']);
    }*/

		$month = 1;
		if(substr($request->namapaket,0,5) === "basic"){
			$month = 1;
      // $chat_price = 100000;
    }
		if(substr($request->namapaket,0,10) === "bestseller"){
			$month = 2;
      // $chat_price = 200000;
    }
		if(substr($request->namapaket,0,10) === "supervalue"){
			$month = 3;
      // $chat_price = 300000;
    }

    $diskon = 0;
    $pricing_upgrade = $upgrade_package = 0;
    $price = (int)$request->price;
    $kuponid = null;

    //if enable chat feature
   /* if($request->chat == 1)
    {
      $price += $chat_price;
    }*/

    if($request->kupon <> null)
    {
      $kpn = $this->check_coupon($request);

      if($kpn['status']=='error'){
        return redirect($pathUrl)->with("error", $kpn['message']);
      } else {

        $diskon = $kpn['diskon'];
        $pricing_upgrade = $kpn['price'] - (int)$price;

        if($kpn['coupon']!=null)
        {
          $kuponid = $kpn['coupon']->id;
        }

        if(isset($kpn['upgrade']))
        {
          $upgrade_package = $kpn['total'];
          $pricing_upgrade = 0;
        }
        /**/
      }
    }

    $user = Auth::user();
    $membership = $this->check_upgrade($request);
    $status_upgrade = $membership['membership'];
    /*if($request->status_upgrade == null)
    {
       $status_upgrade = 0;
    }
    else
    {
       $status_upgrade = $request->status_upgrade;
    }*/

    // KUPON RESELLER
    $reseller = false;
    if(session('reseller_coupon_price') <> null)
    {
      $total = session('reseller_coupon_price')['price'];
      $diskon = $price - (int)$total;
      $kuponid = session('reseller_coupon_price')['kuponid'];
      $reseller = true;
    }

    // in case if user have reseller_id
    $disc_reseller = 10; //change this variable if discount change
    if($user->reseller_id > 0)
    {
      $total = $price;
      $diskon = ($total * $disc_reseller)/100;
    }
   
		$data = [
			"user"=> $user,
			"namapaket"=> $request->namapaket,
			"kuponid"=> $kuponid,
			"price"=> $price,
			// "priceupgrade"=> $pricing_upgrade,
      "priceupgrade"=> 0,
			"diskon"=> $diskon,
			"namapakettitle"=> $request->namapakettitle,
      "phone"=>$user->phone_number,
			"month"=> $month,
      "upgrade"=>$upgrade_package,
      "status_upgrade"=>$status_upgrade,
      "chat"=>0,
      "reseller"=>$reseller
		];

    // dd($data);

		$order = Order::create_order($data);
    return view('order.thankyou')->with(array(
              'order'=>$order,    
            ));
  }

  public function thankyou(Request $request){
    return view('order.thankyou')->with(array(
              'order'=>null,    
            ));
  }

  public function index_order(Request $request){
    //halaman order user
     $orders = Order::where([['user_id',Auth::user()->id],['package','NOT LIKE',"%WA Reseller%"]])
                ->orderBy('created_at','desc')
                ->paginate(15);

     if($request->ajax())
     {
        return view('order.content',['orders'=>$orders,'pager'=>$orders]);
     }

     return view('order.index',['orders'=>$orders,'pager'=>$orders]);
    /*$arr['view'] = (string) view('order.content')
                      ->with('orders',$orders);
    $arr['pager'] = (string) view('order.pagination')
                      ->with('orders',$orders); */
  }
  
  //upload bukti TT 
  public function confirm_payment_order(Request $request){
    $user = Auth::user();
    //konfirmasi pembayaran user
    $order = Order::find($request->id_confirm);
    $folder = $user->email.'/buktibayar';

    if($order->status==0)
    {
      $order->status = 1;

      if($request->hasFile('buktibayar'))
      {
        // $path = Storage::putFile('bukti',$request->file('buktibayar'));
        $dir = 'bukti_bayar/'.explode(' ',trim($user->name))[0].'-'.$user->id;
        $filename = $order->no_order.'.jpg';
        Storage::disk('s3')->put($dir."/".$filename, file_get_contents($request->file('buktibayar')), 'public');
        $order->buktibayar = $dir."/".$filename;
        
      } else {
        // $arr['status'] = 'error';
        // $arr['message'] = 'Upload file buktibayar terlebih dahulu';
        // return $arr;
        $pathUrl = str_replace(url('/'), '', url()->previous());
        return redirect($pathUrl)->with("error", "Upload file buktibayar terlebih dahulu");
      }  
      $order->keterangan = $request->keterangan;
      $order->save();

      // $arr['status'] = 'success';
      // $arr['message'] = 'Konfirmasi pembayaran berhasil';
    } else {
      // $arr['status'] = 'error';
      // $arr['message'] = 'Order telah atau sedang dikonfirmasi oleh admin';
        $pathUrl = str_replace(url('/'), '', url()->previous());
        return redirect($pathUrl)->with("error", "Order telah atau sedang dikonfirmasi oleh admin.");
    }

    // return $arr;
    return view('order.thankyou-confirm-payment');
  }

  public function load_order(Request $request){
    //halaman order user
    $orders = Order::where('user_id',Auth::user()->id)
                ->orderBy('created_at','desc')
                ->paginate(15);
    $arr['view'] = (string) view('order.content')
                      ->with('orders',$orders);
    $arr['pager'] = (string) view('order.pagination')
                      ->with('orders',$orders); 
    return $arr;
  }

/* end class */
}
