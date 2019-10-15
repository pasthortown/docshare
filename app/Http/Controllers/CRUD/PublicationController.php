<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\Publication;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PublicationController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(Publication::get(),200);
       } else {
          $publication = Publication::findOrFail($id);
          $attach = [];
          $authors_on_publication = $publication->Authors()->get();
          array_push($attach, ["authors_on_publication"=>$authors_on_publication]);
          return response()->json(["Publication"=>$publication, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(Publication::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $publication = new Publication();
          $lastPublication = Publication::orderBy('id')->get()->last();
          if($lastPublication) {
             $publication->id = $lastPublication->id + 1;
          } else {
             $publication->id = 1;
          }
          $publication->title = $result['title'];
          $publication->abstract = $result['abstract'];
          $publication->written_date = $result['written_date'];
          $publication->published_date = $result['published_date'];
          $publication->keywords = $result['keywords'];
          $publication->publication_type_id = $result['publication_type_id'];
          $publication->institution_internal_divition_id = $result['institution_internal_divition_id'];
          $publication->save();
          $authors_on_publication = $result['authors_on_publication'];
          foreach( $authors_on_publication as $author) {
             $publication->Authors()->attach($author['id']);
          }
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($publication,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $publication = Publication::where('id',$result['id'])->update([
             'title'=>$result['title'],
             'abstract'=>$result['abstract'],
             'written_date'=>$result['written_date'],
             'published_date'=>$result['published_date'],
             'keywords'=>$result['keywords'],
             'publication_type_id'=>$result['publication_type_id'],
             'institution_internal_divition_id'=>$result['institution_internal_divition_id'],
          ]);
          $publication = Publication::where('id',$result['id'])->first();
          $authors_on_publication = $result['authors_on_publication'];
          $authors_on_publication_old = $publication->Authors()->get();
          foreach( $authors_on_publication_old as $author_old ) {
             $delete = true;
             foreach( $authors_on_publication as $author ) {
                if ( $author_old->id === $author['id'] ) {
                   $delete = false;
                }
             }
             if ( $delete ) {
                $publication->Authors()->detach($author_old->id);
             }
          }
          foreach( $authors_on_publication as $author ) {
             $add = true;
             foreach( $authors_on_publication_old as $author_old) {
                if ( $author_old->id === $author['id'] ) {
                   $add = false;
                }
             }
             if ( $add ) {
                $publication->Authors()->attach($author['id']);
             }
          }
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($publication,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return Publication::destroy($id);
    }

    function backup(Request $data)
    {
       $publications = Publication::get();
       $toReturn = [];
       foreach( $publications as $publication) {
          $attach = [];
          $authors_on_publication = $publication->Authors()->get();
          array_push($attach, ["authors_on_publication"=>$authors_on_publication]);
          array_push($toReturn, ["Publication"=>$publication, "attach"=>$attach]);
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
         $result = $row['Publication'];
         $exist = Publication::where('id',$result['id'])->first();
         if ($exist) {
           Publication::where('id', $result['id'])->update([
             'title'=>$result['title'],
             'abstract'=>$result['abstract'],
             'written_date'=>$result['written_date'],
             'published_date'=>$result['published_date'],
             'keywords'=>$result['keywords'],
             'publication_type_id'=>$result['publication_type_id'],
             'institution_internal_divition_id'=>$result['institution_internal_divition_id'],
           ]);
         } else {
          $publication = new Publication();
          $publication->id = $result['id'];
          $publication->title = $result['title'];
          $publication->abstract = $result['abstract'];
          $publication->written_date = $result['written_date'];
          $publication->published_date = $result['published_date'];
          $publication->keywords = $result['keywords'];
          $publication->publication_type_id = $result['publication_type_id'];
          $publication->institution_internal_divition_id = $result['institution_internal_divition_id'];
          $publication->save();
         }
         $publication = Publication::where('id',$result['id'])->first();
         $authors_on_publication = [];
         foreach($row['attach'] as $attach){
            $authors_on_publication = $attach['authors_on_publication'];
         }
         $authors_on_publication_old = $publication->Authors()->get();
         foreach( $authors_on_publication_old as $author_old ) {
            $delete = true;
            foreach( $authors_on_publication as $author ) {
               if ( $author_old->id === $author['id'] ) {
                  $delete = false;
               }
            }
            if ( $delete ) {
               $publication->Authors()->detach($author_old->id);
            }
         }
         foreach( $authors_on_publication as $author ) {
            $add = true;
            foreach( $authors_on_publication_old as $author_old) {
               if ( $author_old->id === $author['id'] ) {
                  $add = false;
               }
            }
            if ( $add ) {
               $publication->Authors()->attach($author['id']);
            }
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}