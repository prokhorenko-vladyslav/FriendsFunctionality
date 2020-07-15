<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\CRM\User;
use App\Http\Requests\Auth\{ChangePassword, ForgotPassword, Login, Register};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Login $request)
    {
        try {
            if (Auth::attempt($request->validated())) {
                return responder()->success([
                    "token" => Auth::user()->createToken(config('app.name'))->accessToken
                ]);
            } else {
                throw new \Exception;
            }
        } catch (\Exception $e) {
            return responder()->error(401)->respond(401);
        }
    }

    public function register(Register $request)
    {
        try {
            $user = new User($request->validated());
            $user->password = bcrypt($user->password);
            return $user->saveOrFail() ? responder()->success()->respond(200) : responder()->error(500)->respond(500);
        } catch (\Exception $e) {
            return responder()->error(500)->respond(500);
        }
    }
}
