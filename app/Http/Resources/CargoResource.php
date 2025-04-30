
<?php


use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

class CargoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nome_cargo' => $this->nome_cargo,
            'nivel_hierarquico' => $this->nivel_hierarquico,
            'departamento' => $this->departamento,
            'descricao' => $this->descricao,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}