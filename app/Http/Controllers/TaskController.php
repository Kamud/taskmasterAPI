<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::latest()->get();
        foreach ($tasks as $task)
        {
            $task->assigned_user;
            $task->modified_by;
            unset($task->assigned_user_id,$task->modified_by_user_id);
        }

        return [
            'status'=> 'success',
            'count'=>count($tasks),
            "tasks"=>$tasks,
        ];
    }

    public function show($id)
    {
        $task = Task::find($id);

        if($task)
        {
            $task->assigned_user;
            $task->modified_by;
            unset($task->assigned_user_id,$task->modified_by_user_id);
            return [
                'status'=> 'success',
                'task' => $task
            ];
        }
        else
        {
           return response([
               'status'=> 'fail',
               'message'=> "The requested task with id:'$id' was not found"],
               404);
        }


    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'description' => 'required',
            'closing_date' => 'required'
        ]);


        //CREATE A UNIQUE ID and SLUG
        $newTask = $request->all();
        $newTask["id"] = "task_".Str::random(10);
        $newTask['slug'] = Str::slug($newTask['description']);
        $newTask['modified_by_user_id'] = Auth::id();

        $task = Task::create($newTask);
        if($task){
        //CREATE A LOG
        $task['description'] = $task['description'].";".$task['Task'];
        $this->createLog($task,'created');

        return response([
            'status'=>'success',
            'message'=> "New Task with id: '$task->id' has been created",
            'task'=>$task,
        ],201);
        }
        else{
            return response([
                'status'=>'fail',
                'message'=> "Failed to create task",
                'error'=>$task,
            ],400);
        }
    }

    public function update(Request $request,$id)
        {

            $task = Task::find($id);
            if(!$task){
                return response([
                    'status'=> 'fail',
                    'message'=> "The requested task with id:'$id' was not found"],
                    404);
            }

            $newTask = $request->all();
            $newTask['modified_by_user_id'] = Auth::id();

            if(array_key_exists('description',$newTask)){
                $newTask['slug'] = Str::slug($newTask['description']);
            }

            //PREVENT ID FROM UPDATING
            unset($newTask['id']);
            $task->update($newTask);
            //CREATE A LOG
            $task['description'] = $task['description'].";".$task['Task'];
            $this->createLog($task,'modified');
            return response([
                'status'=>'success',
                'message'=> "Task with id: '$task->id' has been updated",
                'task'=>$task,
            ],201);
        }

        public function destroy($id)
        {
            $task = Task::find($id);
            if($task && Task::destroy($id)){
            //CREATE A LOG
            $task['description'] = $task['description'].";".$task['Task'];
            $this->createLog($task,'deleted');

            return [
                'status'=>'success',
                'message'=> "Task with id: '$id' has been deleted",

            ];
            }
            else{
                return response( [
                    'status'=>'fail',
                    'message'=> "The requested task with id:'$id' was not found"
                ],404);
            }
        }

        public function search($name)
        {
            $tasks = Task::where('description', 'like', '%'.$name.'%')->get();
            foreach ($tasks as $task)
            {
                $task->assigned_user;
                $task->modified_by;
                unset($task->assigned_user_id,$task->modified_by_user_id);
            }

            return [
                'status'=> 'success',
                'count'=>count($tasks),
                "tasks"=>$tasks,
            ];
        }
}
