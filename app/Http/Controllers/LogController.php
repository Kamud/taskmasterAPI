<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LogController extends Controller
{
    public function index()
    {
        $logs = Log::latest()->get();
        foreach ($logs as $log)
        {
            $log->modified_by;
            unset($log->modified_by_user_id);
        }

        return [
            'status'=> 'success',
            'count'=>count($logs),
            "logs"=>$logs,
        ];
    }

    public function show($id)
    {
        $log = Log::find($id);

        if($log)
        {
            $log->modified_by;
            unset($log->modified_by_user_id);
            return [
                'status'=> 'success',
                'log' => $log
            ];
        }
        else
        {
            return response([
                'status'=> 'fail',
                'message'=> "The requested log with id:'$id' was not found"],
                404);
        }


    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'resource_id' => 'required',
            'resource_category' => 'required',
            'description' => 'required',
            'action' => 'required'
        ]);


        //CREATE A UNIQUE ID
        $newLog = $request->all();
        $newLog["id"] = "log_".Str::random(10);
        $newLog['modified_by_user_id'] = Auth::id();

        $log = Log::create($newLog);
        if($log){

            return response([
                'status'=>'success',
                'message'=> "New Log with id: '$log->id' has been created",
                'log'=>$log,
            ],201);
        }
        else{
            return response([
                'status'=>'fail',
                'message'=> "Failed to create log",
                'error'=>$log,
            ],400);
        }
    }

    public function update(Request $request,$id)
    {

            return response([
                'status'=> 'fail',
                'message'=> "Logs cannot be updated"],
                400);
    }

    public function destroy($id)
    {
        if(Log::destroy($id)){

            return [
                'status'=>'success',
                'message'=> "Log with id: '$id' has been deleted",

            ];
        }
        else{
            return response( [
                'status'=>'fail',
                'message'=> "The requested log with id:'$id' was not found"
            ],404);
        }
    }

}
