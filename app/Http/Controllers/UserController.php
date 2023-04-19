<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\HasApiTokens;
class UserController extends Controller
{
 //
 function register(Request $request)
 {
 $validator = Validator::make($request->all(), [
 'name' => 'required',
 'email' => 'required|email',
 'password' => 'required'
 ]);
 if($validator->fails()){
 $error = 400;
 $message = "Digite datos requeridos!";
 }
 else{
 $user = new User();
 $user->name = $request->name;
 $user->email = $request->email;
 $user->password = Hash::make($request->password);
 $user->save();
 $error = 200;
 $message = "Usuario Creado!";
 }
 return response()->json([
 'status error' => $error,
 'message' => $message
 ]);
 }
 function index(Request $request)
 {
 $user = User::where('email', $request->email)->first();
 // print_r($data);
 if (!$user || !Hash::check($request->password, $user->password)) {
 $error = 404;
 $message = "Las credenciales no corresponden con las
registradas!";
 }
 else{
 $token = $user->createToken('my-app-token')->plainTextToken;
 $error = 201;
 $message = ['user' => $user,'token' => $token];
 }
 return response()->json([
 'status error' => $error,
 'message' => $message
 ]);
 }
 function logout(Request $request)
 {
 $request->user()->currentAccessToken()->delete();
 return response()->json([
 'status error' => 200,
 'message' => 'Token borrado!'
 ]);
 }
}