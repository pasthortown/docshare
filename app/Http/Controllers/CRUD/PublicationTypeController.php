<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\PublicationType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PublicationTypeController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(PublicationType::get(),200);
       } else {
          $publicationtype = PublicationType::findOrFail($id);
          $attach = [];
          return response()->json(["PublicationType"=>$publicationtype, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(PublicationType::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $publicationtype = new PublicationType();
          $lastPublicationType = PublicationType::orderBy('id')->get()->last();
          if($lastPublicationType) {
             $publicationtype->id = $lastPublicationType->id + 1;
          } else {
             $publicationtype->id = 1;
          }
          $publicationtype->name = $result['name'];
          $publicationtype->description = $result['description'];
          $publicationtype->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($publicationtype,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $publicationtype = PublicationType::where('id',$result['id'])->update([
             'name'=>$result['name'],
             'description'=>$result['description'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($publicationtype,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return PublicationType::destroy($id);
    }

    function backup(Request $data)
    {
       $publicationtypes = PublicationType::get();
       $toReturn = [];
       foreach( $publicationtypes as $publicationtype) {
          $attach = [];
          array_push($toReturn, ["PublicationType"=>$publicationtype, "attach"=>$attach]);
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
         $result = $row['PublicationType'];
         $exist = PublicationType::where('id',$result['id'])->first();
         if ($exist) {
           PublicationType::where('id', $result['id'])->update([
             'name'=>$result['name'],
             'description'=>$result['description'],
           ]);
         } else {
          $publicationtype = new PublicationType();
          $publicationtype->id = $result['id'];
          $publicationtype->name = $result['name'];
          $publicationtype->description = $result['description'];
          $publicationtype->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}