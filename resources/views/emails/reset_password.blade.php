@extends('emails.default')
@section('conteudo')
    <div class="header">
        Solicitação de Mudança de Senha
    </div>
    <div class="content">
        <p>Prezado(a) {{ ucfirst($name) }},</p>
        <p>Recebemos uma solicitação para mudar a sua senha. Por favor, use o seguinte código para verificar seu endereço de e-mail e proceder com a mudança de senha:</p>
        <p class="code">{{ $code }}</p>
        <p>Se você não solicitou esta mudança, por favor, entre em contato com os administradores o mais rápido possível.</p>
    </div>
@endsection
