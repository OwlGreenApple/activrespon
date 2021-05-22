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
       $util->id_category = $request->category;
       $util->category = $request->id_category;

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

    public function index()
    {
        return view('admin.list-utility.index');
    }

/*end class*/
}
