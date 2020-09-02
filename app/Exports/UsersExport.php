<?php

namespace App\Exports;

//use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Excel;
use Illuminate\Contracts\Support\Responsable;
use App\Customer;
use App\Reminder;
use App\ReminderCustomers;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class UsersExport implements FromView 
{

	public function __construct(int $campaign_id)
  {
      $this->campaign_id = $campaign_id;
  }

  public function view(): View
  {
      $userid = Auth::id();
      $campaigns = ReminderCustomers::where([['reminders.campaign_id',$this->campaign_id],['reminders.is_event',2],['reminders.user_id',$userid]])
      ->join('reminders','reminders.id','=','reminder_customers.reminder_id')
      ->join('customers','customers.id','=','reminder_customers.customer_id')
      ->select('reminders.campaign_id','reminders.event_time','customers.name','customers.telegram_number','customers.id')
      ->distinct()
      ->get();

      return view('appointment.list_appt_export', [
          'campaigns' => $campaigns,
      ]);
  }

/* end UsersExport */
}
