<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\PublicationComment;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PublicationCommentController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(PublicationComment::get(),200);
       } else {
          $publicationcomment = PublicationComment::findOrFail($id);
          $attach = [];
          return response()->json(["PublicationComment"=>$publicationcomment, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(PublicationComment::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $publicationcomment = new PublicationComment();
          $lastPublicationComment = PublicationComment::orderBy('id')->get()->last();
          if($lastPublicationComment) {
             $publicationcomment->id = $lastPublicationComment->id + 1;
          } else {
             $publicationcomment->id = 1;
          }
          $publicationcomment->content = $result['content'];
          $publicationcomment->publication_id = $result['publication_id'];
          $publicationcomment->person_id = $result['person_id'];
          $publicationcomment->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($publicationcomment,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $publicationcomment = PublicationComment::where('id',$result['id'])->update([
             'content'=>$result['content'],
             'publication_id'=>$result['publication_id'],
             'person_id'=>$result['person_id'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($publicationcomment,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return PublicationComment::destroy($id);
    }

    function backup(Request $data)
    {
       $publicationcomments = PublicationComment::get();
       $toReturn = [];
       foreach( $publicationcomments as $publicationcomment) {
          $attach = [];
          array_push($toReturn, ["PublicationComment"=>$publicationcomment, "attach"=>$attach]);
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
         $result = $row['PublicationComment'];
         $exist = PublicationComment::where('id',$result['id'])->first();
         if ($exist) {
           PublicationComment::where('id', $result['id'])->update([
             'content'=>$result['content'],
             'publication_id'=>$result['publication_id'],
             'person_id'=>$result['person_id'],
           ]);
         } else {
          $publicationcomment = new PublicationComment();
          $publicationcomment->id = $result['id'];
          $publicationcomment->content = $result['content'];
          $publicationcomment->publication_id = $result['publication_id'];
          $publicationcomment->person_id = $result['person_id'];
          $publicationcomment->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}