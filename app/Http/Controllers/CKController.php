<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CKController extends Controller
{
    public function index()
	{
		return view('ck');
	}
	
	/* CKEDITOR */
	public function ck_browse(){
		$username = Auth::user()->name;
		$id = Auth::id();
		$folder = $username.'-'.$id;

		$path = public_path().'/ckeditor/'.$folder;
		$dir['image'] = scandir($path,1);
		
		$x = 0;
		foreach($dir  as $row=>$val)
		{
			foreach($val as $rows){
				if($val[$x] == '..' || $val[$x] == '.' ){
					unset($val[$x]);
				} 
				$x++;
			}
		}
		return view('ck-browser',['data'=>$val,'folder'=>$folder]);
	}
	
	public function ck_delete_image(Request $request){
		$username = Auth::user()->name;
		$id = Auth::id();
		$folder = $username.'-'.$id;
		$image_name = $request->filename;

		$path = public_path().'/ckeditor/'.$folder.'/'.$image_name;
	
		if(file_exists($path)){
			unlink($path);
			$data['msg'] = 'Your file : '.$image_name.' has been deleted';
		} else {
			$data['msg'] = 'File is not available';
		}
		return json_encode($data);
	}

	 /* Upload image from list */
    public function ck_upload_image(Request $request){
    	$username = Auth::user()->name;
		  $id = Auth::id();
		  $folder = $username.'-'.$id;

      $file = $request->file('upload');
     	
      $message = [
        'dimensions'=>'Maximum image dimension width : 1165px and height : 295px'
      ];

      $rules = [
          'upload' => 'required|image|mimes:jpeg,png,jpg,gif|max:1024|dimensions:max_width=1165,max_height=295',
      ];

      $validator = Validator::make($request->all(),$rules,$message);

      if($validator->fails()){
      	$error =  $validator->errors();
      	return response()->json(['message'=>$error->first('upload'),'uploaded' =>true ]);
      }

      if($request->hasfile('upload'))
       {
          $file = $request->file('upload');
          $name=time().$file->getClientOriginalName();
          $file->move(public_path().'/ckeditor/'.$folder.'/', $name);
          $url = url('public/ckeditor/'.$folder.'/'.$name.'');
       }
       return response()->json(['fileName' => $name, 'uploaded' => true, 'url'=> $url]);
    }
	
}
