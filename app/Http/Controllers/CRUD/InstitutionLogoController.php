<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\InstitutionLogo;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class InstitutionLogoController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(InstitutionLogo::get(),200);
       } else {
          $institutionlogo = InstitutionLogo::findOrFail($id);
          $attach = [];
          return response()->json(["InstitutionLogo"=>$institutionlogo, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(InstitutionLogo::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $institutionlogo = new InstitutionLogo();
          $lastInstitutionLogo = InstitutionLogo::orderBy('id')->get()->last();
          if($lastInstitutionLogo) {
             $institutionlogo->id = $lastInstitutionLogo->id + 1;
          } else {
             $institutionlogo->id = 1;
          }
          $institutionlogo->institution_logo_file_type = $result['institution_logo_file_type'];
          $institutionlogo->institution_logo_file_name = $result['institution_logo_file_name'];
          $institutionlogo->institution_logo_file = $result['institution_logo_file'];
          $institutionlogo->institution_id = $result['institution_id'];
          $institutionlogo->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($institutionlogo,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $institutionlogo = InstitutionLogo::where('id',$result['id'])->update([
             'institution_logo_file_type'=>$result['institution_logo_file_type'],
             'institution_logo_file_name'=>$result['institution_logo_file_name'],
             'institution_logo_file'=>$result['institution_logo_file'],
             'institution_id'=>$result['institution_id'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($institutionlogo,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return InstitutionLogo::destroy($id);
    }

    function backup(Request $data)
    {
       $institutionlogos = InstitutionLogo::get();
       $toReturn = [];
       foreach( $institutionlogos as $institutionlogo) {
          $attach = [];
          array_push($toReturn, ["InstitutionLogo"=>$institutionlogo, "attach"=>$attach]);
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
         $result = $row['InstitutionLogo'];
         $exist = InstitutionLogo::where('id',$result['id'])->first();
         if ($exist) {
           InstitutionLogo::where('id', $result['id'])->update([
             'institution_logo_file_type'=>$result['institution_logo_file_type'],
             'institution_logo_file_name'=>$result['institution_logo_file_name'],
             'institution_logo_file'=>$result['institution_logo_file'],
             'institution_id'=>$result['institution_id'],
           ]);
         } else {
          $institutionlogo = new InstitutionLogo();
          $institutionlogo->id = $result['id'];
          $institutionlogo->institution_logo_file_type = $result['institution_logo_file_type'];
          $institutionlogo->institution_logo_file_name = $result['institution_logo_file_name'];
          $institutionlogo->institution_logo_file = $result['institution_logo_file'];
          $institutionlogo->institution_id = $result['institution_id'];
          $institutionlogo->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}