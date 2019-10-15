<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\Author;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AuthorController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(Author::get(),200);
       } else {
          $author = Author::findOrFail($id);
          $attach = [];
          return response()->json(["Author"=>$author, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(Author::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $author = new Author();
          $lastAuthor = Author::orderBy('id')->get()->last();
          if($lastAuthor) {
             $author->id = $lastAuthor->id + 1;
          } else {
             $author->id = 1;
          }
          $author->name = $result['name'];
          $author->last_name = $result['last_name'];
          $author->affiliation = $result['affiliation'];
          $author->email = $result['email'];
          $author->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($author,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $author = Author::where('id',$result['id'])->update([
             'name'=>$result['name'],
             'last_name'=>$result['last_name'],
             'affiliation'=>$result['affiliation'],
             'email'=>$result['email'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($author,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return Author::destroy($id);
    }

    function backup(Request $data)
    {
       $authors = Author::get();
       $toReturn = [];
       foreach( $authors as $author) {
          $attach = [];
          array_push($toReturn, ["Author"=>$author, "attach"=>$attach]);
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
         $result = $row['Author'];
         $exist = Author::where('id',$result['id'])->first();
         if ($exist) {
           Author::where('id', $result['id'])->update([
             'name'=>$result['name'],
             'last_name'=>$result['last_name'],
             'affiliation'=>$result['affiliation'],
             'email'=>$result['email'],
           ]);
         } else {
          $author = new Author();
          $author->id = $result['id'];
          $author->name = $result['name'];
          $author->last_name = $result['last_name'];
          $author->affiliation = $result['affiliation'];
          $author->email = $result['email'];
          $author->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}