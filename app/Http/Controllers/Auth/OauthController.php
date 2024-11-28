<?php

namespace App\Http\Controllers\Auth;

use App\Enum\AuthProviderEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class OauthController extends Controller
{
    public function providerAuthentication(AuthProviderEnum $provider)
    {
        return Socialite::driver($provider->value)->redirect();
    }

    public function callbackAuthentication(AuthProviderEnum $provider)
    {
        $provider = Socialite::driver($provider->value)->user();
        
        $user = User::updateOrCreate([
            'email' => $provider->email,
        ], [
            'name' => $provider->name,
            'password' => Hash::make('password')
        ]);
     
        Auth::login($user);
     
        return redirect('/dashboard');
    }
}
