<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

   public function register(Request $request)
   {
       $fields = $request->validate([
           'id' => 'required|string',
           'name' => 'required|string',
           'surname' => 'required|string',
           'username' => 'required|string',
           'email'=> 'required|string|unique:users,email',
           'password'=>'required|confirmed'
       ]);

       $user = User::create([
          'id'=> $fields['id'],
          'name'=> $fields['name'],
          'surname'=> $fields['surname'],
          'username'=> $fields['username'],
          'email'=> $fields['email'],
          'password'=> bcrypt($fields['password'])
       ]);

       $token = $user->createToken('myapptoken')->plainTextToken;
       //CREATE A LOG
       $user['description'] = "Login:".$user['id'];
       $user['category'] = 'users';
       $user['modified_by_user_id'] = $user['id'];
       $this->createLog($user,'login');

       $response = [
           'user' => $user,
           'token' => $token
       ];

       return response($response,201);
   }

   public  function checkUser($email)
   {
       //CHECK EMAIL
       $user = User::where('email',$email)->first();

       if(!$user)
       {
           return response([
               'status' => 'fail',
               'message'=> "That email account does not exist"
           ],404);
       }
       else{
           return response([
               'status' => 'success',
               'message'=> "Email valid proceed to password input",
               'proceed'=> true
           ]);
       }


   }

   public function login(Request $request)
   {
       $fields = $request->validate([
           'email'=> 'required|string',
           'password'=>'required'
       ]);

       //CHECK EMAIL
       $user = User::where('email',$fields['email'])->first();

       //CHECK PASSWORD
       if(!$user || !Hash::check($fields['password'],$user->password)){
           return response([
              'message'=> "Bad Credentials"
           ],401);
       }

       $token = $user->createToken('myapptoken')->plainTextToken;
       //CREATE A LOG
       $user['description'] = "Login:".$user['id'];
       $user['category'] = 'users';
       $user['modified_by_user_id'] = $user['id'];
       $this->createLog($user,'login');

       $response = [
           'authenticated'=> true,
           'user' => $user,
           'token' => $token
       ];

       return response($response,201);
   }

   public function login_route(Request $request){
       return response([
           "message" => 'You are not aunthenticated, please login first'
       ],401);
   }

   public function logout(Request $request)
   {
       auth()->user()->tokens()->delete();

       return [
           "message"=> 'Successfully logged out'
       ];
   }
}
