<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Utility;
use Illuminate\Database\QueryException;

class UtilityController extends Controller
{
    public function delete_category(Request $request)
    {
      $id = $request->id;
      return $this->delete_reccursive($id);
    }

    public function delete_reccursive($id)
    {
      $check_child = Utility::where('id_category',$id)->get();
      $data = array();

      if($check_child->count() > 0)
      {
        foreach($check_child as $row):
          $data[] = $row->id;
        endforeach;
      }

      try
      {
         Utility::find($id)->delete();
      }
      catch(QueryException $e)
      {
        $ret['status'] = $e->getMessage();
        return response()->json($ret);
      }

      // RETURN JSON IF NO CHILDS FROM PARENT
      if(count($data) > 0)
      {
        return self::extract_array($data);
      }
      else
      {
        $ret['status'] = 1;
        return response()->json($ret);
      }

    }

    private static function extract_array(array $data)
    {
      $arr = array();
      $util = Utility::whereIn('id_category',$data)->get();

      //PUT CHILDS ID FROM DATA ARRAY
      if($util->count() > 0)
      {
        foreach($util as $row)
        {
          $arr[] = $row->id;
        }
      }
      
      // DELETE PREVIOUS ID
      foreach($data as $col)
      {
        Utility::find($col)->delete();
      }
     
      if(count($arr) > 0)
      {
        return self::extract_array($arr);
      }
      else
      {
        $ret['status'] = 1;
        return response()->json($ret);
      } 
     
    }

    public function edit_category(Request $request)
    {
      $id = $request->id;
      $category = $request->category;
      $utils = Utility::find($id);

      if(is_null($utils))
      {
        $data['status'] = 2;
        return response()->json($data);
      }

      try{
        $utils->category = $category;
        $utils->save();
        $data['status'] = 1;
      }
      catch(QueryException $e)
      {
        // $e->getMessage();
        $data['status'] = 0;
      }

      return response()->json($data);
    }

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

      if($request->ajax())
      {
         return view('admin.list-utility.content',['data'=>$utils,'id'=>$id,'callback'=> new UtilityController]);
      }
      else
      {
         return view('admin.list-utility.content-child',['data'=>$utils,'id'=>$id,'callback'=> new UtilityController]);
      }
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
       if($request->category == null || empty($request->category))
       {
          $data['status'] = 2;
           return response()->json($data);
       }

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
