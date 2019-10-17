<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Auth;
use GuzzleHttp\Client;


class AuthController extends Controller
{
    public function users() {
        return User::all();
    }
    public function login(Request $request)
    {
        $http = new Client();

        try {
            $response = $http->post(config('services.passport.login_endpoint'), [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => config('services.passport.client_id'),
                    'client_secret' => config('services.passport.client_secret'),
                    'username' => $request->username,
                    'password' => $request->password,
                    'scope' => '',
                ],
            ]);

            $user = User::where('email', $request->username)->first();

            //return json_decode((string) $response->getBody(), true);

            return response()->json([
                'token' => json_decode((string) $response->getBody(), true),
                'user' => $user,
            ]);

        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            if ($e->getCode() == 400) {
                return response()->json('Invalid Request. Please enter a username or a password', $e->getCode());
            } else if ($e->getCode() == 401) {
                return response()->json('Your credentials are incorrect. Please try again.', $e->getCode());
                //return response()->json(['error' => 'Your credentials are incorrect. Please try again.'], $e->getCode());
            }
            return response()->json('Something went wrong on the server.', $e->getCode());
        }

    }

    public function logout(Request $request) {

    
        Auth::user()->tokens->each(function ($token, $key) {
            $token->delete();
        });

        $response = 'You have been succesfully logged out!';
        return response($response, 200);
    
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
