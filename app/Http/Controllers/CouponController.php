<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Coupon;
use App\Catalogs;
use Validator;
use Carbon;
use Auth;

class CouponController extends Controller
{
    protected function validator(array $data){
      return Validator::make($data, [
        'kodekupon' => ['required','string','unique:mysql2.coupons'],
        'jenis_kupon' => ['required','integer'],
        'diskon_value' => ['required','integer','min:0'],
        'diskon_percent' => ['required','integer','min:0','max:100'],
        'valid_until' => ['required','date','after:today'],
      ]);
    }

    public function index(){
      //halaman list kupon admin
      $pricelist = getPackage(null);
      return view('admin.list-coupon.index',['price'=>$pricelist]);
    }

    public function load_coupon(Request $request){
      //halaman list kupon admin
      $coupons = Coupon::where("user_id",0)->get();

      $arr['view'] = (string) view('admin.list-coupon.content')
              ->with('coupons',$coupons);  
      return $arr;
    }

    public function add_coupon(Request $request){
      //tambah kupon
      $validator = $this->validator($request->all());

      if(!$validator->fails()){
        $coupon = new Coupon;
        $coupon->kodekupon = $request->kodekupon;
        $coupon->diskon_value = $request->diskon_value;
        $coupon->diskon_percent = $request->diskon_percent;
        $coupon->coupon_type = $request->jenis_kupon;
        $coupon->valid_until = $request->valid_until;
        $coupon->valid_to = $request->valid_to;
        $coupon->keterangan = $request->keterangan;
        $coupon->package_id = $request->package_id;
        $coupon->save();

        $arr['status'] = 'success';
        $arr['message'] = 'Kupon berhasil ditambahkan';
      } else {
        $arr['status'] = 'error';
        $arr['message'] = $validator->errors()->first();
      }

      return $arr;
    }

    public function edit_coupon(Request $request){
      //edit kupon
      $validator = $this->validator($request->all());

      if($validator->fails()){
        $failedRules = $validator->failed();

        if(isset($failedRules['kodekupon']['Unique'])){
        } else {
          $arr['status'] = 'error';
          $arr['message'] = $validator->errors()->first();
          return $arr;
        }
      }

      $coupon = Coupon::find($request->id_edit);
      $coupon->kodekupon = $request->kodekupon;
      $coupon->diskon_value = $request->diskon_value;
      $coupon->diskon_percent = $request->diskon_percent;
      $coupon->valid_until = $request->valid_until;
      $coupon->valid_to = $request->valid_to;
      $coupon->keterangan = $request->keterangan;
      $coupon->package_id = $request->package_id;
      $coupon->save();

      $arr['status'] = 'success';
      $arr['message'] = 'Kupon berhasil diedit';
      return $arr;
    }

    public function delete_coupon(Request $request){
      //hapus kupon
      $coupon = Coupon::find($request->id)
                  ->delete();

      $arr['status'] = 'success';
      $arr['message'] = 'Delete kupon berhasil';

      return $arr;
    }

    public function coupon_available(Request $request){
      return view('user.coupon.index');
    }

    #DASHBOARD
    public function kupon() {
  		$banner = Catalogs::where('type','=','main')->first();
      if(!is_null($banner))
      {
        $banner = $banner->path;
      }
      else
      {
        $banner = null;
      }
  		 
  		 return view('user.coupon.kupon',['banner'=>$banner]);
    }
	
	public function kupon_content(Request $request)
    {
    $data = array();
    $coupon = array();
    $userid = Auth::id();
	  $value = $request->value;
    $sort = (int)$request->sort;
    $now = Carbon::now();
	  
    $pos = 'ASC';
    if($value == null) {
       $sel = array(
          ['coupons.valid_until','>=',$now],
          ['type','=','coupon-global']
       );

       #AUTO GENERATE LOGIC
       $auto = array(
          ['user_id','=',$userid],
          ['valid_until','>=',$now]
       );

       #OTHER
       $other = array(
          ['type','=','other'],
          ['valid_until','>=',$now]
       );

    }else if(empty($sort)) {
       $sel = array(
          ['coupons.valid_until','>=',$now],
          ['type','=','coupon-global'],
          ['coupons.kodekupon','LIKE','%'.$value.'%']
       );

      #AUTO GENERATE LOGIC
       $auto = array(
          ['user_id','=',$userid],
          ['valid_until','>=',$now],
          ['kodekupon','LIKE','%'.$value.'%']
       );

       #OTHER
       $other = array(
          ['type','=','other'],
          ['valid_until','>=',$now],
          ['kodekupon','LIKE','%'.$value.'%']
       );
    } else if($sort ==2) {
      $sel = array(
          ['coupons.valid_until','>=',$now],
          ['type','=','coupon-global'],
          ['coupons.kodekupon','LIKE','%'.$value.'%']
      );

      #AUTO GENERATE LOGIC
       $auto = array(
          ['user_id','=',$userid],
          ['valid_until','>=',$now],
          ['kodekupon','LIKE','%'.$value.'%']
       );

       #OTHER
       $other = array(
          ['type','=','other'],
          ['valid_until','>=',$now],
          ['kodekupon','LIKE','%'.$value.'%']
       );
      $pos = 'DESC'; 
    }
	  
    # COUPON GLOBAL
	   $catalogs = Catalogs::where($sel)
              ->join('coupons','coupons.id','=','catalogs.coupon_id')
              ->select('catalogs.*','coupons.valid_until','coupons.kodekupon','coupons.user_id')
              ->orderBy('coupons.valid_until',$pos)
              ->get();

     if($catalogs->count() > 0)
     {
       foreach($catalogs as $rows)
       {
          $coupon[] = array(
            'path'=>$rows->path,
            'desc'=>$rows->desc,
            'valid_until'=>$rows->valid_until,
            'kodekupon'=>$rows->kodekupon,
            'coupon_url'=>$rows->coupon_url,
            'type'=>'coupon-global',
          ); 
       }
     }

     #AUTO GENERATE
     $catalog_ag = Catalogs::where('type','=','auto-generate')->first();
     $gcoupons = Coupon::where($auto)
                ->orderBy('valid_until',$pos)
                ->get();

     if($gcoupons->count() > 0 && !is_null($catalog_ag))
     {
       foreach($gcoupons as $rows)
       {
          $coupon[] = array(
            'path'=>$catalog_ag->path,
            'desc'=>$catalog_ag->desc,
            'coupon_url'=>$catalog_ag->coupon_url,
            'valid_until'=>$rows->valid_until,
            'kodekupon'=>$rows->kodekupon,
            'type'=>'auto-generate'
          ); 
       }
     }

     #OTHER
     $catalog_ot = Catalogs::where($other)
                ->orderBy('valid_until',$pos)
                ->get();

     if($catalog_ot->count() > 0)
     {
        foreach($catalog_ot as $rows)
        {
            $coupon[] = array(
            'path'=>$rows->path,
            'desc'=>$rows->desc,
            'valid_until'=>$rows->valid_until,
            'kodekupon'=>$rows->kodekupon,
            'coupon_url'=>$rows->coupon_url,
            'type'=>'other'
          ); 
        }
     }

    if(count($coupon) > 0)
    {
      foreach($coupon as $rows)
      {
        $valid_until = Carbon::parse($rows['valid_until']);
        $totalDuration = $now->diffInSeconds($valid_until,false);

        if($totalDuration <= 0) {
          $zerotime = 0;
          $end_period = gmdate('H:i:s',$zerotime);
        }
        else {
          $convert_time = gmdate('H:i:s', $totalDuration);
          $hour = round($totalDuration/3600);
          $breaktime = explode(':',$convert_time);
          $breaktime[0] = $hour;
          $end_period = implode(":",$breaktime);
        }

        $data[] = array(
          'path'=>$rows['path'],
          'desc'=>$rows['desc'],
          'valid_until'=>$end_period,
          'kodekupon'=>$rows['kodekupon'],
          'coupon_url'=>$rows['coupon_url'],
          'type'=>$rows['type']
        );
      }
    }

    return view('user.coupon.kupon-content',['catalogs'=>$data]);
      
}

/**/    
}
