<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Utility;
use App\Rules\UniqueUtility;
use Illuminate\Database\QueryException;
use Auth, Validator;

class UtilityController extends Controller
{
    public function delete_category(Request $request)
    {
      $id = $request->id;
      $del = Utility::where([['id',$id],['user_id',Auth::id()]])->first();

      if(is_null($del))
      {
        $data['status'] = 0;
        return response()->json($data);
      }

      $idc = $del->id_category;

      try
      {
        $del->delete();
        $data['status'] = 1;
        $data['idc'] = $idc;
      }
      catch(Queryexception $e)
      {
        $data['status'] = 0;
      }
      return response()->json($data);
    }

   /* 
    DELETE RECCURSIVELY
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
     
    }*/

    public function edit_category(Request $request)
    {
      $id = $request->id;
      $category = strip_tags($request->category);
      $utils = Utility::where([['id',$id],['user_id',Auth::id()]])->first();

      if(is_null($utils))
      {
        $data['status'] = 2;
        return response()->json($data);
      }

      $idc = $utils->id_category;

      try{
        $utils->category = $category;
        $utils->save();
        $data['status'] = 1;
        $data['idc'] = $idc;
      }
      catch(QueryException $e)
      {
        // $e->getMessage();
        $data['status'] = 0;
      }

      return response()->json($data);
    }

    public function call_display_function($id,$user_id)
    {
      $id_category = $id;
      $utils = Utility::where([['user_id',$user_id],['id_category',$id_category]])->orderBy('id','desc')->get();
      return $utils;
    }

    public function display_category(Request $request,$id = null)
    {
      $user = self::admin_account();
      
      if($request->id !== null)
      {
        $id = $request->id;
      }

      $utils = $this->call_display_function($id,$user->id);
      return view('admin.list-utility.content',['data'=>$utils,'id'=>$id,'callback'=> new UtilityController]);

      /*if($request->ajax())
      {
        
      }
      else
      {
         return view('admin.list-utility.content-child',['data'=>$utils,'id'=>$id,'callback'=> new UtilityController]);
      }*/
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

    public function add_category_admin(Request $request)
    {
      $category = strip_tags($request->category);
      return $this->add_category($category,1);
    }

    public function add_category_user(Request $request)
    {
      $category = strip_tags($request->category);
      $id_category = $request->id_category;

      $rules = [
        'category'=>['required','max:50', new UniqueUtility(Auth::id(),$id_category)]
      ];

      $validator = Validator::make($request->all(),$rules);
      if($validator->fails())
      {
        $err = $validator->errors();
        $error = [
          'status'=>2,
          'idc'=>$id_category,
          'category'=>$err->first('category'),
        ];
        return response()->json($error);
      }

      // max category each hobbies and jobs
      $max = 7;
      $util = Utility::where([['user_id',Auth::id()],['id_category',$id_category]])->get();
      if($util->count() == $max)
      {
        $data['status'] = 3;
        $data['idc'] = $id_category;
        $data['max'] = $max;
        return response()->json($data);
      }

      return $this->add_category($category,$id_category);
    }

    public function add_category($category,$id_category)
    {
       if($category == null || empty($category))
       {
          $data['status'] = 2;
          return response()->json($data);
       }

       $arr_category = [1,2,3];
       if(in_array($id_category, $arr_category) == false)
       {
          $data['status'] = 2;
          return response()->json($data);
       }

       $util = new Utility;
       $util->user_id = Auth::id();
       $util->id_category = $id_category;
       $util->category = $category ;

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

    /* MISC */

    private static function admin_account()
    {
      // DETERMINE ADMIN OR USER
      $user = Auth::user();
      return $user;
    }

/*end class*/
}
