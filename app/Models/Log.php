<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class Log extends Model
{
    use HasFactory; // Adicionei o trait HasFactory para facilitar a criação de registros

    protected $table = 'logs';

    // Defina explicitamente se está usando timestamps
    public $timestamps = true; // ou false, dependendo da sua tabela

    protected $fillable = [
        'client_id',
        'client_ip', // Note que você usou 'ip' no método e 'client_ip' aqui
        'name',
        'autenticado',
        'rota',
        // Adicione outros campos que sua tabela possa ter
    ];

    // Adicione casts para campos específicos se necessário
    protected $casts = [
        'autenticado' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Método para registrar logs de erro
     */
    public static function logError($exception, $client_id = null, $ip = null, $name = null, $autenticado = false, $rota = null)
    {
        try {
            // Primeiro tenta salvar no log normal
            $log = self::create([
                'client_id' => $client_id,
                'client_ip' => $ip,
                'name' => $name ?? 'Erro desconhecido',
                'autenticado' => $autenticado,
                'rota' => $rota ?? 'N/A'
            ]);

            // Depois registra no failed_jobs se for uma exceção importante
            DB::table('failed_jobs')->insert([
                'uuid' => Uuid::uuid4()->toString(),
                'connection' => config('database.default'),
                'queue' => 'default',
                'payload' => json_encode([
                    'log_id' => $log->id,
                    'client_id' => $client_id,
                    'client_ip' => $ip,
                    'name' => $name,
                    'autenticado' => $autenticado,
                    'rota' => $rota
                ]),
                'exception' => (string) $exception,
                'failed_at' => now(),
            ]);

            return $log;
        } catch (\Exception $e) {
            // Fallback: Registrar em um arquivo de log se tudo falhar
            \Log::error('Falha ao registrar log e erro', [
                'original_exception' => (string) $exception,
                'log_error' => $e->getMessage(),
                'context' => [
                    'client_id' => $client_id,
                    'ip' => $ip,
                    'name' => $name
                ]
            ]);

            return false;
        }
    }

    /**
     * Método para registrar logs comuns
     */
    public static function registrar($client_id, $ip, $autenticado = true, $name, $rota)
    {
        try {
            return self::create([
                'client_id' => $client_id,
                'client_ip' => $ip,
                'name' => $name,
                'autenticado' => $autenticado,
                'rota' => $rota
            ]);
        } catch (\Exception $e) {
            \Log::error('Falha ao registrar log', [
                'error' => $e->getMessage(),
                'context' => [
                    'client_id' => $client_id,
                    'ip' => $ip,
                    'name' => $name
                ]
            ]);

            return false;
        }
    }
}
