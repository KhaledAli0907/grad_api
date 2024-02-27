<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|min:2|string',
            'email' => 'required|unique:users,email',
            'password' => 'required|string|confirmed|min:4'
        ]);


        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password'])
            ]);
        } catch (Exception $e) {
            throw $e;
        }

        $token = $user->createToken('userToken')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }


    function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required',
            'password' => 'required|string|min:4'
        ]);


        // search for user in database
        $user = User::where('email', $data['email'])->first();

        // check id exists and password
        if (!$user || Hash::check($data['password'], $user->password)) {
            return response([
                "message" => "Invalid credentials"
            ], 402);
        }

        return response([
            "message" => 'user logged in',
            "token" => $user->token
        ], 200);
    }


    function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'logged out',
        ];
    }
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     # By default we are using here auth:api middleware
    //     $this->middleware('auth:api', ['except' => ['login']]);
    // }

    // /**
    //  * Get a JWT via given credentials.
    //  *
    //  * @return \Illuminate\Http\JsonResponse
    //  */
    // public function login()
    // {
    //     $credentials = request(['email', 'password']);

    //     if (!$token = auth()->attempt($credentials)) {
    //         return response()->json(['error' => 'Unauthorized'], 401);
    //     }

    //     return $this->respondWithToken($token); # If all credentials are correct - we are going to generate a new access token and send it back on response
    // }

    // /**
    //  * Get the authenticated User.
    //  *
    //  * @return \Illuminate\Http\JsonResponse
    //  */
    // public function me()
    // {
    //     # Here we just get information about current user
    //     return response()->json(auth()->user());
    // }

    // /**
    //  * Log the user out (Invalidate the token).
    //  *
    //  * @return \Illuminate\Http\JsonResponse
    //  */
    // public function logout()
    // {
    //     auth()->logout(); # This is just logout function that will destroy access token of current user

    //     return response()->json(['message' => 'Successfully logged out']);
    // }

    // /**
    //  * Refresh a token.
    //  *
    //  * @return \Illuminate\Http\JsonResponse
    //  */
    // public function refresh()
    // {
    //     # When access token will be expired, we are going to generate a new one wit this function
    //     # and return it here in response
    //     return $this->respondWithToken(auth()->refresh());
    // }

    // /**
    //  * Get the token array structure.
    //  *
    //  * @param  string $token
    //  *
    //  * @return \Illuminate\Http\JsonResponse
    //  */
    // protected function respondWithToken($token)
    // {
    //     # This function is used to make JSON response with new
    //     # access token of current user
    //     return response()->json([
    //         'access_token' => $token,
    //         'token_type' => 'bearer',
    //         'expires_in' => auth()->factory()->getTTL() * 60
    //     ]);
    // }
}
