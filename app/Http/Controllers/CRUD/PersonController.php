<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\Person;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PersonController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(Person::get(),200);
       } else {
          $person = Person::findOrFail($id);
          $attach = [];
          return response()->json(["Person"=>$person, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(Person::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $person = new Person();
          $lastPerson = Person::orderBy('id')->get()->last();
          if($lastPerson) {
             $person->id = $lastPerson->id + 1;
          } else {
             $person->id = 1;
          }
          $person->identification = $result['identification'];
          $person->name = $result['name'];
          $person->last_name = $result['last_name'];
          $person->mobile_number = $result['mobile_number'];
          $person->home_number = $result['home_number'];
          $person->birthday = $result['birthday'];
          $person->email = $result['email'];
          $person->user_id = $result['user_id'];
          $person->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($person,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $person = Person::where('id',$result['id'])->update([
             'identification'=>$result['identification'],
             'name'=>$result['name'],
             'last_name'=>$result['last_name'],
             'mobile_number'=>$result['mobile_number'],
             'home_number'=>$result['home_number'],
             'birthday'=>$result['birthday'],
             'email'=>$result['email'],
             'user_id'=>$result['user_id'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($person,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return Person::destroy($id);
    }

    function backup(Request $data)
    {
       $people = Person::get();
       $toReturn = [];
       foreach( $people as $person) {
          $attach = [];
          array_push($toReturn, ["Person"=>$person, "attach"=>$attach]);
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
         $result = $row['Person'];
         $exist = Person::where('id',$result['id'])->first();
         if ($exist) {
           Person::where('id', $result['id'])->update([
             'identification'=>$result['identification'],
             'name'=>$result['name'],
             'last_name'=>$result['last_name'],
             'mobile_number'=>$result['mobile_number'],
             'home_number'=>$result['home_number'],
             'birthday'=>$result['birthday'],
             'email'=>$result['email'],
             'user_id'=>$result['user_id'],
           ]);
         } else {
          $person = new Person();
          $person->id = $result['id'];
          $person->identification = $result['identification'];
          $person->name = $result['name'];
          $person->last_name = $result['last_name'];
          $person->mobile_number = $result['mobile_number'];
          $person->home_number = $result['home_number'];
          $person->birthday = $result['birthday'];
          $person->email = $result['email'];
          $person->user_id = $result['user_id'];
          $person->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}