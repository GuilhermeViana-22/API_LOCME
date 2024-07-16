<!DOCTYPE html>
<html>
<head>
    <title>Verification Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            color: #333333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            text-align: center;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #7159c1;
            color: #ffffff;
            padding: 10px 0;
            font-size: 24px;
            font-weight: bold;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
        }
        .content {
            padding: 20px;
        }
        .code {
            font-size: 24px;
            font-weight: bold;
            color: #7159c1;
            margin-bottom: 20px;
        }
        .footer {
            background-color: #f5f5f5;
            color: #666666;
            padding: 10px;
            font-size: 12px;
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 5px;
        }
        .footer a {
            color: #7159c1;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="content">
        @yield('conteudo')
    </div>
    <div class="footer">
        <p>Ao utilizar nossos serviços, você concorda com nossa  <a href="#">Política de Privacidade</a> e  <a href="#">Termos de Serviço.</a>.</p>
        <p>&copy; Todos os direitos reservados.</p>
    </div>
</div>
</body>
</html>
