<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Templates;

class TemplatesController extends Controller
{

	/* Display template form */
	public function templateForm(){
		$templates = Templates::where('user_id',Auth::id())->get();
		return view('template.templates',['data'=>$templates]);
	}

    /* Insert Broadcast template */
    public function createTemplate(Request $request){
    	$name = $request->template_name;
    	$message = $request->message;
    	$template = new Templates;
    	$template->user_id = Auth::id();
    	$template->name = $name;
    	$template->message = $message;
    	$template->save();

    	if($template->save() == true){
    		$data['success'] = true;
    		$data['message'] = 'Template '.$name.' created succesfully';
    	} else {
    		$data['message'] = 'Error!! Unable to create broadcast template';
    	}

    	return response()->json($data);
    }

    /* Display broadcast template list */
    public function displayTemplateList(){
    	$template = Templates::where('user_id',Auth::id())->get();
    	if($template->count() > 0){
    			echo '<option>Choose</option>';
    		foreach($template as $row){
	    		echo '<option value='.$row->id.'>'.$row->name.'</option>';
	    	}
    	} else {
    		echo '<option>No Data</option>';
    	}
    }

    /* Get message from broadcast template */
    public function displayTemplate(Request $request){
    	$id = $request->id;
    	$editor = $request->editor;
    	$template = Templates::where('id',$id)->first();

    	if(is_null($template)){
    		echo 'No Data';
    	}

    	$data = array(
    		'name'=>$template->name,
    		'message'=>$template->message,
    	);

    	if($editor == true){
    		return response()->json($data);
    	} else {
    		echo $template->message;
    	}
    }

    /* Update template */
    public function updateTemplate(Request $request){
    	$template = Templates::where('id',$request->id)->update(
    		[
    			'name'=>$request->edit_template_name,
    			'message'=>$request->edit_message,
    		]
    	);

    	if($template == true){
    		$data['success'] = true;
    		$data['status'] = 'Template updated successfully';
    	} else {
    		$data['status'] = 'Error!! Failed to update template';
    	}

    	return response()->json($data);
    }

    /* Delete template */
    public function delTemplate(Request $request){
    	$template = Templates::where('id',$request->id)->delete();

    	if($template == true){
    		$data['status'] = 'Template updated successfully';
    	} else {
    		$data['status'] = 'Error!! Failed to update template';
    	}

    	return response()->json($data);
	}
}
