<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRquest;
use App\Http\Requests\UserRegisterValidationRequest;
use App\Models\Log;
use App\Models\LogAcesso;
use App\Models\PersonalAcessToken;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\MeRequest;
use App\Models\VerificationCode;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use App\Mail\SendMail;
use App\Models\User;

class AuthController extends Controller
{
    /***
     * método para registrar usuário
     * @param UserRegisterValidationRequest $request
     * @return mixed
     */
    public function register(UserRegisterValidationRequest $request)
    {
        // Validar os dados do request
        $validatedData = $request->validated();
        DB::beginTransaction();

        // Verificar se a senha atende aos critérios
        $password = $validatedData['password'];
        if (!preg_match('/^(?=.*\d)(?=.*[A-Z]).{8,}$/', $password)) {
            return response()->json(['error' => 'A senha não atende aos critérios mínimos: deve conter pelo menos um número, uma letra maiúscula e ter mais de 8 caracteres.'], Response::HTTP_BAD_REQUEST);
        }

        // Criar o usuário
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        try {
            $user->save();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Erro ao registrar o usuário. Por favor, tente novamente. ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        try {
            // Gerar e armazenar código de verificação
            $code = Str::random(6);
            VerificationCode::create([
                'user_id' => $user->id,
                'email' => $request->email,
                'code' => $code,
            ]);

            // Enviar código de verificação por email
            $this->sendVerificationEmail($user->email, $code, $user->name);

            DB::commit();

            // Retornar resposta para que o usuário verifique o código
            return response()->json(['message' => 'Verificação do código enviada. Por gentileza cheque seu e-mail.'], Response::HTTP_OK);

        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Erro ao registrar o usuário. Por favor, tente novamente. ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /***
     * @param Request $request
     * @return mixed
     */
    public function login(LoginRquest $request)
    {
        $credentials = $request->only('email', 'password');
        $ip = $request->get('ip');
        $aplication_name = $request->get('name');
        $rota = $request->get('rota');

        //inicia a transação
        DB::beginTransaction();

        try {
            if (Auth::attempt($credentials)) {
                $user = Auth::user();

                $token = $user->createToken('LaravelAuthApp')->accessToken;
                $this->PersonalAcessToken($token,  'login');
                $this->Log($user->id, $ip, $aplication_name, true,  $rota);

                DB::commit();
                return response()->json(['token' => $token], 200);
            } else {

                $this->PersonalAcessToken(null,  null);
                $this->Log(null, null, null , null,  null);

                DB::commit();
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->PersonalAcessToken(null,  null);
            $this->Log(null, null, null , null,  null);
            return response()->json(['error' => 'Internal Server Error'], 500);
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

    /***
     * @param Request $request
     * @return mixed
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'Successfully logged out'], Response::HTTP_OK);
    }

    /***
     * método para envio de email
     * @param $email
     * @param $code
     * @param $name
     * @return void
     */
    private function sendVerificationEmail($email, $code, $name)
    {
        Mail::to($email)->send(new SendMail($code, $name));
    }

    /***
     * método para salvar os dados de log
     * @param $clientId
     * @param $chamada
     * @param $autenticado
     * @param $mensagem
     * @return \Illuminate\Http\JsonResponse
     */
    private function PersonalAcessToken($token, $chamada)
    {
        try {
            PersonalAcessToken::create([
                'tokenable' => $token,
                'token' => $token,
                'name' => $chamada,
                'abilities' => '',
                'last_used_at' => Carbon::now()
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao registrar o acesso pessoal. Por favor, entre em contato com o time de desenvolvimento de sistemas. ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);

        }
    }

    /***
     * @param $client_id
     * @param $ip // ip do ususario
     * @param $name
     * @param $autenticado // seta para true a autentificação
     * @param $rota // chamada da rota
     * @return \Illuminate\Http\JsonResponse
     */
    public function log($client_id, $ip, $name, $autenticado, $rota)
    {
        DB::beginTransaction();

        try {
            $log = new Log();
            $log->client_id = $client_id;
            $log->client_ip = $ip;
            $log->name = $name;
            $log->autenticado = $autenticado;
            $log->rota = $rota;

            $log->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage(), $client_id, $ip, $name, $autenticado, $rota);
            return response()->json(['error' => 'Não foi possivel registrar logs. ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
