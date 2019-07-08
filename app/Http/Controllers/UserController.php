<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use JWTAuth;
use App\User;
use JWTAuthException;
use Auth;
use Socialite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Validator;

class UserController extends Controller
{
      /**
     * @var User
     */
    private $user;
    /**
     * UserController constructor.
     * @param User $user
     */
    public function __construct(User $user){
        $this->user = $user;
    }
    /**
     * registers a new user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'username'=>'required|string|max:255',
            'email'=>'required|string|max:255|email|unique:users',
            'password'=>'required|string|min:6',
        ]);
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()],400);
        }

        $user =User::where('email',$request->get('email'))->first();
        if($user){
            return response()->json(['status'=>false,'message'=>'Email Already Exists'],400);
        }

        $user = $this->user->create([
            'username' => $request->get('username'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
        ]);
        return response()->json(['status'=>true,'message'=>'User created successfully','data'=>$user],200);
    }


    /**
     * logs in with a user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function login(Request $request){
        
        $credentials = $request->only('email', 'password');
        $token = null;
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['status' => false, 'message' => "Invalid Email or Password"],400);
            }
        } catch (JWTAuthException $e) {
            return response()->json(['message'=>"failed_to_create_token"], 500);
        }
        return response()->json(compact('token'));
    }
    /**
     * logouts a user
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(){
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['status' => false, 'message' => "You are now logged out"],200);
    }
    public function getUser(){
        $user= User::find(auth()->id());
        return response()->json(['status'=>true,'message'=>'user found','data'=>$user]);
    }
}
