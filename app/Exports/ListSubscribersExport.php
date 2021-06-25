<?php

namespace App\Exports;

use App\Customer;
// use Maatwebsite\Excel\Concerns\FromCollection;
/*use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;*/
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
/*use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\WithMapping;*/
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Http\Controllers\ListController;
use App\Http\Controllers\CustomerController as cs;


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
          $list_subscriber = Customer::query()->where([['list_id',$this->idlist],['user_id','=',$userid]])->select('name','last_name','telegram_number','email','birthday','gender','country','province','city','zip','marriage','hobby','occupation','religion')->get();
      }
      else
      {
          $list_subscriber = Customer::query()->where([['list_id',$this->idlist],['user_id','=',$userid]])->select('name','last_name','telegram_number','email','birthday','gender','country','province','city','zip','marriage','hobby','occupation','religion','additional')->get();
      }

      return view('list.list_subscriber_export', [
          'import'=>$this->import,
          'customer' => $list_subscriber,
          'fct'=> new ListController,
          'cs'=> new cs
      ]);
  }
	
/*end class*/
}
