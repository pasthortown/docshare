<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\AccountRol;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AccountRolController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(AccountRol::get(),200);
       } else {
          $accountrol = AccountRol::findOrFail($id);
          $attach = [];
          return response()->json(["AccountRol"=>$accountrol, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(AccountRol::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $accountrol = new AccountRol();
          $lastAccountRol = AccountRol::orderBy('id')->get()->last();
          if($lastAccountRol) {
             $accountrol->id = $lastAccountRol->id + 1;
          } else {
             $accountrol->id = 1;
          }
          $accountrol->user_id = $result['user_id'];
          $accountrol->administrative_rol_id = $result['administrative_rol_id'];
          $accountrol->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($accountrol,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $accountrol = AccountRol::where('id',$result['id'])->update([
             'user_id'=>$result['user_id'],
             'administrative_rol_id'=>$result['administrative_rol_id'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($accountrol,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return AccountRol::destroy($id);
    }

    function backup(Request $data)
    {
       $accountrols = AccountRol::get();
       $toReturn = [];
       foreach( $accountrols as $accountrol) {
          $attach = [];
          array_push($toReturn, ["AccountRol"=>$accountrol, "attach"=>$attach]);
       }
       return response()->json($toReturn,200);
    }

    function masiveLoad(Request $data)
    {
      $incomming = $data->json()->all();
      $masiveData = $incomming['data'];
      try{
       DB::beginTransaction();
       foreach($masiveData as $row) {
         $result = $row['AccountRol'];
         $exist = AccountRol::where('id',$result['id'])->first();
         if ($exist) {
           AccountRol::where('id', $result['id'])->update([
             'user_id'=>$result['user_id'],
             'administrative_rol_id'=>$result['administrative_rol_id'],
           ]);
         } else {
          $accountrol = new AccountRol();
          $accountrol->id = $result['id'];
          $accountrol->user_id = $result['user_id'];
          $accountrol->administrative_rol_id = $result['administrative_rol_id'];
          $accountrol->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}