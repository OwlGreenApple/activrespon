<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Utility;
use Illuminate\Database\QueryException;

class UtilityController extends Controller
{
    public function add_category(Request $request)
    {
       $util = new Utility;
       $util->id_category = $request->id_category;
       $util->category = $request->category ;

       try{
        $util->save();
        $data['status'] = 1;
       }
       catch(QueryException $e)
       {
        $data['status'] = 0;
       }
       return response()->json($data);
    }

    public function display_category(Request $request)
    {
      $utils = Utility::all();
      $data = [];

      if($utils->count() > 0)
      {
        foreach($utils as $row)
        {
          $data[$row->id] = $row->category;
        }
      }

      return response()->json($data);
    }

    public function index()
    {
        return view('admin.list-utility.index');
    }

/*end class*/
}
