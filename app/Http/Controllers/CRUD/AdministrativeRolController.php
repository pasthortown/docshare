<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\AdministrativeRol;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AdministrativeRolController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(AdministrativeRol::get(),200);
       } else {
          $administrativerol = AdministrativeRol::findOrFail($id);
          $attach = [];
          return response()->json(["AdministrativeRol"=>$administrativerol, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(AdministrativeRol::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $administrativerol = new AdministrativeRol();
          $lastAdministrativeRol = AdministrativeRol::orderBy('id')->get()->last();
          if($lastAdministrativeRol) {
             $administrativerol->id = $lastAdministrativeRol->id + 1;
          } else {
             $administrativerol->id = 1;
          }
          $administrativerol->description = $result['description'];
          $administrativerol->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($administrativerol,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $administrativerol = AdministrativeRol::where('id',$result['id'])->update([
             'description'=>$result['description'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($administrativerol,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return AdministrativeRol::destroy($id);
    }

    function backup(Request $data)
    {
       $administrativerols = AdministrativeRol::get();
       $toReturn = [];
       foreach( $administrativerols as $administrativerol) {
          $attach = [];
          array_push($toReturn, ["AdministrativeRol"=>$administrativerol, "attach"=>$attach]);
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
         $result = $row['AdministrativeRol'];
         $exist = AdministrativeRol::where('id',$result['id'])->first();
         if ($exist) {
           AdministrativeRol::where('id', $result['id'])->update([
             'description'=>$result['description'],
           ]);
         } else {
          $administrativerol = new AdministrativeRol();
          $administrativerol->id = $result['id'];
          $administrativerol->description = $result['description'];
          $administrativerol->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}