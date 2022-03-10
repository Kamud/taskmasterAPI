<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Estimate;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EstimateController extends Controller
{
    public function index()
    {
        $estimates = Estimate::latest()->get();
        foreach ($estimates as $estimate)
        {
            $estimate->modified_by;
            $estimate->assignment;
            unset($estimate->assignment_id,$estimate->modified_by_user_id);
        }

        return [
            'status'=> 'success',
            'count'=>count($estimates),
            "estimates"=>$estimates,
        ];
    }

    public function show($id)
    {
        $estimate = Estimate::find($id);

        if($estimate)
        {
            $estimate->assigned_user;
            $estimate->modified_by;
            unset($estimate->assigned_user_id,$estimate->modified_by_user_id);
            return [
                'status'=> 'success',
                'estimate' => $estimate
            ];
        }
        else
        {
            return response([
                'status'=> 'fail',
                'message'=> "The requested estimate with id:'$id' was not found"],
                404);
        }


    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'assignment_id' => 'required',
            'quote_price' => 'required|numeric',
            'quote_ref' => 'required',
            'submission_date' => 'required'
        ]);


        //CREATE A UNIQUE ID and SLUG, CLOSING DATE
        $newEstimate = $request->all();
        $newEstimate["id"] = "estimate_".Str::random(10);
        $newEstimate['slug'] = Str::slug($newEstimate['quote_ref']);
        $newEstimate['modified_by_user_id'] = Auth::id();

        $time = strtotime("+ 15 days",strtotime($newEstimate['submission_date']));
        $newEstimate['closing_date'] = date('Y-m-d H:i:s',$time);

        $estimate = Estimate::create($newEstimate);
        if($estimate){
            //CREATE A LOG
            $assignment = Assignment::find($newEstimate['assignment_id']);
            $estimate['description'] = $estimate['quote_ref'].";".$assignment['description'];
            $this->createLog($estimate,'created');

            return response([
                'status'=>'success',
                'message'=> "New Estimate with id: '$estimate->id' has been created",
                'estimate'=>$estimate,
            ],201);
        }
        else{
            return response([
                'status'=>'fail',
                'message'=> "Failed to create estimate",
                'error'=>$estimate,
            ],400);
        }
    }

    public function update(Request $request,$id)
    {
        $this->validate($request,[
            'quote_price' => 'numeric',
            'submission_date' => 'date'
        ]);

        $estimate = Estimate::find($id);
        if(!$estimate){
            return response([
                'status'=> 'fail',
                'message'=> "The requested estimate with id:'$id' was not found"],
                404);
        }

        $newEstimate = $request->all();
        $newEstimate['modified_by_user_id'] = Auth::id();

        if(array_key_exists('description',$newEstimate)){
            $newEstimate['slug'] = Str::slug($newEstimate['quote_ref']);
        }

        //PREVENT ID FROM UPDATING
        unset($newEstimate['id']);
        $estimate->update($newEstimate);

        //CREATE A LOG
        $assignment = Assignment::find($estimate['assignment_id']);
        $estimate['description'] = $estimate['quote_ref'].";".$assignment['description'];
        $this->createLog($estimate,'modified');
        return response([
            'status'=>'success',
            'message'=> "Estimate with id: '$estimate->id' has been updated",
            'estimate'=>$estimate,
        ],201);
    }

    public function destroy($id)
    {
        $estimate = Estimate::find($id);
        $estimate && $assignment = Assignment::find($estimate['assignment_id']);

        if($estimate && Estimate::destroy($id)){
            //CREATE A LOG
            $estimate['description'] = $estimate['quote_ref'].";".$assignment['description'];
            $this->createLog($estimate,'deleted');

            return [
                'status'=>'success',
                'message'=> "Estimate with id: '$id' has been deleted",

            ];
        }
        else{
            return response( [
                'status'=>'fail',
                'message'=> "The requested estimate with id:'$id' was not found"
            ],404);
        }
    }

    public function search($name)
    {
        $estimates = Estimate::where('description', 'like', '%'.$name.'%')->get();
        foreach ($estimates as $estimate)
        {
            $estimate->assigned_user;
            $estimate->modified_by;
            unset($estimate->assigned_user_id,$estimate->modified_by_user_id);
        }

        return [
            'status'=> 'success',
            'count'=>count($estimates),
            "estimates"=>$estimates,
        ];
    }


}
