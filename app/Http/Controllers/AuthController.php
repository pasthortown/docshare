<?php

namespace App\Http\Controllers;

use Validator;
use Exception;
use App\User;
use App\AccountRol;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Crypt;

class AuthController extends Controller
{
  function passwordRecoveryRequest(Request $data) {
    $result = $data->json()->all();
    $email = $result['email'];
    $user = User::where('email', $email)->first();
    if(!$user){
      return response()->json('Ocurrió un error',400);
    }
    $enlace = env('APP_URL').'password_recovery/?r='.$user->api_token;
    $message = "Para cambiar tu contraseña da click en el siguiente enlace: " . $enlace;
    $subject = "Solicitud de Cambio de Contraseña";
    return $this->send_mail($user->email, $user->name, $subject, $message, env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
  }

  function passwordRecovery(Request $data)
  {
    $token = $data['r'];
    $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
    try{
      $new_password = str_random(10);
      DB::beginTransaction();
      $status = User::where('id', $credentials->subject)->update([
        'password'=>Crypt::encrypt($new_password),
      ]);
      if(!$status){
        return response()->json('Ocurrió un error',400);
      }
      DB::commit();
    } catch (Exception $e) {
      return response()->json('Ocurrió un error',400);
    }
    $message = "Tu nueva contraseña es " . $new_password;
    $subject = "Recuperación de Contraseña";
    $user = User::where('id', $credentials->subject)->first();
    return $this->send_mail($user->email, $user->name, $subject, $message, env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
  }

  function passwordChange(Request $data)
  {
    $result = $data->json()->all();
    $id = $data->auth->id;
    $new_password = $result['new_password'];
    try{
      DB::beginTransaction();
      $user = User::find($id)->update([
        'password'=>Crypt::encrypt($new_password),
      ]);
      DB::commit();
    } catch (Exception $e) {
      return response()->json([
        'error' => 'Bad Credentials'
      ], 400);
    }
    return response()->json('Password changed successfully',200);
  }
  
  function register(Request $data)
  {
    try{
      $new_password = str_random(10);
      DB::beginTransaction();
      $result = $data->json()->all();
      $user = new User();
      $lastUser = User::orderBy('id')->get()->last();
      if($lastUser) {
          $user->id = $lastUser->id + 1;
      } else {
          $user->id = 1;
      }
      $user->name = $result['name'];
      $user->email = $result['email'];
      $user->password = Crypt::encrypt($new_password);
      $user->api_token = str_random(64);
      $user->save();
      $account = new AccountRol();
      $lastAccount = AccountRol::orderBy('id')->get()->last();
      if($lastAccount) {
          $account->id = $lastAccount->id + 1;
      } else {
          $account->id = 1;
      }
      $account->user_id = $user->id;
      $account->administrative_rol_id = 4;
      $account->save();
      $message = "Tu nueva contraseña es " . $new_password;
      $subject = "Te damos la bienvenida a " . env('MAIL_FROM_NAME');
      DB::commit();
      return $this->send_mail($user->email, $user->name, $subject, $message, env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
    } catch (Exception $e) {
      return response()->json($e,400);
    }
    return response()->json($user,200);
  }
  
  function login(Request $data)
  {
      $result = $data->json()->all();
      $email = $result['email'];
      $password = $result['password'];
      $user = User::where('email', $email)->first();
      if (!$user) {
        return response()->json([
          'error' => 'Bad Credentials'
        ], 400);
      }
      if ($password === Crypt::decrypt($user->password)) {
        $token = $this->jwt($user);
        $response = User::where('id',$user->id)->update([
          'api_token'=>$token,
        ]);
        $rol = AccountRol::where('user_id',$user->id)->get();
        return response()->json([
            'token' => $token,
            'name' => $user->name,
            'id' => $user->id,
            'rol' => $rol,
        ], 200);
      }
      return response()->json([
        'error' => 'Bad Credentials'
      ], 400);
  }

  protected function jwt(User $user) {
    $payload = [
        'subject' => $user->id,
        'creation_time' => time(),
        'expiration_time' => time() + 60*60
    ];
    return JWT::encode($payload, env('JWT_SECRET'));
  }

  protected function send_mail($to, $toAlias, $subject, $body, $fromMail,$fromAlias) {
    $data = ['name'=>$toAlias, 'body'=>$body, 'appName'=>env('MAIL_FROM_NAME')];
    Mail::send('mail', $data, function($message) use ($to, $toAlias, $subject, $fromMail,$fromAlias) {
      $message->to($to, $toAlias)->subject($subject);
      $message->from($fromMail,$fromAlias);
    });
    return response()->json("Success!",200);
  }
}
