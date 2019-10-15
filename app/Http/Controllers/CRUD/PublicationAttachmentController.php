<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\PublicationAttachment;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PublicationAttachmentController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(PublicationAttachment::get(),200);
       } else {
          $publicationattachment = PublicationAttachment::findOrFail($id);
          $attach = [];
          return response()->json(["PublicationAttachment"=>$publicationattachment, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(PublicationAttachment::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $publicationattachment = new PublicationAttachment();
          $lastPublicationAttachment = PublicationAttachment::orderBy('id')->get()->last();
          if($lastPublicationAttachment) {
             $publicationattachment->id = $lastPublicationAttachment->id + 1;
          } else {
             $publicationattachment->id = 1;
          }
          $publicationattachment->publication_attachment_file_type = $result['publication_attachment_file_type'];
          $publicationattachment->publication_attachment_file_name = $result['publication_attachment_file_name'];
          $publicationattachment->publication_attachment_file = $result['publication_attachment_file'];
          $publicationattachment->publication_id = $result['publication_id'];
          $publicationattachment->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($publicationattachment,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $publicationattachment = PublicationAttachment::where('id',$result['id'])->update([
             'publication_attachment_file_type'=>$result['publication_attachment_file_type'],
             'publication_attachment_file_name'=>$result['publication_attachment_file_name'],
             'publication_attachment_file'=>$result['publication_attachment_file'],
             'publication_id'=>$result['publication_id'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($publicationattachment,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return PublicationAttachment::destroy($id);
    }

    function backup(Request $data)
    {
       $publicationattachments = PublicationAttachment::get();
       $toReturn = [];
       foreach( $publicationattachments as $publicationattachment) {
          $attach = [];
          array_push($toReturn, ["PublicationAttachment"=>$publicationattachment, "attach"=>$attach]);
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
         $result = $row['PublicationAttachment'];
         $exist = PublicationAttachment::where('id',$result['id'])->first();
         if ($exist) {
           PublicationAttachment::where('id', $result['id'])->update([
             'publication_attachment_file_type'=>$result['publication_attachment_file_type'],
             'publication_attachment_file_name'=>$result['publication_attachment_file_name'],
             'publication_attachment_file'=>$result['publication_attachment_file'],
             'publication_id'=>$result['publication_id'],
           ]);
         } else {
          $publicationattachment = new PublicationAttachment();
          $publicationattachment->id = $result['id'];
          $publicationattachment->publication_attachment_file_type = $result['publication_attachment_file_type'];
          $publicationattachment->publication_attachment_file_name = $result['publication_attachment_file_name'];
          $publicationattachment->publication_attachment_file = $result['publication_attachment_file'];
          $publicationattachment->publication_id = $result['publication_id'];
          $publicationattachment->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}