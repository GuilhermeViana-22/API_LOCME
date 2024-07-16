@extends('emails.default')
@section('conteudo')
    <div class="header">
        Código de verificação
    </div>
    <div class="content">
        <p>Prezado(a) {{$name}},</p>
        <p>Obrigado por se registrar. Por favor, use o seguinte código para verificar seu endereço de e-mail:</p>
        <p class="code">{{ $code }}</p>
        <p>Se você não solicitou este e-mail, ignore-o.</p>
    </div>
@endsection


