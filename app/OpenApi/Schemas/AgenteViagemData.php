<?php

namespace App\OpenApi\Schemas;

/**
 * @OA\Schema(
 *     schema="AgenteViagemData",
 *     title="Dados do Agente de Viagem",
 *     description="Esquema para dados do formulário de agente de viagem",
 *     required={"nome_completo", "cpf", "email", "whatsapp", "cidade", "uf", "vinculado_agencia", "tem_cnpj_proprio", "aceita_contato_representantes"},
 *     @OA\Property(
 *         property="nome_completo",
 *         type="string",
 *         description="Nome completo do agente de viagem",
 *         example="João da Silva"
 *     ),
 *     @OA\Property(
 *         property="cpf",
 *         type="string",
 *         description="CPF do agente de viagem",
 *         example="123.456.789-00"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         format="email",
 *         description="E-mail do agente de viagem",
 *         example="joao.silva@email.com"
 *     ),
 *     @OA\Property(
 *         property="whatsapp",
 *         type="string",
 *         description="Número de WhatsApp do agente de viagem",
 *         example="(11) 98765-4321"
 *     ),
 *     @OA\Property(
 *         property="cidade",
 *         type="string",
 *         description="Cidade do agente de viagem",
 *         example="São Paulo"
 *     ),
 *     @OA\Property(
 *         property="uf",
 *         type="string",
 *         description="Estado (UF) do agente de viagem",
 *         example="SP"
 *     ),
 *     @OA\Property(
 *         property="vinculado_agencia",
 *         type="boolean",
 *         description="Indica se o agente está vinculado a uma agência",
 *         example=true
 *     ),
 *     @OA\Property(
 *         property="cnpj_agencia_vinculada",
 *         type="string",
 *         description="CNPJ da agência vinculada (obrigatório se vinculado_agencia=true)",
 *         example="12.345.678/0001-90"
 *     ),
 *     @OA\Property(
 *         property="tem_cnpj_proprio",
 *         type="boolean",
 *         description="Indica se o agente possui CNPJ próprio",
 *         example=false
 *     ),
 *     @OA\Property(
 *         property="portfolio_redes_sociais",
 *         type="string",
 *         description="Portfólio ou redes sociais do agente",
 *         example="instagram.com/joao_agente_viagens"
 *     ),
 *     @OA\Property(
 *         property="aceita_contato_representantes",
 *         type="boolean",
 *         description="Indica se o agente aceita contato de representantes",
 *         example=true
 *     )
 * )
 */
class AgenteViagemData
{
}
