<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invitation à rejoindre une colocation</title>
</head>
<body>
    <p>Vous avez été invité(e) à rejoindre la colocation <strong>{{ $invitation->colocation->name }}</strong>.</p>

<p>Cliquez sur ce lien pour accepter :</p>

<a href="{{ route('colocation.join', $invitation->token) }}">Rejoindre la colocation</a>
</body>
</html>