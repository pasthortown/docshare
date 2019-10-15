<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\InstitutionInternalRolAssignment;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class InstitutionInternalRolAssignmentController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(InstitutionInternalRolAssignment::get(),200);
       } else {
          $institutioninternalrolassignment = InstitutionInternalRolAssignment::findOrFail($id);
          $attach = [];
          return response()->json(["InstitutionInternalRolAssignment"=>$institutioninternalrolassignment, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(InstitutionInternalRolAssignment::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $institutioninternalrolassignment = new InstitutionInternalRolAssignment();
          $lastInstitutionInternalRolAssignment = InstitutionInternalRolAssignment::orderBy('id')->get()->last();
          if($lastInstitutionInternalRolAssignment) {
             $institutioninternalrolassignment->id = $lastInstitutionInternalRolAssignment->id + 1;
          } else {
             $institutioninternalrolassignment->id = 1;
          }
          $institutioninternalrolassignment->date = $result['date'];
          $institutioninternalrolassignment->institution_internal_rol_id = $result['institution_internal_rol_id'];
          $institutioninternalrolassignment->person_id = $result['person_id'];
          $institutioninternalrolassignment->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($institutioninternalrolassignment,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $institutioninternalrolassignment = InstitutionInternalRolAssignment::where('id',$result['id'])->update([
             'date'=>$result['date'],
             'institution_internal_rol_id'=>$result['institution_internal_rol_id'],
             'person_id'=>$result['person_id'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($institutioninternalrolassignment,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return InstitutionInternalRolAssignment::destroy($id);
    }

    function backup(Request $data)
    {
       $institutioninternalrolassignments = InstitutionInternalRolAssignment::get();
       $toReturn = [];
       foreach( $institutioninternalrolassignments as $institutioninternalrolassignment) {
          $attach = [];
          array_push($toReturn, ["InstitutionInternalRolAssignment"=>$institutioninternalrolassignment, "attach"=>$attach]);
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
         $result = $row['InstitutionInternalRolAssignment'];
         $exist = InstitutionInternalRolAssignment::where('id',$result['id'])->first();
         if ($exist) {
           InstitutionInternalRolAssignment::where('id', $result['id'])->update([
             'date'=>$result['date'],
             'institution_internal_rol_id'=>$result['institution_internal_rol_id'],
             'person_id'=>$result['person_id'],
           ]);
         } else {
          $institutioninternalrolassignment = new InstitutionInternalRolAssignment();
          $institutioninternalrolassignment->id = $result['id'];
          $institutioninternalrolassignment->date = $result['date'];
          $institutioninternalrolassignment->institution_internal_rol_id = $result['institution_internal_rol_id'];
          $institutioninternalrolassignment->person_id = $result['person_id'];
          $institutioninternalrolassignment->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}