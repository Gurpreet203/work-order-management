<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/style.css">
    <title>Document</title>
</head>
<body>
    <section>
        <x-flash-messages />
        
        <form action="{{ route('set-password.store', $user) }}" method="post" class="form">
            @csrf
            <h2>Create Password</h2>
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control form-control-sm" name="password" required>
            <x-error name='password' />

            <label for="confirm-password" class="form-label">Confirm Password</label>
            <input type="password" class="form-control form-control-sm" name="confirm-password" required>
            <x-error name='confirm-password' />

            <input type="hidden" value="{{$user->email}}" name="email">
            
            <div class="buttons mt-3">
                <input type="submit" value="Save" name="addPassword" class="btn btn-primary" style="width: 100%">
            </div>
        </form>
    </section>
</body>
</html>