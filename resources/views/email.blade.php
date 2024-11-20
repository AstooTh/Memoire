<!DOCTYPE html>
<html>
<head>
    <title>Réinitialisation du mot de passe</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Styles en ligne pour la compatibilité des e-mails */
        .container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
        }
        .btn {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Bonjour {{ $user->nom }},</h1>
        <p>Votre matricule est : {{ $matricule }}</p>
        <p class="text-center">Nous avons créé votre compte avec succès. Cliquez sur le bouton ci-dessous pour réinitialiser votre mot de passe :</p>
        <a href="{{ $changeMdp }}" class="btn btn-primary btn-lg btn-block">Réinitialiser le mot de passe</a>
        <p class="text-center">Merci !</p>
    </div>
</body>
</html>