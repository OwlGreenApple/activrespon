<?php
use App\UserList;
use App\Customer;
use Illuminate\Support\Facades\Storage;

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
        return '<span class="text-primary">Success</span>';
      }
      elseif($status == 2)
      {
        return '<span class="act-tel-apt-create">Phone Offline</span>';
      }
      elseif($status == 3)
      {
        return '<span class="act-tel-apt-create">Phone Not Available</span>';
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

    function getPackage($id_package,$check = null)
    {
      $package = array(
        1 => ['package'=>'basic1','price'=>195000],
        2 => ['package'=>'bestseller1','price'=>370500],
        3 => ['package'=>'supervalue1','price'=>526500],
        '-----------',
        4 => ['package'=>'basic2','price'=>295000],
        5 => ['package'=>'bestseller2','price'=>560500],
        6 => ['package'=>'supervalue2','price'=>796500],
        '-----------',
        7 => ['package'=>'basic3','price'=>395000],
        8 => ['package'=>'bestseller3','price'=>750500],
        9 => ['package'=>'supervalue3','price'=>1066500],
        '-----------',
        10 => ['package'=>'basic4','price'=>495000],
        11 => ['package'=>'bestseller4','price'=>940500],
        12 => ['package'=>'supervalue4','price'=>1336500],
        '-----------',
        13 => ['package'=>'basic5','price'=>595000],
        14 => ['package'=>'bestseller5','price'=>1130500],
        15 => ['package'=>'supervalue5','price'=>1606500],
        '-----------',
        16 => ['package'=>'basic6','price'=>695000],
        17 => ['package'=>'bestseller6','price'=>1320500],
        18 => ['package'=>'supervalue6','price'=>1876500],
        '-----------',
        19 => ['package'=>'basic7','price'=>795000],
        20 => ['package'=>'bestseller7','price'=>1510500],
        21 => ['package'=>'supervalue7','price'=>2146500],
        '-----------',
        22 => ['package'=>'basic8','price'=>895000],
        23 => ['package'=>'bestseller8','price'=>1700500],
        24 => ['package'=>'supervalue8','price'=>2416500],
        '-----------',
        25 => ['package'=>'basic9','price'=>995000],
        26 => ['package'=>'bestseller9','price'=>1890500],
        27 => ['package'=>'supervalue9','price'=>2686500],
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

  function getPackagePrice($package)
  {
    foreach(getPackage(null) as $row=>$col)
    {
        if($col['package'] == $package)
        {
            return $col['price'];
        }
    }
  }

  //TO DETERMINE UPGRADE OR DOWNGRADE EITHER
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

  function getAdditionalDay($package)
  {
      $get_package = substr($package,0,-1);
      $additional_day = 0;

      if($get_package == 'basic')
      {
        $additional_day += 30;
      }
      
      if($get_package == 'bestseller')
      {
        $additional_day += 60;
      }
      
      if($get_package == 'supervalue')
      {
        $additional_day += 90;
      }

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

?>