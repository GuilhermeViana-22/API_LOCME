<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteAccountRequest;
use App\Http\Requests\UserRegisterValidationRequest;
use App\Http\Requests\MailVerifyRequest;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\ResetRequest;
use App\Http\Requests\LoginRequest;
use App\Models\PersonalAcessToken;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Models\VerificationCode;
use App\Http\Requests\MeRequest;
use Illuminate\Support\Carbon;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Mail\ResetPassword;
use App\Mail\SendMail;
use App\Models\User;
use App\Models\Log;

class AuthController extends Controller
{
    /***
     * método para registrar usuário
     * @param UserRegisterValidationRequest $request
     * @return JsonResponse
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
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request)
    {
        // Captura as credenciais e o IP do cliente
        $credentials = $this->getCredentials($request);
        $ip = $request->ip();

        // Inicia a transação
        DB::beginTransaction();

        try {
            // Verifica as credenciais
            $user = $this->authenticate($credentials);

            if (!$user) {
                DB::rollBack();
                return response()->json(['error' => 'Credenciais inválidas'], 401);
            }

            // Cria o token para o usuário autenticado
            try {
                $tokenStr = $this->createToken($user);
            } catch (\Throwable $e) {
                DB::rollBack();
                return response()->json(['error' => 'Erro ao criar o token: ' . $e->getMessage()], 500);
            }

            // Salva o token na tabela `personal_access_tokens`
            try {
                DB::table('personal_access_tokens')->insert([
                    'tokenable_type' => 'App\Models\User',
                    'tokenable_id' => $user->id,
                    'name' => 'API Token',
                    'token' => $tokenStr,  // Armazena o hash do token
                    'abilities' => json_encode(['*']),
                    'created_at' => now(),
                    'updated_at' => now(),
                    'last_used_at' => null
                ]);
            } catch (\Throwable $e) {
                DB::rollBack();
                return response()->json(['error' => 'Erro ao salvar o token: ' . $e->getMessage()], 500);
            }

            // Registra o acesso do usuário
            try {
                $this->logAccess($user->id, $ip);
            } catch (\Throwable $e) {
                DB::rollBack();
                return response()->json(['error' => 'Erro ao registrar o acesso: ' . $e->getMessage()], 500);
            }

            // Confirma a transação
            DB::commit();

            // Retorna o token e o user_id
            return response()->json([
                'token' => $tokenStr,
                'user_id' => $user->id
            ], 200);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['error' => 'Erro: ' . $e->getMessage()], 500);
        }
    }


    /***
     * @param MeRequest $request
     * @return JsonResponse
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
     * método para realizar reset request
     * @param ResetRequest $request
     * @return JsonResponse
     */
    public function mailVerify(MailVerifyRequest $request)
    {
        $user = User::where('email', 'like', '%'.$request->get('email').'%')->first();
        if(!$user){
            return response()->json(['Error' => 'Não foi localizado este e-mail nos nossos registros , por gentileza verifique o emial digitado e tente novamebnte.']);
        }

        try {
            DB::beginTransaction();
            // Gerar e armazenar código de verificação
            $code = Str::random(6);

            VerificationCode::create([
                'user_id' => $user->id,
                'email' => $user->email,
                'code' => $code,
            ]);

            // Enviar código de verificação por email
            $this->sendResetPassword($user->email, $code, $user->name);

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
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'Successfully logged out'], Response::HTTP_OK);
    }


    /***
     * @param $client_id
     * @param $ip // ip do ususario
     * @param $autenticado // seta para true a autentificação
     * @return JsonResponse
     */
    public function log($client_id, $ip, $autenticado)
    {
        DB::beginTransaction();

        try {
            $log = new Log();
            $log->client_id = $client_id;
            $log->client_ip = $ip;
            $log->autenticado = $autenticado;
            $log->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage(), $client_id, $ip, $autenticado);
            return response()->json(['error' => 'Não foi possivel registrar logs. ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /***
     * método para realizar o update de senha
     * @param ResetRequest $request
     * @return JsonResponse
     */
    public function reset(ResetRequest $request)
    {
        // Extrair o USER_ID do cabeçalho de autorização
        $userIdHeader = $request->header('user_id');
        if (!$userIdHeader) {
            return response()->json(['error' => 'header não foi fornecido corretamente.'], 401);
        }

        // Validar os dados da requisição
        $dados = $request->validated();

        // Verificar se o usuário existe
        $user = User::find($userIdHeader);
        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado.'], 404);
        }

        // Verificar o código de verificação
        $verification = VerificationCode::where('user_id', $userIdHeader)->first();

        if (!$verification) {
            return response()->json(['error' => 'Código de verificação inválido ou expirado.'], 400);
        }

        // Atualizar a senha do usuário
        $user->password = Hash::make($dados['password']);
        $user->save();

        // Invalida o código de verificação após o uso
        $verification->delete();

        return response()->json(['message' => 'Senha alterada com sucesso.'], 200);
    }

    public function delete(DeleteAccountRequest $request)
    {
        $id = $request->get('id');

        // Encontre o usuário pelo ID
        $user = User::find($id);

        if (!$user) {
            // Retorna erro 404 se o usuário não for encontrado
            return response()->json([
                'message' => 'Usuário não encontrado.',
            ], 404);
        }

        // Verifica se o usuário já está inativo
        if ($user->active == User::USUARIO_INATIVO && $user->situacao == User::SITUACAO_INATIVO) {
            return response()->json([
                'message' => 'O usuário já está inativo.',
            ], 400);
        }

        // Atualize as colunas para marcar o usuário como inativo
        $user->active = User::USUARIO_INATIVO;
        $user->situacao = User::SITUACAO_INATIVO;

        try {
            $user->save(); // Salva as alterações
            $user->delete(); // Executa o soft delete

            return response()->json(['message' => 'O usuário foi desativado com sucesso. Caso deseje reativar a conta, entre em contato com o suporte.'], 200);

        } catch (Exception $e) {
            // Retorna erro 500 para exceções genéricas
            return response()->json(['message' => 'Não foi possível deletar a conta do usuário.','error' => $e->getMessage(), ], 500);
        }
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

    /**
     * @param $email
     * @param $code
     * @param $name
     * @return void
     */
    private function sendResetPassword($email, $code, $name)
    {
        Mail::to($email)->send(new ResetPassword($code, $name));
    }

    /***
     * método para salvar os dados de log
     * @param $token
     * @param $chamada
     * @return JsonResponse
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

    /**
     * Valida se o token é válido para o usuário fornecido.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validar(Request $request)
    {
        dd('chegamos ate aqui');
//        $userId = $request->get('user_id');
//
//        // Buscar o token na tabela personal_access_tokens
//        $tokenRecord = DB::table('personal_access_tokens')
//            ->where('tokenable_id', $userId)
//            ->first();
//
//        if ($tokenRecord) {
//            return response()->json([
//                'authorized' => true,
//                'message' => 'Token válido.'
//            ], 200);
//        }

        return response()->json(['message' => 'Senha alterada com sucesso.'], 200);
    }


    private function getCredentials(Request $request)
    {
        return $request->only('email', 'password');
    }

    private function authenticate(array $credentials)
    {
        if (Auth::attempt($credentials)) {
            return Auth::user();
        }
        return null;
    }

    private function createToken($user)
    {
        $token = $user->createToken('LaravelAuthApp')->accessToken;
        $this->PersonalAcessToken($token, 'login');
        return $token;
    }

    private function logAccess($userId, $ip)
    {
        $this->Log($userId, $ip, true);
    }
}
