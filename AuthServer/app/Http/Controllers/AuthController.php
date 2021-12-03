<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;
use App\Models\PasswordReset;
use DateTime;

class AuthController extends Controller
{
    function oauth(Request $request) {
        $request->session()->put('redirect_uri', $request->get('redirect_uri'));
        $request->session()->get('auth_code');
        return redirect('/api/main_view');
    }
    function answer(Request $request) {
        if ($request->get('accept') == true) {

        }
        return redirect($request->session()->get('redirect_uri').'?accept=false');
    }
    function view() {
        return redirect(env('CLIENT_URI'));
    }
    function register(Request $request) {
        return $request;
        $this->validate($request, [
            'login' => 'required|string|unique:users,login',
            'password'=>'required|confirmed|min:8',
            'full_name'=>'required|string|max:255',
            'email'=>'required|string|unique:users,email|max:255',
            'google_id' => 'string',
            'avatar' => 'string'
        ]);
        $request['password'] = Hash::make($request['password']);
        $user = User::create($request->all());
        $auth_code = Str::random(40);
        $user->update(['auth_code' => $auth_code]);
        $request->session()->put('auth_code', $auth_code);

        return redirect('/');
    }
    function login(Request $request)
    {
        $this->validate($request, [
            'login' => 'required|string',
            'password'=>'required|string|min:8'
        ]);
        $auth = $request->only(['login', 'password']);
        if (empty($auth['login']) or empty($auth['password'])) {
            return response(['message' => "Empty fields"], 422);
        }
        $user = User::where('login', $auth['login'])->first();
        if (empty($user)) $user = User::where('email', $auth['login'])->first();
        if (empty($user) or !Hash::check($auth['password'], $user->password)) {
            return response(['message' => "Bad login or password"], 400);
        }

        $auth_code = Str::random(40);
        $user->update(['auth_code' => $auth_code]);
        $request->session()->put('auth_code', $auth_code);

        return redirect('/');
    }

    function passwordReset(Request $request) {
        $this->validate($request, ['email'=>'required|string']);
        $email = $request->input('email');
        $user = User::where('email', $email)->first();
        if (empty($user)) return response(['message' => 'Email does not exist!'], 404);
        $verification_code = Str::random(6);
        $data = [
            'title' => 'Your password',
            'hi' => 'Hello '.$user->login."!",
            'content' => 'Verification code: '.$verification_code
        ];
        Mail::to($email)->send(new SendMail($data));
        $password_resets = PasswordReset::where('email', $email)->get();
        if ($password_resets) {
            foreach ($password_resets as $password_reset) {
                $password_reset->delete();
            }
        }
        PasswordReset::create([
            'email' => $email,
            'verification_code' => $verification_code
        ]);

        return 'success';
    }
    function passwordChange(Request $request) {
        $this->validate($request, [
            'email' => 'required|exists:users,email',
            'verification_code' => 'required|size:6',
            'password' => 'required|confirmed|min:8',
        ]);
        $password_reset = PasswordReset::where('email', $request->email)->first();
        if ($password_reset->verification_code != $request->verification_code)
            return response(['errors'=>['verification_code'=>["Bad verification code"]]], 400);
        $dif = (new DateTime("now"))->getTimestamp() - (new DateTime($password_reset->created_at))->getTimestamp();
        if ($dif > 120)
            return response(['message'=>"Verification code is bad or outdated"], 400);
        $user = User::where('email', $request->email)->first();
        $user->update(['password' => bcrypt($request->password)]);
        return $user;
    }
    function socialLogin($provider) {
            try {
                $user = Socialite::driver($provider)->stateless()->user();
            } catch(Exception $exception) {
                redirect('/login?message='.$exception->getMessage());
            }
            $isUser = User::where('email', $user->email)->first();
            if (isset($isUser)) {
                if (!$isUser->google_id) $isUser->google_id = $user->id;
                $token = $isUser->createToken('good_shop')->plainTextToken;
                return redirect('/profile?token='.$token.'&id='.$isUser->id);
            }
            else {
                $login = $user->nickname;
                if (empty($login) || User::where('login', $login)->first()) {
                    $id = User::max('id');
                    $id = $id?$id+1:1;
                    while(User::where('login', 'user'.$id)->first()) $id += 1;
                    $user->nickname = 'user'.$id;
                }
                $password = Str::random(10);

                $url = $user->avatar_original;
                $ext = pathinfo($url, PATHINFO_EXTENSION);
                $img_name = $user->nickname.($ext?'.'.$ext:'');
                $path = public_path('/img/users/'.$img_name);

                $user = new Request([
                    'login' => $user->nickname,
                    'full_name' => $user->name,
                    'email' => $user->email,
                    'password' => $password,
                    'password_confirmation' => $password,
                    'google_id' => $user->id,
                    'avatar' => $img_name
                ]);
                try {
                    $response = $this->register($user);
                    file_put_contents($path, file_get_contents($url));
                }
                catch (Exception $exception) {
                    User::where('login', $user->login)->delete();
                    if(file_exists($path)) unlink($path);
                    return redirect('/login?message=Register error');
                }
                $data = [
                    'title' => 'Your password',
                    'hi' => 'Hello '.$user->login."!",
                    'content' => 'Your password: '.$password
                ];
                Mail::to($user->email)->send(new SendMail($data));
                return redirect('/profile?token='.$response->original['token'].'&id='.$response->original['user']->id);
            }
    }
}
