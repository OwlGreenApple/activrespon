<?php

namespace App\Exports;

use App\Customer;
// use Maatwebsite\Excel\Concerns\FromCollection;
/*use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;*/
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Excel;
/*use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\WithMapping;*/
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ListSubscribersExport implements FromView
{

  public function __construct(int $listid,int $import)
  {
      $this->idlist = $listid;
      $this->import = $import;
  }

  public function view(): View
  {
      $userid = Auth::id();
      $list_subscriber = array();

      if($this->import == 1)
      {
          $list_subscriber = Customer::query()->where([['list_id',$this->idlist],['user_id','=',$userid]])->select('name','telegram_number','email')->get();
      }
      else
      {
          $list_subscriber = Customer::query()->where([['list_id',$this->idlist],['user_id','=',$userid]])->select('name','telegram_number','email','additional')->get();
      }

      return view('list.list_subscriber_export', [
          'import'=>$this->import,
          'customer' => $list_subscriber,
      ]);
  }
	
/*end class*/
}
