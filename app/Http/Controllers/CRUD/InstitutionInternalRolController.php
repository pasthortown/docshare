<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\InstitutionInternalRol;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class InstitutionInternalRolController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(InstitutionInternalRol::get(),200);
       } else {
          $institutioninternalrol = InstitutionInternalRol::findOrFail($id);
          $attach = [];
          return response()->json(["InstitutionInternalRol"=>$institutioninternalrol, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(InstitutionInternalRol::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $institutioninternalrol = new InstitutionInternalRol();
          $lastInstitutionInternalRol = InstitutionInternalRol::orderBy('id')->get()->last();
          if($lastInstitutionInternalRol) {
             $institutioninternalrol->id = $lastInstitutionInternalRol->id + 1;
          } else {
             $institutioninternalrol->id = 1;
          }
          $institutioninternalrol->name = $result['name'];
          $institutioninternalrol->description = $result['description'];
          $institutioninternalrol->institution_id = $result['institution_id'];
          $institutioninternalrol->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($institutioninternalrol,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $institutioninternalrol = InstitutionInternalRol::where('id',$result['id'])->update([
             'name'=>$result['name'],
             'description'=>$result['description'],
             'institution_id'=>$result['institution_id'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($institutioninternalrol,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return InstitutionInternalRol::destroy($id);
    }

    function backup(Request $data)
    {
       $institutioninternalrols = InstitutionInternalRol::get();
       $toReturn = [];
       foreach( $institutioninternalrols as $institutioninternalrol) {
          $attach = [];
          array_push($toReturn, ["InstitutionInternalRol"=>$institutioninternalrol, "attach"=>$attach]);
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
         $result = $row['InstitutionInternalRol'];
         $exist = InstitutionInternalRol::where('id',$result['id'])->first();
         if ($exist) {
           InstitutionInternalRol::where('id', $result['id'])->update([
             'name'=>$result['name'],
             'description'=>$result['description'],
             'institution_id'=>$result['institution_id'],
           ]);
         } else {
          $institutioninternalrol = new InstitutionInternalRol();
          $institutioninternalrol->id = $result['id'];
          $institutioninternalrol->name = $result['name'];
          $institutioninternalrol->description = $result['description'];
          $institutioninternalrol->institution_id = $result['institution_id'];
          $institutioninternalrol->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}