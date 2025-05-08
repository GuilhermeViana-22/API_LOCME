<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class Log extends Model
{
    protected $table = 'logs';
    protected $fillable = [
        'client_id',
        'client_ip',
        'name',
        'autenticado',
        'rota',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'client_id'); // Define a relação inversa
    }

    public static function error($exception, $client_id, $ip, $name, $autenticado, $rota)
    {
        try {
            DB::table('failed_jobs')->insert([
                'uuid' => Uuid::uuid4()->toString(),
                'connection' => config('database.default'),
                'queue' => 'default',
                'payload' => json_encode([
                    'client_id' => $client_id,
                    'client_ip' => $ip,
                    'name' => null,
                    'autenticado' => $autenticado,
                    'rota' => null
                ]),
                'exception' => $exception,
                'failed_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Lide com possíveis erros ao salvar na tabela failed_jobs
            // Aqui você pode, por exemplo, registrar em um arquivo de log
        }
    }
}
