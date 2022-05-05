<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
// use Illuminate\Http\Request;

use Socialite;
use App\User;

class LoginController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */
    public function redirectToProvider($authProvider) {
        return Socialite::driver($authProvider)->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function handleProviderCallback($authProvider) {
        if (!in_array($authProvider, ['google', 'facebook', 'twitter'])) {
            return redirect('/login');
        }

        try {
            $providerUser = Socialite::driver($authProvider)->user();
        } catch (Exception $e) {
            return redirect('/login');
        }

        if ($providerUser) {
            $email = $providerUser->getEmail();
            $user = User::where('email', $email)->first();
            if (!$user) {
                $user = new User;
                $user->name = $providerUser->getName();
                $user->email = $providerUser->getEmail();
                $user->avatar = $providerUser->getAvatar();
                $user->remember_token = session('_token');
                // $user->role = 'registered';
                $user->enabled = true;
                // $user->password = 'cixrkNesCti1iP5OorpyBl3A3t3y3xPH';
                $user->save();
            }
            auth()->login($user);
            return redirect('/home');
        }
        return redirect('/login');


        // // $user->token;

        // return response()->json($user);

        // // OAuth Two Providers
        // $token = $user->token;
        // $refreshToken = $user->refreshToken; // not always provided
        // $expiresIn = $user->expiresIn;

        // // OAuth One Providers
        // $token = $user->token;
        // $tokenSecret = $user->tokenSecret;

        // // All Providers
        // $user->getId();
        // $user->getNickname();
        // $user->getName();
        // $user->getEmail();
        // $user->getAvatar();
    }
}
