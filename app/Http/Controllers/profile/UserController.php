<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\User;
use App\AccountRol;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Crypt;

class UserController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(User::get(),200);
       } else {
          return response()->json(User::findOrFail($id),200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(User::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $user = new User();
          $lastUser = User::orderBy('id')->get()->last();
          if($lastUser) {
             $user->id = $lastUser->id + 1;
          } else {
             $user->id = 1;
          }
          $user->name = $result['name'];
          $user->email = $result['email'];
          $user->password = Crypt::encrypt($request['password']);
          $user->api_token = $result['api_token'];
          $user->save();
          $account = new AccountRol();
          $account->user_id = $user->id;
          $account->administrative_rol_id = 4;
          $account->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($user,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $user = User::where('id',$result['id'])->update([
             'name'=>$result['name'],
             'email'=>$result['email'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($user,200);
    }

    function delete(Request $data)
    {
       $result = $data->json()->all();
       $id = $result['id'];
       return User::destroy($id);
    }
}