<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\InstitutionInternalDivition;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class InstitutionInternalDivitionController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(InstitutionInternalDivition::get(),200);
       } else {
          $institutioninternaldivition = InstitutionInternalDivition::findOrFail($id);
          $attach = [];
          return response()->json(["InstitutionInternalDivition"=>$institutioninternaldivition, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(InstitutionInternalDivition::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $institutioninternaldivition = new InstitutionInternalDivition();
          $lastInstitutionInternalDivition = InstitutionInternalDivition::orderBy('id')->get()->last();
          if($lastInstitutionInternalDivition) {
             $institutioninternaldivition->id = $lastInstitutionInternalDivition->id + 1;
          } else {
             $institutioninternaldivition->id = 1;
          }
          $institutioninternaldivition->description = $result['description'];
          $institutioninternaldivition->institution_id = $result['institution_id'];
          $institutioninternaldivition->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($institutioninternaldivition,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $institutioninternaldivition = InstitutionInternalDivition::where('id',$result['id'])->update([
             'description'=>$result['description'],
             'institution_id'=>$result['institution_id'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($institutioninternaldivition,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return InstitutionInternalDivition::destroy($id);
    }

    function backup(Request $data)
    {
       $institutioninternaldivitions = InstitutionInternalDivition::get();
       $toReturn = [];
       foreach( $institutioninternaldivitions as $institutioninternaldivition) {
          $attach = [];
          array_push($toReturn, ["InstitutionInternalDivition"=>$institutioninternaldivition, "attach"=>$attach]);
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
         $result = $row['InstitutionInternalDivition'];
         $exist = InstitutionInternalDivition::where('id',$result['id'])->first();
         if ($exist) {
           InstitutionInternalDivition::where('id', $result['id'])->update([
             'description'=>$result['description'],
             'institution_id'=>$result['institution_id'],
           ]);
         } else {
          $institutioninternaldivition = new InstitutionInternalDivition();
          $institutioninternaldivition->id = $result['id'];
          $institutioninternaldivition->description = $result['description'];
          $institutioninternaldivition->institution_id = $result['institution_id'];
          $institutioninternaldivition->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}