<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

</head>

<body class="antialiased">
    @foreach($users as $user)
        <h1>{{ $user->name }} {{ $user->surname }}</h1>
        <h3>{{ $user->email }}</h3>
        <h5>{{ $user->created_at }}</h5>
        <h2>Comentaris</h2>
        @foreach($user->comentaris as $comentari)
            <h5>{{ $comentari->contingut }}</h5>
        @endforeach
        <hr>
    @endforeach
</body>

</html>
