<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AssignmentController extends Controller
{
    public function index()
    {
        $assignments = Assignment::latest()->get();
        return [
            'status'=> 'success',
            'count'=>count($assignments),
            "assignments"=>$assignments,
        ];
    }

    public function show(Assignment $assignment)
    {
        return [
            'status'=> 'success',
            'assignment' => $assignment
        ];
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'description' => 'required|max:255',
            'category'  =>'max:50' ,
            'location'  =>'max:50' ,
            'organisation'  => 'required|max:50',
            'client_ref' => 'max:25',
            'type' => [Rule::in(['RFQ','Tender'])],
            'publish_date' => 'date|before_or_equal:today',
            'closing_date' => 'required|date|after_or_equal:today|after:publish_date',
            'source'=> [Rule::in(['Tenderlink','Tender Notice Board','NGO Tenders','Newspaper','Referrals','Other'])],
            'source_url' => 'max:120',
            'document_fees' => 'max:10',
            'bid_bond'  => 'max:10',
        ]);

        //CREATE A UNIQUE ID and SLUG
        $newAssignment = $request->all();
        $newAssignment["id"] = mt_rand(10000000,99999999);
        $newAssignment['slug'] = Str::slug($newAssignment['description']);
        $newAssignment['created_by'] = Auth::id();
        $newAssignment['updated_by'] = Auth::id();
        $assignment = Assignment::create($newAssignment);

        if($assignment){
            return response([
                'status'=>'success',
                'message'=> "New Assignment with id: '$assignment->id' has been created",
                'assignment'=>$assignment,
            ],201);
        }
        else{
            return response([
                'status'=>'fail',
                'message'=> "Failed to create assignment",
                'error'=>$assignment,
            ],400);
        }
    }

    public function update(Request $request,Assignment $assignment)
    {
        $this->validate($request,[
            'description' => 'max:255',
            'category'  =>'max:50',
            'organisation'  => 'max:50',
            'client_ref' => 'max:25',
            'type' => [Rule::in(['RFQ','Tender'])],
            'publish_date' => 'date|before_or_equal:today',
            'closing_date' => 'date|after_or_equal:today|after:publish_date',
            'source'=> [Rule::in(['Tenderlink','Tender Notice Board','NGO Tenders','Newspaper','Referrals','Other'])],
            'source_url' => 'max:120',
            'document_fees' => 'max:10',
            'bid_bond'  => 'max:10',
            'status' => [Rule::in(['new','pending','assigned','declined'])],
            'status_description',
        ]);

        $newAssignment = $request->all();
        $newAssignment['updated_by'] = Auth::id();

        if(array_key_exists('description',$newAssignment)){
            $newAssignment['slug'] = Str::slug($newAssignment['description']);
        }

        //PREVENT ID & CREATED_BY FROM UPDATING
        unset($newAssignment['id']);
        unset($newAssignment['created_by']);
        $assignment->update($newAssignment);

        return response([
            'status'=>'success',
            'message'=> "Assignment with id: '$assignment->id' has been updated",
            'assignment'=>$assignment,
        ],201);
    }

    public function destroy(Assignment $assignment)
    {
        $assignment->delete();
        return [
            'status'=>'success',
            'message'=> "Assignment with id: $assignment->id has been deleted",
        ];
    }

    public function search($name)
    {
        $assignments = Assignment::where('description', 'like', '%'.$name.'%')->get();

        return [
            'status'=> 'success',
            'count'=>count($assignments),
            "assignments"=>$assignments,
        ];
    }
}
