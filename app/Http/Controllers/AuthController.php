<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Throwable;

class AuthController extends Controller
{
    use HttpResponses;

    /**
     * Аутентификация.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function login(Request $request)
    {
        try {
            $this->validate($request, [
                'email' => 'required|email',
                'password' => 'required|min:6',
            ]);

            if (!Auth::attempt($request->only(['email', 'password']))) {
                return $this->error([], 'Данные введены неверно', 401);
            }

            $user = User::where('email', $request->email)->first();

            return $this->success([
                'user_id' => $user->id,
                'token' => $user->createToken('Token: ' . $user->name)->plainTextToken,
            ]);

        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            return $this->error([], 'При аутентификации произошла ошибка.', 401);
        }
    }

    /**
     * Регистрация.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|min:2,max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return $this->success([
            'user_id' => $user->id,
            'token' => $user->createToken('Token: ' . $user->name)->plainTextToken,
        ]);
    }

    public function logout()
    {
        return response()->json('logout success');
    }
}