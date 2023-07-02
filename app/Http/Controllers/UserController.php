<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Dictionary;
use App\Models\Word;
use App\Models\History;

class UserController extends Controller
{
    function redirect($provider)
    {
        return Socialite::driver($provider)->redirect()->getTargetUrl();
    }

    function Callback($provider)
    {
        if ($_SERVER["REMOTE_ADDR"]) {
            $ip = $_SERVER["REMOTE_ADDR"];
        } else {
            $ip = "Unknown";
        }

        $userSocial  =   Socialite::driver($provider)->stateless()->user();
        $userAcount  =   User::where(['email' => $userSocial->getEmail()])->first();

        if ($userAcount) {
            $user = User::find($userAcount->getAttribute('id'));
        } else {
            $user = User::create([
                'name' => $userSocial->getName(),
                'email' => $userSocial->getEmail(),
                'password' => Hash::make("Password")
            ]);
        }

        Auth::login($user, true);

        History::create([
            'user_id' => Auth::id(),
            'ip_address' => $ip
        ]);

        return [
            'user'  => $user,
            'access_token' => $user->createToken('user')->plainTextToken,
        ];
    }

    function logout()
    {
        Auth::logout();
        return response()->json("", 200);
    }

    function check()
    {
        if (Auth::check()) {
            $user = Auth::user();
            return response()->json([
                'user'  => $user,
                'access_token' => "fja0r9032riosfsdf",
            ]);
        } else {
            return response()->json("", 500);
        }
    }

    function change_name(Request $request)
    {
        $request->validate([
            'name' => 'required|max:30',
        ]);

        $id = Auth::id();
        $name = $request->name;

        $user = User::where("id", $id)->first();
        $user->name = $name;
        $user->save();

        return [
            'user'  => $user,
            'access_token' => "fja0r9032riosfsdf",
        ];
    }

    function delete(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'name' => 'required|max:30',
        ]);

        $id = Auth::id();
        $email = $request->email;
        $name = $request->name;

        $user = User::where("id", $id)->where("email", $email)->where("name", $name)->first();

        if (!is_null($user)) {
            $user->delete();
            Dictionary::where("user_id", $id)->delete();
            Word::where("user_id", $id)->delete();
            Auth::logout();
            return response()->json("", 200);
        } else {
            return response()->json("", 500);
        }
    }

    function history()
    {
        $user_id = Auth::id();

        $history = History::where("user_id", $user_id)->select("ip_address", "created_at")->get();

        return response()->json($history);
    }

    function block()
    {
        $password = "Password";
        Auth::logoutOtherDevices($password);

        return response()->json("", 200);
    }
}
