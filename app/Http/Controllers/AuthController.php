<?php

namespace App\Http\Controllers;

use App\Http\Requests\MeRequest;
use App\Http\Requests\UserRegisterValidationRequest;
use App\Mail\SendMail;
use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /***
     * @param UserRegisterValidationRequest $request
     * @return mixed
     */
    public function register(UserRegisterValidationRequest $request)
    {
        // Validar os dados do request
        $validatedData = $request->validated();
        DB::beginTransaction();

        try {
            // Criar o usuário
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]);

            // Gerar e armazenar código de verificação
            $code = Str::random(6);
            VerificationCode::create([
                'email' => $request->email,
                'code' => $code,
            ]);

            // Enviar código de verificação por email
            Mail::to($request->email)->send(new SendMail($code, $validatedData['name']));

            // Retornar resposta para que o usuário verifique o código
            return response()->json(['message' => 'Verification code sent. Please check your email.'], Response::HTTP_OK);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Erro ao registrar o usuário. Por favor, tente novamente. ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /***
     * @param Request $request
     * @return mixed
     */
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


    /***
     * @param MeRequest $request
     * @return mixed
     */
    public function me(MeRequest $request)
    {

        // Obter todos os parâmetros da requisição
        $user = User::find($request->get('id'));
        if ($user) {
            return response()->json($user, Response::HTTP_OK);
        } else {
            return response()->json(['error' => 'Usuário não encontrado.'], Response::HTTP_NOT_FOUND);
        }
    }
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'Successfully logged out'], Response::HTTP_OK);
    }


// Método para verificar o código de verificação
    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string',
        ]);

        // Buscar o código de verificação no banco de dados
        $verificationCode = VerificationCode::where('email', $request->email)
            ->where('code', $request->code)
            ->first();

        if ($verificationCode) {
            // Código válido, prosseguir para gerar o token de acesso
            DB::beginTransaction();

            try {
                // Encontrar o usuário pelo email
                $user = User::where('email', $request->email)->first();

                // Gerar o token de acesso
                $token = $user->createToken('LaravelAuthApp')->accessToken;

                // Excluir o código de verificação após usar
                $verificationCode->delete();

                DB::commit();
                return response()->json(['token' => $token], Response::HTTP_OK);

            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['error' => 'Erro ao gerar o token de acesso. Por favor, tente novamente. ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

        } else {
            // Código de verificação inválido
            return response()->json(['error' => 'Invalid verification code.'], Response::HTTP_BAD_REQUEST);
        }
    }
}
