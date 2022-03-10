<?php


namespace App\Http\Controllers;


use App\Models\Assignment;
use App\Models\Estimate;
use App\Models\Prospect;
use App\Models\Task;

class PageController
{
    public function dashboard()
    {
        $tasks = Task::all()->toArray();
        $prospects = Prospect::all()->toArray();
        $assignments = Assignment::all()->toArray();
        $estimates = Estimate::all()->toArray();

        $all_array = array_merge($tasks,$prospects,$assignments,$estimates);
        $combined_array = [];
        //ADD A TIME DIFFERENCE FROM NOW
        foreach ($all_array as $item){
            $item['time_difference'] = strtotime($item['closing_date']) - time();
            $combined_array[] = $item;
        }

        $upcoming = array_filter($combined_array, function ($obj){
            return $obj['time_difference'] > 0;
        });

        //SORT THE ARRAY
        usort($upcoming,function ($obj1, $obj2){
            return $obj1['time_difference'] - $obj2['time_difference'];
        });

        //spare the first 3 items
        $upcoming = array_slice($upcoming, 0, 3);

        //SORT ON DATE MODIFIED
        usort($combined_array, function ($obj1, $obj2) {
            return strtotime($obj1['updated_at']) - strtotime($obj2['updated_at']);
        });

        //spare the first four items
        $recent= array_slice($combined_array, 0, 5);

        return [
            'status'=> 'success',
            "upcoming"=>$upcoming,
            "recent"=>$recent
        ];

    }

}
