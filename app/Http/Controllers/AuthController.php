<?php

namespace App\Http\Controllers;
use JWTAuth;
// use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use App\User;

class AuthController extends Controller
{
    /**
     * Register a new user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $token = auth()->login($user);

        return $this->respondWithToken($token);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
                 $token =array(
                'success' => false,
                'message' => 'Invalid Email or Password',
                'status' => '401'
            );
          return response()->json(compact('token'));       
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        $user=$this->getUser($token);
        $token =array(
            'user_id'   => auth()->user()->id,
            'access_token' => $token,
            'token_type' => 'bearer',
            'success' => true,
             'message' => 'Login was  successfully',
            'status' => '200',
            'expires_in' => auth()->factory()->getTTL() * 2 // can change to 5 or 10 mins
        );
return response()->json(compact('token', 'user'));
    }

 /**
     * Get the user by token.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUser($request)
    {
        JWTAuth::setToken($request);
        $user = JWTAuth::toUser();
        return response()->json($user);
    }

    public function refresh()
    {
        try{
                $newtoken = auth()->refresh();
        }catch(\Tymon\JWTAuth\Exceptions\TokenInvalideException $e){
            return response()->json(['error' => $e->getMessage()],401);
        }
        return response()->json(['access_token' => $newtoken]);
    }



}
