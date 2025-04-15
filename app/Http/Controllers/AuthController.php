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


/**
 * @OA\Info(
 *     title="Sistema de Gerenciamento de Usuários",
 *     version="1.0.0",
 *     description="API para gerenciamento de autenticação de usuários",
 *     @OA\Contact(email="suporte@sistema.com")
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Registra um novo usuário",
     *     tags={"Usuário"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string", example="João Silva"),
     *             @OA\Property(property="email", type="string", format="email", example="joao@exemplo.com"),
     *             @OA\Property(property="password", type="string", format="password", example="Senha123@",
     *                 description="Deve conter pelo menos 8 caracteres, 1 letra maiúscula e 1 número")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Registro bem-sucedido",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Verificação do código enviada. Por gentileza cheque seu e-mail.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Senha inválida",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="A senha não atende aos critérios mínimos")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Erro ao registrar o usuário")
     *         )
     *     )
     * )
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
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Erro ao registrar o usuário. Por favor, tente novamente. ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Login de um usuário",
     *     tags={"Usuário"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="joao@exemplo.com"),
     *             @OA\Property(property="password", type="string", format="password", example="Senha123@")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login bem-sucedido",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Login realizado com sucesso!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Credenciais inválidas",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Credenciais inválidas.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Erro ao realizar o login.")
     *         )
     *     )
     * )
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


    /**
     * @OA\Get(
     *     path="/api/me",
     *     summary="Retorna dados do usuário autenticado",
     *     tags={"Usuário"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             ref="#/components/schemas/User"
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuário não encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Usuário não encontrado.")
     *         )
     *     )
     * )
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
    /**
     * @OA\Post(
     *     path="/api/mail-verify",
     *     summary="Envia código de verificação por e-mail para redefinição de senha",
     *     tags={"Usuário"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", format="email", example="usuario@exemplo.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Código enviado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Verificação do código enviada. Por gentileza cheque seu e-mail.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="E-mail não encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="Error", type="string", example="Não foi localizado este e-mail nos nossos registros")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Erro ao enviar e-mail de verificação")
     *         )
     *     )
     * )
     */
    public function mailVerify(MailVerifyRequest $request)
    {
        $user = User::where('email', 'like', '%' . $request->get('email') . '%')->first();
        if (!$user) {
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
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Erro ao registrar o usuário. Por favor, tente novamente. ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Encerra a sessão do usuário",
     *     tags={"Usuário"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logout bem-sucedido",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Successfully logged out")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     )
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'Successfully logged out'], Response::HTTP_OK);
    }




    /**
     * @OA\Post(
     *     path="/api/log-access",
     *     summary="Registra um log de acesso",
     *     tags={"Logs"},
     *     @OA\Parameter(
     *         name="client_id",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="ip",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="autenticado",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Log registrado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Não foi possivel registrar logs")
     *         )
     *     )
     * )
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



    /**
     * @OA\Post(
     *     path="/api/reset-password",
     *     summary="Redefine a senha do usuário",
     *     tags={"Usuário"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"password"},
     *             @OA\Property(property="password", type="string", format="password", example="NovaSenha123")
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="user_id",
     *         in="header",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Senha redefinida com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Senha alterada com sucesso.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Código inválido ou expirado",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Código de verificação inválido ou expirado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Header ausente",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="header não foi fornecido corretamente.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuário não encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Usuário não encontrado.")
     *         )
     *     )
     * )
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

    /**
     * @OA\Delete(
     *     path="/api/delete-account",
     *     summary="Desativa a conta do usuário",
     *     tags={"Usuário"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Conta desativada com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="O usuário foi desativado com sucesso. Caso deseje reativar a conta, entre em contato com o suporte."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Conta já inativa",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="O usuário já está inativo.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuário não encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Usuário não encontrado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Não foi possível deletar a conta do usuário")
     *         )
     *     )
     * )
     */
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
            return response()->json(['message' => 'Não foi possível deletar a conta do usuário.', 'error' => $e->getMessage(),], 500);
        }
    }

     /**
     * @OA\Post(
     *     path="/api/validate-token",
     *     summary="Valida um token de acesso",
     *     tags={"Usuário"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id", "token"},
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="token", type="string", example="token_jwt_aqui")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Token válido",
     *         @OA\JsonContent(
     *             @OA\Property(property="authorized", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Token válido.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Token inválido",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Token inválido")
     *         )
     *     )
     * )
     */
    public function validar(Request $request) {}

    public function teste(Request $request)
    {
        $userId = $request->get('user_id');
        $token = $request->get('token'); // Certifique-se de que o token está correto

        // Buscar o token na tabela personal_access_tokens
        $tokenRecord = DB::table('personal_access_tokens')
            ->where('tokenable_id', $userId)
            ->where('token', $token) // Comparação exata é segura
            ->first();

        if ($tokenRecord) {
            return response()->json([
                'authorized' => true,
                'message' => 'Token válido.'
            ], 200);
        }

        return response()->json(['message' => 'Token inválido ou senha alterada com sucesso.'], 200);
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
