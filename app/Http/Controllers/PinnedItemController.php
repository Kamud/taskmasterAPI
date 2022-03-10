<?php

namespace App\Http\Controllers;

use App\Models\PinnedItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PinnedItemController extends Controller
{
    public function index()
    {
        $pinnedItems = PinnedItem::latest()->get();
        foreach ($pinnedItems as $pinned)
        {
            $pinned->modified_by;
            $pinned->resource_category = $pinned['resource_category'];
            $pinned->resource;
            unset($pinned->modified_by_user_id);
        }

        return [
            'status'=> 'success',
            'count'=>count($pinnedItems),
            "data"=>$pinnedItems,
        ];
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'resource_id' => 'required',
            'resource_category' => 'required',
        ]);


        //CREATE A UNIQUE ID
        $newPinned = $request->all();
        $newPinned["id"] = "pinned_".Str::random(10);
        $newPinned['modified_by_user_id'] = Auth::id();

        $pinned = PinnedItem::create($newPinned);
        if($pinned){

            return response([
                'status'=>'success',
                'message'=> "Resource successfully pinned",
                'pinnedItem'=>$pinned,
            ],201);
        }
        else{
            return response([
                'status'=>'fail',
                'message'=> "Failed to create pinnedItem",
                'error'=>$pinned,
            ],400);
        }
    }

    public function update(Request $request,$id)
    {

        return response([
            'status'=> 'fail',
            'message'=> "PinnedItem cannot be updated"],
            400);
    }

    public function destroy($id)
    {
        if(PinnedItem::destroy($id)){

            return [
                'status'=>'success',
                'message'=> "Resource has been unpinned",

            ];
        }
        else{
            return response( [
                'status'=>'fail',
                'message'=> "The requested pinnedItem with id:'$id' was not found"
            ],404);
        }
    }
}
