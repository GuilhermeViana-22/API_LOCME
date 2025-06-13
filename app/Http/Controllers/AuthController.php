<?php

namespace App\Http\Controllers;

use App\Http\Resources\Users\UserResource;
use App\Models\TipoPerfil;
use Exception;
use App\Models\Log;
use App\Models\User;
use App\Mail\SendMail;
use App\Mail\ResetPassword;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use App\Http\Requests\MeRequest;
use App\Models\VerificationCode;
use Illuminate\Http\JsonResponse;
use App\Models\PersonalAcessToken;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ResetRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\MailVerifyRequest;
use App\Http\Requests\DeleteAccountRequest;
use App\Http\Requests\UserRegisterValidationRequest;


/**
 * @OA\Info(
 *     title="Sistema de Gerenciamento - BOB FLOW",
 *     version="1.0.0",
 *     description="API para gerenciamento BOB FLOW",
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
     *     summary="Registrar um novo usuário",
     *     tags={"Autenticação"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "password_confirmation", "tipo_perfil_id", "perfil_id"},
     *             @OA\Property(property="name", type="string", maxLength=255, example="João da Silva"),
     *             @OA\Property(property="email", type="string", format="email", maxLength=255, example="joao@empresa.com"),
     *             @OA\Property(property="password", type="string", format="password", minLength=8, example="Senha123@"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="Senha123@"),
     *             @OA\Property(property="tipo_perfil_id", type="integer", example=1),
     *             @OA\Property(property="perfil_id", type="integer", example=1),
     *             @OA\Property(property="bio", type="string", nullable=true, example="Desenvolvedor web"),
     *             @OA\Property(
     *                 property="foto_perfil",
     *                 type="string",
     *                 nullable=true,
     *                 description="URL ou caminho da foto de perfil",
     *                 example="users/profile_photos/abc123.jpg"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuário registrado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", ref="#/components/schemas/User"),
     *             @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9..."),
     *             @OA\Property(property="token_type", type="string", example="Bearer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação ou dados duplicados",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Os dados fornecidos são inválidos."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 example={
     *                     "email": {"Este email já está em uso"},
     *                     "tipo_perfil_id": {"O tipo de perfil é obrigatório"}
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno no servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Erro ao processar o registro"),
     *             @OA\Property(property="error", type="string", example="Mensagem técnica do erro (em ambiente de desenvolvimento)")
     *         )
     *     )
     * )
     */
    public function register(UserRegisterValidationRequest $request)
    {
        try {
            DB::beginTransaction();

            // Verificação adicional de email duplicado
            if (User::where('email', $request->email)->exists()) {
                return response()->json([
                    'errors' => ['email' => ['Este email já está em uso']],
                    'message' => 'Email já cadastrado'
                ], 422);
            }

            $userData = $request->validated();
            $userData['password'] = bcrypt($userData['password']);

            $user = User::create($userData);

            // Verifica se as chaves do Passport existem
            if (!file_exists(storage_path('oauth-private.key')) || !file_exists(storage_path('oauth-public.key'))) {
                throw new \Exception('Chaves do Passport não encontradas. Execute "php artisan passport:install"');
            }

            // Tenta criar o token de acesso
            $token = $user->createToken('auth_token')->accessToken;

            // Log opcional
            $this->logAccess($user->id, $request->ip(), $request->path(), true, $user->name);

            DB::commit();

            return response()->json([
                'user' => UserResource::make($user),
                'access_token' => $token,
                'token_type' => 'Bearer'
            ], 201);

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();

            $errorInfo = $e->errorInfo;

            if ($errorInfo[1] == 1054) {
                return response()->json([
                    'message' => 'Erro de configuração do banco de dados',
                    'error' => config('app.debug') ? $errorInfo[2] : 'Coluna não encontrada'
                ], 500);
            }

            if ($errorInfo[1] == 1062) {
                return response()->json([
                    'errors' => ['email' => ['Este email já está em uso']],
                    'message' => 'Email já cadastrado'
                ], 422);
            }

            return response()->json([
                'message' => 'Erro ao processar o registro',
                'error' => config('app.debug') ? $errorInfo[2] : null
            ], 500);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erro ao processar o registro',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }


    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Login de um usuário",
     *     tags={"Autenticação"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email_corporativo", "password"},
     *             @OA\Property(property="email_corporativo", type="string", format="email", example="usuario@empresa.com"),
     *             @OA\Property(property="password", type="string", format="password", example="Senha123@")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login bem-sucedido",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9..."),
     *             @OA\Property(property="user", ref="#/components/schemas/User"),
     *             @OA\Property(property="token_type", type="string", example="Bearer"),
     *             @OA\Property(property="expires_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciais inválidas",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Credenciais inválidas")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Erro ao realizar o login")
     *         )
     *     )
     * )
     */
    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (!auth()->attempt($credentials)) {
                return response()->json([
                    'message' => 'Credenciais inválidas',
                    'errors' => [
                        'email' => ['Credenciais fornecidas são inválidas.']
                    ]
                ], 401);
            }

            $user = auth()->user();
            $tokenResult = $user->createToken('Personal Access Token');

            // Registra o acesso (se necessário)
            $this->logAccess($user->id, $request->ip(), $request->path(), true, $user->name);

            return response()->json([
                'user' => new UserResource($user),
                'token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => $tokenResult->token->expires_at->toDateTimeString()
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao realizar login',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
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
        $user = Auth::user();

        if (!$user) {
            return response()->json(
                ['error' => 'Usuário não encontrado.'],
                Response::HTTP_NOT_FOUND
            );
        }

        $this->logAccess($user->id, $request->ip(), $request->path(), true, $user->name);

        return new UserResource($user);
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
        // Obtém o usuário autenticado antes de revogar o token
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Nenhum usuário autenticado'], Response::HTTP_UNAUTHORIZED);
        }

        // Revoga o token de acesso
        $user->token()->revoke();

        // Registra o log de logout
        $this->logAccess($user->id, $request->ip(), $request->path(), true, $user->name);

        return response()->json([
            'message' => 'Logout realizado com sucesso'
        ], Response::HTTP_OK);
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
    public function log($client_id, $ip, $rota, $autenticado = true, $name = null)
    {
        // Verifique se o modelo Log está sendo importado corretamente
        if (!class_exists(Log::class)) {
            \Log::error('Classe Log não encontrada');
            return response()->json(['error' => 'Configuração de modelo inválida'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // Prepare os dados garantindo que os campos correspondam à tabela
        $data = [
            'client_id' => $client_id,
            'client_ip' => $ip, // Note que no seu modelo o campo é client_ip, não ip
            'autenticado' => $autenticado,
            'name' => $name,
            'rota' => $rota,
            'created_at' => now(),
            'updated_at' => now()
        ];

        DB::beginTransaction();

        try {
            // Método alternativo que sempre funciona
            $log = new Log();
            $log->fill($data);
            $log->save();

            DB::commit();

            // Verificação adicional
            if (!$log->exists) {
                throw new \Exception('O registro não foi persistido no banco de dados');
            }

            return response()->json(['message' => 'Log registrado com sucesso'], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Falha ao registrar log', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $data
            ]);

            return response()->json([
                'error' => 'Não foi possível registrar logs',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
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
        if ($user->active == User::USUARIO_INATIVO && $user->situacao == User::USUARIO_INATIVO) {
            return response()->json([
                'message' => 'O usuário já está inativo.',
            ], 400);
        }

        // Atualize as colunas para marcar o usuário como inativo
        $user->active = User::USUARIO_INATIVO;
        $user->situacao = User::USUARIO_INATIVO;

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

    private function logAccess($userId, $ip, $rota, $autenticado = true, $name = null)
    {
        $this->Log($userId, $ip, $rota, $autenticado, $name);
    }
}
