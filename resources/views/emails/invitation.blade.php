<!DOCTYPE html>
<html>
<head>
    <title>Welcome Email</title>
</head>
<body>
    <p>Vous avez été invité(e) à rejoindre la colocation 
<strong>{{ $invitation->colocation->name }}</strong>.
</p>

<p>
Cliquez sur ce lien pour accepter :
</p>

<p>
<a href="{{ route('colocation.join', $invitation->token) }}">
    {{ route('colocation.join', $invitation->token) }}
</a>
</p>
</body>
</html>