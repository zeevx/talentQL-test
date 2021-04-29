<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\Passport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponser;

    public function login(Request $request)
    {
        $attr = $this->validateLogin($request);

        if ($attr->fails()){
            return $this->error($attr->messages()->first(), '401');
        }

        $user = User::whereEmail($request->email)->first();

        if (is_null($user)){
            return $this->error('User with this email does not exist', 401);
        }
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return $this->error('Invalid password, please check and retry', 401);
        }

        return $this->token($this->getPersonalAccessToken(), 'User login successful');
    }

    public function register(Request $request)
    {
        $attr = $this->validateSignup($request);

        if ($attr->fails()){
            return $this->error($attr->messages()->first(), '401');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        if ($request->role == 'photographer')
        {
            $user->assignRole('photographer');
        }

        if ($request->role == 'product-owner')
        {
            $user->assignRole('product-owner');
        }

        $credentials = $request->only('email', 'password');

        Auth::attempt($credentials);

        return $this->token($this->getPersonalAccessToken(), 'User registration successful', 201);
    }


    public function logout()
    {
        Auth::guard('api')->user()->token()->revoke();
        return $this->success('User Logged Out', 200);
    }

    public function getPersonalAccessToken()
    {
        if (request()->remember_me === 'true')
            Passport::personalAccessTokensExpireIn(now()->addDays(15));

        return Auth::user()->createToken('Personal Access Token');
    }

    public function validateLogin($request)
    {
        return Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);
    }

    public function validateSignup($request)
    {
        return Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|string',
            'password' => 'required|string|min:6',
        ]);
    }

    public function unauthorised()
    {
        return $this->error('Please login to continue', 401);
    }
}
