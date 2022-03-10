<?php

namespace App\Http\Controllers;

use App\Models\Prospect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProspectController extends Controller
{
    public function index()
    {
        $prospects = Prospect::latest()->get();
        return [
            'status'=> 'success',
            'count'=>count($prospects),
            "prospects"=>$prospects,
        ];
    }

    public function show(Prospect $prospect)
    {
        return [
            'status'=> 'success',
            'prospect' => $prospect
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
        $newProspect = $request->all();
        $newProspect["id"] = mt_rand(10000000,99999999);
        $newProspect['slug'] = Str::slug($newProspect['description']);
        $newProspect['created_by'] = Auth::id();
        $newProspect['updated_by'] = Auth::id();
        $prospect = Prospect::create($newProspect);

        if($prospect){
            return response([
                'status'=>'success',
                'message'=> "New Prospect with id: '$prospect->id' has been created",
                'prospect'=>$prospect,
            ],201);
        }
        else{
            return response([
                'status'=>'fail',
                'message'=> "Failed to create prospect",
                'error'=>$prospect,
            ],400);
        }
    }

    public function update(Request $request,Prospect $prospect)
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

        $newProspect = $request->all();
        $newProspect['updated_by'] = Auth::id();

        if(array_key_exists('description',$newProspect)){
            $newProspect['slug'] = Str::slug($newProspect['description']);
        }

        //PREVENT ID & CREATED_BY FROM UPDATING
        unset($newProspect['id']);
        unset($newProspect['created_by']);
        $prospect->update($newProspect);

        return response([
            'status'=>'success',
            'message'=> "Prospect with id: '$prospect->id' has been updated",
            'prospect'=>$prospect,
        ],201);
    }

    public function destroy(Prospect $prospect)
    {
        $prospect->delete();
        return [
            'status'=>'success',
            'message'=> "Prospect with id: $prospect->id has been deleted",
        ];
    }

    public function search($name)
    {
        $prospects = Prospect::where('description', 'like', '%'.$name.'%')->get();

        return [
            'status'=> 'success',
            'count'=>count($prospects),
            "prospects"=>$prospects,
        ];
    }
}
