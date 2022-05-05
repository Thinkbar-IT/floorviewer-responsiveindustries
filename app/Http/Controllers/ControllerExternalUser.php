<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Auth\RegisterController;

use App\ExternalToken;
use App\Savedroom;
use App\User;

class ControllerExternalUser
{
    public function fromLink(Request $request)
    {
        $token = ExternalToken::where('token', $request->extt)->first();

        if (!isset($token)) {
            $user = $this->createUser($request->extt, $request->extname);
            $token = $this->createToken($request->extt, $user->id);
        }

        Auth::login($token->user, true);

        if (Savedroom::existsByUrl($request->room)) {
            return redirect('/room/url/' . $request->room);
        }

        return redirect('/');
    }

    private function createUser($user_external_token, $name = null)
    {
        if (!isset($name)) $name = $this->generateRandomString(16);
        $email = $this->generateEmail();
        $password = $this->generateRandomString(32);

        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ];

        $register_controller = new RegisterController();
        return $register_controller->create($data);
    }

    private function createToken($user_external_token, $user_id)
    {
        $token = new ExternalToken();
        $token->user_id = $user_id;
        $token->token = $user_external_token;
        $token->save();

        return $token;
    }

    private function generateEmail() {
        $email = '';
        do {
            $email = $this->generateRandomString(32);
        } while (sizeof(User::where('email', '=', $email)->get()) > 0);

        return $email;
    }

    private function generateRandomString($length = 32) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
