<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegisterValidationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public function register(UserRegisterValidationRequest $request)
    {
        // Validar os dados do request
        $validatedData = $request->validated();

        // Continuar com o registro do usuário se a validação passar
        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]);

            $token = $user->createToken('LaravelAuthApp')->accessToken;

            DB::commit();

            return response()->json(['token' => $token], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'Erro ao registrar o usuário. Por favor, tente novamente.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('LaravelAuthApp')->accessToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }


    public function me()
    {
        $user = Auth::user(); // Obtém o usuário autenticado atualmente

        if ($user) {
            return response()->json($user, Response::HTTP_OK);
        } else {
            return response()->json(['error' => 'Usuário não autenticado.'], Response::HTTP_UNAUTHORIZED);
        }
    }
    public function teste()
    {
        return "deu certo";
    }
}
