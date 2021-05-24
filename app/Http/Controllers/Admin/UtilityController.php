<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Utility;
use Illuminate\Database\QueryException;

class UtilityController extends Controller
{
    public function call_display_function($id)
    {
      $id_category = $id;
      $utils = Utility::where('id_category',$id_category)->get();
      return $utils;
    }

    public function display_category(Request $request,$id = null)
    {
      if($request->id !== null)
      {
        $id = $request->id;
      }

      $utils = $this->call_display_function($id);
      return view('admin.list-utility.content',['data'=>$utils,'id'=>$id,'callback'=> new UtilityController]);
    }

    public function display_category_option()
    {
      $utils = Utility::all();
      $options = [];

      if($utils->count() > 0)
      {
        foreach($utils as $row):
          $options[$row->id] = $row->category;
        endforeach;
      }

      return response()->json($options);
    }

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

    public function index()
    {
        return view('admin.list-utility.index');
    }

/*end class*/
}
