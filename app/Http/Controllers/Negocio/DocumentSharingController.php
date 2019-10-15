<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use App\User;
use App\PublicationComment;
use App\PublicationAttachment;
use App\Person;
use App\Publication;
use App\Institution;
use App\InstitutionLogo;
use App\InstitutionInternalRolAssignment;
use App\InstitutionInternalRol;
use App\InstitutionInternalDivition;
use App\PublicationType;
use App\AccountRol;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DocumentSharingController extends Controller
{
    function my_institution(Request $data)
    {
      $result = $data->json()->all();
      $id = $data->auth->id;
      $person = Person::where('user_id', $id)->first();
      $institutionInternalRolAssignmet = InstitutionInternalRolAssignment::where('person_id',$person->id)->first();
      $institutionInternalRol = InstitutionInternalRol::where('id', $institutionInternalRolAssignmet->institution_internal_rol_id)->first();
      $institutionInternalRols = InstitutionInternalRol::where('institution_id', $institutionInternalRol->institution_id)->get();
      $institution = Institution::where('id', $institutionInternalRol->institution_id)->first();
      $institutionLogo = InstitutionLogo::where('institution_id', $institution->id)->first();
      $institution_internal_divition = InstitutionInternalDivition::where('institution_id', $institution->id)->get();
      return response()->json(["institution" => $institution, "institution_logo" => $institutionLogo, "institution_internal_divition" => $institution_internal_divition, "institution_internal_rols"=>$institutionInternalRols],200);
    }

    function get_institution_publications(Request $data) {
      $result = $data->json()->all();
      $id = $data->auth->id;
      $filter = $result['filter'];
      $person = Person::where('user_id', $id)->first();
      $institutionInternalRolAssignmet = InstitutionInternalRolAssignment::where('person_id',$person->id)->first();
      $institutionInternalRol = InstitutionInternalRol::where('id', $institutionInternalRolAssignmet->institution_internal_rol_id)->first();
      $institution = Institution::where('id', $institutionInternalRol->institution_id)->first();
      $institutionInternalDivitions = InstitutionInternalDivition::where('institution_id', $institution->id)->get();
      $toReturn = [];
      foreach($institutionInternalDivitions as $institutionInternalDivition) {
        $publications = Publication::orderBy('created_at','DESC')->where('institution_internal_divition_id', $institutionInternalDivition->id);
        if($filter !== '') {
          $publications = $publications->where(function($output) use ($filter) {
            return $output->where('title', 'ilike', '%'.$filter.'%')
            ->orWhere('abstract', 'ilike', '%'.$filter.'%')
            ->orWhere('keywords', 'ilike', '%'.$filter.'%');
          });            
        }
        $publications = $publications->get();
        foreach($publications as $publication) {
          $authors = $publication->Authors()->get();
          $publicationType = PublicationType::where('id',$publication->publication_type_id)->first();
          array_push($toReturn, ["publicationType" => $publicationType, "institution_internal_divition" => $institutionInternalDivition, "authors"=>$authors ,"publication" => $publication]);
        } 
      }
      return response()->json($toReturn,200);
    }

    function get_institution_publishers(Request $data) {
      $result = $data->json()->all();
      $id = $data->auth->id;
      $person = Person::where('user_id', $id)->first();
      $institutionInternalRolAssignmet = InstitutionInternalRolAssignment::where('person_id',$person->id)->first();
      $institutionInternalRol = InstitutionInternalRol::where('id', $institutionInternalRolAssignmet->institution_internal_rol_id)->first();
      $institutionInternalRols = InstitutionInternalRol::where('institution_id', $institutionInternalRol->institution_id)->get();
      $institutionInternalRolAssignmets = [];
      foreach($institutionInternalRols as $institutionInternalRol) {
        $institutionInternalRolAssignmetsToAdd = InstitutionInternalRolAssignment::where('institution_internal_rol_id', $institutionInternalRol->id)->get();
        foreach($institutionInternalRolAssignmetsToAdd as $institutionInternalRolAssignmetToAdd) {
          array_push($institutionInternalRolAssignmets, $institutionInternalRolAssignmetToAdd);
        }
      }
      $publishers = [];
      foreach($institutionInternalRolAssignmets as $institutionInternalRolAssignmet) {
        $existe = false;
        foreach($publishers as $publisher) {
          if ($publisher->id === $institutionInternalRolAssignmet->person_id) {
            $existe = true;
          }
        }
        if(!$existe) {
          $person = Person::where('id', $institutionInternalRolAssignmet->person_id)->first();
          array_push($publishers, $person);
        }
      }
      $people = Person::get();
      $candidates = [];
      foreach($people as $person) {
        $user = User::where('id', $person->user_id)->first();
        $accountRol = AccountRol::where('user_id', $user->id)->first();
        if($accountRol->administrative_rol_id == 4) {
          array_push($candidates, $person);
        }
      }
      $toReturn = ["institution_internal_rols"=>$institutionInternalRols, "institution_internal_rol_assignmets" => $institutionInternalRolAssignmets, "publishers" => $publishers, "candidates" => $candidates];
      return response()->json($toReturn,200);
    }

    function get_publication_comments(Request $data) {
      $result = $data->json()->all();
      $publication_id = $result['publication_id'];
      $comments = PublicationComment::where('publication_id', $publication_id)->orderBy('created_at','DESC')->get();
      $toReturn = [];
      foreach($comments as $comment) {
        $author = Person::where('id',$comment->person_id)->first();
        array_push($toReturn,["comment"=>$comment, "author"=>$author]);
      }

      return response()->json($toReturn,200);
    }

    function person_user(Request $data) {
      $result = $data->json()->all();
      $id = $data->auth->id;
      $person = Person::where('user_id', $id)->first();
      if($person) {
        return response()->json($person,200);
      } else {
        $person = new Person();
        $person->id = 0;
        $person->user_id = $id;
        return response()->json($person,200);
      }
      return $person;
    }

    function get_publication_attachment_by_publication_id(Request $data) {
      $result = $data->json()->all();
      $publication_id = $result['publication_id'];
      $attachment = PublicationAttachment::where('publication_id', $publication_id)->first();
      return response()->json($attachment,200);
    }

    function publications_filtered(Request $data)
    {
      $result = $data->json()->all();
      $size = $result['size'];
      $institution_internal_divition_id = $result['institution_internal_divition_id'];
      $filter = $result['filter'];
      $toGet = Publication::orderBy('created_at','DESC');
      if($institution_internal_divition_id != 0 ) {
         $toGet = $toGet->where('institution_internal_divition_id', $institution_internal_divition_id);
      }
      if($filter !== '') {
         $toGet = $toGet->where('title', 'ilike', '%'.$filter.'%')->orWhere('abstract', 'ilike', '%'.$filter.'%')->orWhere('keywords', 'ilike', '%'.$filter.'%');
      }
      $toReturn = $toGet->paginate($size);
       return response()->json($toReturn, 200);
    }
}