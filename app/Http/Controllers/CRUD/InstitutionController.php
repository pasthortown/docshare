<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\Institution;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class InstitutionController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(Institution::get(),200);
       } else {
          $institution = Institution::findOrFail($id);
          $attach = [];
          return response()->json(["Institution"=>$institution, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(Institution::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $institution = new Institution();
          $lastInstitution = Institution::orderBy('id')->get()->last();
          if($lastInstitution) {
             $institution->id = $lastInstitution->id + 1;
          } else {
             $institution->id = 1;
          }
          $institution->name = $result['name'];
          $institution->address = $result['address'];
          $institution->address_map_latitude = $result['address_map_latitude'];
          $institution->address_map_longitude = $result['address_map_longitude'];
          $institution->phone_number = $result['phone_number'];
          $institution->web = $result['web'];
          $institution->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($institution,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $institution = Institution::where('id',$result['id'])->update([
             'name'=>$result['name'],
             'address'=>$result['address'],
             'address_map_latitude'=>$result['address_map_latitude'],
             'address_map_longitude'=>$result['address_map_longitude'],
             'phone_number'=>$result['phone_number'],
             'web'=>$result['web'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($institution,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return Institution::destroy($id);
    }

    function backup(Request $data)
    {
       $institutions = Institution::get();
       $toReturn = [];
       foreach( $institutions as $institution) {
          $attach = [];
          array_push($toReturn, ["Institution"=>$institution, "attach"=>$attach]);
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
         $result = $row['Institution'];
         $exist = Institution::where('id',$result['id'])->first();
         if ($exist) {
           Institution::where('id', $result['id'])->update([
             'name'=>$result['name'],
             'address'=>$result['address'],
             'address_map_latitude'=>$result['address_map_latitude'],
             'address_map_longitude'=>$result['address_map_longitude'],
             'phone_number'=>$result['phone_number'],
             'web'=>$result['web'],
           ]);
         } else {
          $institution = new Institution();
          $institution->id = $result['id'];
          $institution->name = $result['name'];
          $institution->address = $result['address'];
          $institution->address_map_latitude = $result['address_map_latitude'];
          $institution->address_map_longitude = $result['address_map_longitude'];
          $institution->phone_number = $result['phone_number'];
          $institution->web = $result['web'];
          $institution->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}