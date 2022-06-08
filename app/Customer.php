<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Customer;
use App\UserList;
use App\User;

class Customer extends Model
{

	protected $table = 'customers';
	protected $fillable = ['user_id','list_id','name','last_name','email','telegram_number','code_country','birthday','gender','country','province','city','zip','marriage','hobby','occupation','religion','additional','status'];
    /*
		status : 
		0 = deleted
		1 = active
    */

    /*
      unique : 
      - telegram_number
      - email

      note : if user fill with available data either on these columns, 
            then customer status would be updated.
    */

  // VALIDATION MAX CONTACTS
  public static function max_contacts($user_id)
  {
    $user = User::find($user_id);
    $user_membership = $user->membership;
    $package = getPackagePrice($user_membership,1);
    $max_customer = getPackagePrice($user_membership,"customer");
    $total_customer = self::where('user_id',$user_id)->count();

    $data['success'] = false;
    $data['status'] = 'err_contacts';

    if($package == 'basic' && $total_customer >= $max_customer)
    {
      $data['message'] = 'Jumlah kontak sudah mencapai 1.000';
      return $data;
    }

    if($package == 'premium' && $total_customer >= $max_customer)
    {
      $data['message'] = 'Jumlah kontak sudah mencapai 10.000';
      return $data;
    }

    $data['success'] = true;
    return $data;
  }

  // NOT USING ANYMORE
  public static function create_link_unsubs($id,$list_id){
    $customer = Customer::find($id);
    $list = UserList::find($id);
    if (!is_null($customer) && !is_null($list)){
      if ($customer->link_unsubs =="")
      {
        $data = array(
          // "to" => "https://activrespon.com",
          "to" => env("APP_URL")."link/unsubscribe/".$list->name."/".$customer->id,
        );

        $payload = json_encode($data);

        // Prepare new cURL resource
        $ch = curl_init('https://spon.li/create');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        // Set HTTP Header for POST request 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload))
        );

        // Submit the POST request
        $result = curl_exec($ch);

        // Close cURL session handle
        curl_close($ch);
        $obj = json_decode($result);

        $customer->link_unsubs = $obj->url;
        $customer->save();
      }
      
      return $customer->link_unsubs;
    }
    
    return false;
  }

/* end class */
}
