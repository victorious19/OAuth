<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OAuthController extends Controller
{
    public function redirect()
    {
        return redirect(env('OAUTH_SERVER_URI') . '/api/oauth/authorize?redirect_uri='.env('REDIRECT_URI'));
    }

    public function callback(Request $request)
    {
        $auth_code = $request->get('auth_code');
        if(!$auth_code) return redirect('/');

        $response = Http::post(env('OAUTH_SERVER_URI') . '/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => env('CLIENT_ID'),
            'client_secret' => env('CLIENT_SECRET'),
            'redirect_uri' => env('REDIRECT_URI'),
            'code' => $auth_code
        ])->json();

        $request->user()->token()->delete();

        $request->user()->token()->create([
            'access_token' => $response['access_token'],
            'expires_in' => $response['expires_in'],
            'refresh_token' => $response['refresh_token']
        ]);

        return redirect('/');
    }

    public function refresh(Request $request)
    {
        $response = Http::post(env('OAUTH_SERVER_URI') . '/oauth/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $request->user()->token->refresh_token,
            'client_id' => env('CLIENT_ID'),
            'client_secret' => env('CLIENT_SECRET'),
            'redirect_uri' => env('REDIRECT_URI')
        ]);

        if ($response->status() !== 200) {
            $request->user()->token()->delete();

            return redirect('/home')
                ->withStatus('Authorization failed from OAuth server.');
        }

        $response = $response->json();
        $request->user()->token()->update([
            'access_token' => $response['access_token'],
            'expires_in' => $response['expires_in'],
            'refresh_token' => $response['refresh_token']
        ]);

        return redirect('/');
    }
}
