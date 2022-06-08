<?php
use App\UserList;
use App\Customer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Lang;
use Carbon\Carbon;

  // GET MEMBERSHIP NUMBER
	function getMembership($membership)
	{
		  $membership_value = substr($membership,-1,1);
      return (int)$membership_value;
	}

  // CHECK IMAGE
  function checkImageSize($image)
  {
      $image_file_size = (int)number_format($image->getSize() / 1024, 2);
      if($image_file_size > 500)
      {
         return true;
      }
      else
      {
         return false;
      }
  }

  // SCALE IMAGE
  function scaleImageRatio($width,$height)
  {
    if($width > 1280)
    {
      $scale = $width/1280;
      $newHeight = (int)$height/$scale;
      $data = array(
          'width'=>1280,
          'height'=>(int)$newHeight,
      );
    }
    else
    {
      $scale = $height/1280;
      $newWidth = (int)$width/$scale;
      $data = array(
          'width'=>(int)$newWidth,
          'height'=>1280,
      );
    }
      
    return $data;
  }

  // RESIZE AND REDUCE IMAGE SIZE AND DIMENTION
  function resize_image($file, $w, $h, $crop=false,$folder_name,$file_name) {
      list($width, $height) = getimagesize($file);
      $r = $width / $height;

      if ($crop) {
          if ($width > $height) {
              $width = ceil($width-($width*abs($r-$w/$h)));
          } else {
              $height = ceil($height-($height*abs($r-$w/$h)));
          }
          $newwidth = $w;
          $newheight = $h;
      } else {
          if ($w/$h > $r) {
              $newwidth = $h*$r;
              $newheight = $h;
          } else {
              $newheight = $w/$r;
              $newwidth = $w;
          }
      }

      $check_image_ext = exif_imagetype($file);
      #Check whether the file is valid jpg or not
      $tempExtension = $file->getClientOriginalExtension();
      switch(image_type_to_mime_type($check_image_ext)){
        case 'image/png':
          $ext = 'png';
        break;
        case 'image/gif':
          $ext = 'gif';
        break;
        case 'image/jpeg':
          $ext = 'jpg';
        break;
      }

      $newfile = $file;
      switch($ext){
          case "png":
              $src = imagecreatefrompng($newfile);
          break;
          case "jpeg":
          case "jpg":
              $src = imagecreatefromjpeg($newfile);
          break;
          case "gif":
              $src = imagecreatefromgif($newfile);
          break;
          default:
              $src = imagecreatefromjpeg($newfile);
          break;
      }
      
      $path = $folder_name.$file_name;

      if($ext == "png")
      {
         $dst = imagecreate($newwidth, $newheight);
         imagealphablending( $dst, false );
         imagesavealpha( $dst, true );
         imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

         ob_start();
         imagepng($dst);
         $image_contents = ob_get_clean();

         Storage::disk('s3')->put($path,$image_contents,'public');
          // Storage::disk('local')->put('test/'.$path,$image_contents);
      }
      else if($ext == "gif")
      {
         $dst = imagecreate($newwidth, $newheight);
         imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
         ob_start();
         imagegif($dst);
         $image_contents = ob_get_clean();

         Storage::disk('s3')->put($path,$image_contents,'public');         
         // Storage::disk('local')->put('test/'.$path,$image_contents);
      }
      else
      {
         $dst = imagecreatetruecolor($newwidth, $newheight);
         imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
         ob_start();
         imagejpeg($dst);
         $image_contents = ob_get_clean();

         Storage::disk('s3')->put($path,$image_contents,'public');
         // Storage::disk('local')->put('test/'.$path,$image_contents);
      }
      
   }

   // TO DISPLAY LIST WITH CONTACT ON PAGE CREATE CAMPAIGN AND APPOINTMENT
   function displayListWithContact($userid)
   {
      $data = array();
      $lists = UserList::where([['user_id',$userid],['status','>',0]])->select('label','id')->get();

      if($lists->count() > 0)
      {
        foreach($lists as $row)
        {
          $customer = Customer::where([['list_id',$row->id],['status','=',1]])->get();
          $data[] = array(
            'id'=>$row->id,
            'label'=>$row->label,
            'customer_count'=>'('.$customer->count().')',
          );
        }
      }

      return $data;
   }

   //  MESSAGE DELIVERY STATUS
   function message_status($status)
    {
      if($status == 0)
      {
        return '<span class="text-brown">Pending</span>';
      } 
      elseif($status == 1)
      {
        return '<span class="text-primary">Sukses</span>';
      }
      elseif($status == 2)
      {
        return '<span class="act-tel-apt-create">Paket anda tidak mendukung</span>';
      }
      elseif($status == 3)
      {
        return '<span class="act-tel-apt-create">Error</span>';
      }
      elseif($status == 4)
      {
        return '<span class="act-tel-apt-create">Cancelled</span>';
      }
      else
      {
         return '<span class="act-tel-apt-create">Queued</span>';
      }
    }

    function getPackage($id_package = null,$check = null) 
    {
      $duration_tri = 3;
      $duration_year = 12;
      $percent_month = 25;
      $percent_year = 65;
      $basic_contacts = 1000;
      $premium_contacts = 10000;

      $package = array(
        1 => ['package'=>'basic_tri','label'=>'basic','price'=>95000,'duration'=>$duration_tri,'percent'=>$percent_month,'contact'=>$basic_contacts],
        2 => ['package'=>'premium_tri','label'=>'premium','price'=>195000,'duration'=>$duration_tri,'percent'=>$percent_month,'contact'=>$premium_contacts],
        3 => ['package'=>'unlimited_tri','label'=>'unlimited','price'=>295000,'duration'=>$duration_tri,'percent'=>$percent_month,'contact'=>null],
        '-----------',
        4 => ['package'=>'basic_yearly','label'=>'basic','price'=>295000,'duration'=>$duration_year,'percent'=>$percent_year,'contact'=>$basic_contacts],
        5 => ['package'=>'premium_yearly','label'=>'premium','price'=>395000,'duration'=>$duration_year,'percent'=>$percent_year,'contact'=>$premium_contacts],
        6 => ['package'=>'unlimited_yearly','label'=>'unlimited','price'=>495000,'duration'=>$duration_year,'percent'=>$percent_year,'contact'=>null],
      );

      if($id_package == '0')
      {
          return 'All';
      }
      elseif($id_package <> null && $check == 1)
      {
          return $package[$id_package];
      }
      elseif($id_package <> null || is_numeric($id_package))
      {
          return $package[$id_package]['package'];
      }
      else
      {
          return $package;
      }
    }

  function getPackagePrice($package,$label = null)
  {
    foreach(getPackage(null) as $row=>$col)
    {
        if($col['package'] == $package)
        {
            if($label == null)
            { 
              return $col['price'];
            }
            elseif($label == "duration")
            {
              return $col['duration'];
            }
            elseif($label == "customer")
            {
              return $col['contact'];
            }
            else
            {
              return $col['label'];
            }
        }
    }
  }

  // FORMAT PRICING
  function pricingFormat($price)
  {
     return str_replace(",",".",number_format($price));
  }

  function discount($price,$percent)
  {
    $discount = $price + ($price * $percent)/100;
    return $discount;
  }

  //TO DETERMINE EITHER UPGRADE OR DOWNGRADE
  function checkMembershipDowngrade(array $data)
  {
      // if downgrade return true
      $current_package_name = substr($data['current_package'],0,-1);
      $order_package_name = substr($data['order_package'],0,-1);

      $filter_current_package_number = substr($data['current_package'],-1);
      $filter_order_package_number = substr($data['order_package'],-1);
      
      if($filter_current_package_number == $filter_order_package_number)
      {
            
         if($current_package_name == 'supervalue' && $order_package_name == 'bestseller')
         {
           return true;
         }
         elseif($current_package_name == 'supervalue' && $order_package_name == 'basic')
         {
           return true;
         }
         elseif($current_package_name == 'bestseller' && $order_package_name == 'basic')
         {
           return true;
         }
         elseif($current_package_name == 'basic' && $order_package_name == 'basic')
         {
           return true;
         }
         elseif($current_package_name == 'bestseller' && $order_package_name == 'bestseller')
         {
           return true;
         }
         elseif($current_package_name == 'supervalue' && $order_package_name == 'supervalue')
         {
           return true;
         }
         else
         {
           return false;
         }
         
      }
      elseif($filter_current_package_number > $filter_order_package_number)
      {
         return true;
      }
      else
      {
         return false;
      }
  }

  // GET DAY LEFT WHEN ADMIN CONFIRM ORDER
  function getAdditionalDay($package)
  {
      $duration = getPackagePrice($package,"duration");
      $get_range = Carbon::now()->addMonth($duration);
      $additional_day = Carbon::now()->diffInDays($get_range);
      return $additional_day;
  }

   function getCountMonthMessage($package)
  {
      $get_package = substr($package,0,-1);
      $get_message = getCounter($package);
      $data = array();

      if($get_package == 'basic')
      {
        $data = array(
          'month'=>1,
          'total_message'=>$get_message['max_counter'] * 1
        );
      }
      
      if($get_package == 'bestseller')
      {
        $data = array(
          'month'=>2,
          'total_message'=>$get_message['max_counter'] * 2
        );
      }
      
      if($get_package == 'supervalue')
      {
        $data = array(
          'month'=>3,
          'total_message'=>$get_message['max_counter'] * 3
        );
      }

      return $data;
  }

  function getCounter($package)
  {
    $type_package = substr($package,-1,1);
    if ($type_package=="1") {
      $data = [
        'max_counter_day'=>500,
        'max_counter'=>10000
      ];
    }
    if ($type_package=="2") {
      $data = [
        'max_counter_day'=>1000,
        'max_counter'=>17500
      ];
    }
    if ($type_package=="3") {
      $data = [
        'max_counter_day'=>1500,
        'max_counter'=>27500
      ];
    }
    if ($type_package=="4") {
      $data = [
        'max_counter_day'=>1500,
        'max_counter'=>40000
      ];
    }
    if ($type_package=="5") {
      $data = [
        'max_counter_day'=>2000,
        'max_counter'=>55000
      ];
    }
    if ($type_package=="6") {
      $data = [
        'max_counter_day'=>2500,
        'max_counter'=>72500
      ];
    }
    if ($type_package=="7") {
      $data = [
        'max_counter_day'=>3000,
        'max_counter'=>92500
      ];
    }
    if ($type_package=="8") {
      $data = [
        'max_counter_day'=>4000,
        'max_counter'=>117500
      ];
    }
    if($type_package=="9") {
      $data = [
        'max_counter_day'=>5000,
        'max_counter'=>147500
      ];
    }

    return $data;
  }

  function wa_media_diference($media)
  {
    $filter = explode("/",$media);
    return $filter;
  }

?>