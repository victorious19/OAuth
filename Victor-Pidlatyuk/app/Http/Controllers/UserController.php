<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;

class UserController extends Controller
{
    function get_user_by_id($id) {
        $user = User::find($id);
        if(empty($user)) return response('User not found!', 404);
        return $user;
    }
    function change(Request $request, $user_id) {
        $request->validate([
            'login'=>'string|unique:users,login|max:30',
            'password'=>'confirmed|min:8',
            'full_name' => 'string|max:255',
            'email'=>'string|unique:users,email|max:255',
            'avatar' => 'image'
        ]);
        $user = User::find($user_id);
        if (empty($user)) return response('User not found', 404);
        $avatar = $user->avatar;
        if (isset($request->password)) $request->password = bcrypt($request->password);
        $user->update($request->all());
        if ($user->id != auth()->user()->id) return response('Access denied', 403);
        if (isset($request->avatar)) {
            $ext = $request->avatar->extension();
            if (isset($request->login))  $img_name = $request->login;
            else $img_name = $user->login;
            $img_name .= Str::random(3).($ext?'.'.$ext:'');
            $path = public_path('/img/products/'.$avatar);
            if (file_exists($path) && $user->avatar != 'default.png') unlink($path);
            $request->avatar->move(public_path('/img/users'), $img_name);
            $request->avatar = $img_name;
            $user->update(['avatar' => $img_name]);
        }
        return $user;
    }
}
