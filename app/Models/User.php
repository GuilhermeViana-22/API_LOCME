<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="User",
 *     title="User Model",
 *     description="Modelo de usuário do sistema",
 *     required={"name", "email", "password", "tipo_perfil_id", "perfil_id"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         format="int64",
 *         description="ID único do usuário",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         maxLength=255,
 *         description="Nome completo do usuário",
 *         example="João Silva"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         format="email",
 *         maxLength=255,
 *         description="E-mail do usuário (único)",
 *         example="joao@exemplo.com"
 *     ),
 *     @OA\Property(
 *         property="email_verified_at",
 *         type="string",
 *         format="date-time",
 *         description="Data de verificação do e-mail",
 *         nullable=true,
 *         example="2023-01-01 12:00:00"
 *     ),
 *     @OA\Property(
 *         property="password",
 *         type="string",
 *         description="Senha do usuário (criptografada)",
 *         example="hashed_password"
 *     ),
 *     @OA\Property(
 *         property="foto_perfil",
 *         type="string",
 *         maxLength=255,
 *         description="Caminho/URL da foto de perfil",
 *         nullable=true,
 *         example="users/profile_photos/abc123.jpg"
 *     ),
 *     @OA\Property(
 *         property="tipo_perfil_id",
 *         type="integer",
 *         description="ID do tipo de perfil do usuário",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="perfil_id",
 *         type="integer",
 *         description="ID do perfil do usuário",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="bio",
 *         type="string",
 *         description="Biografia do usuário",
 *         nullable=true,
 *         example="Desenvolvedor web com 5 anos de experiência"
 *     ),
 *     @OA\Property(
 *         property="remember_token",
 *         type="string",
 *         description="Token de lembrete para sessão persistente",
 *         nullable=true
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Data de criação",
 *         example="2023-01-01 12:00:00"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Data de atualização",
 *         example="2023-01-01 12:00:00"
 *     ),
 *     @OA\Property(
 *         property="deleted_at",
 *         type="string",
 *         format="date-time",
 *         description="Data de exclusão (soft delete)",
 *         nullable=true,
 *         example="2023-01-01 12:00:00"
 *     )
 * )
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'foto_perfil',
        'tipo_perfil_id',
        // 'perfil_id', ← REMOVA ou comente
        'bio',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Relacionamento com o tipo de perfil
     */
    public function tipoPerfil()
    {
        return $this->belongsTo(TipoPerfil::class, 'tipo_perfil_id');
    }

    /**
     * Relacionamento polimórfico com o perfil baseado no tipo_perfil_id
     */
    public function perfil()
    {
        switch ($this->tipo_perfil_id) {
            case TipoPerfil::TIPO_REPRESENTANTE:
                return $this->belongsTo(Representante::class, 'perfil_id');

            case TipoPerfil::TIPO_AGENTE_VIAGEM:
                return $this->belongsTo(AgenteViagem::class, 'perfil_id');

            case TipoPerfil::TIPO_AGENCIA_VIAGEM:
                return $this->belongsTo(AgenciaViagem::class, 'perfil_id');

            case TipoPerfil::TIPO_GUIA_TURISMO:
                return $this->belongsTo(GuiaTurismo::class, 'perfil_id');

            case TipoPerfil::TIPO_EMPRESA_ENTIDADE:
                return $this->belongsTo(Empresa::class, 'perfil_id');

            default:
                return null;
        }
    }

    // Método auxiliar para acessar o perfil diretamente
    public function getPerfilAttribute()
    {
        return $this->perfil()->first();
    }
}
