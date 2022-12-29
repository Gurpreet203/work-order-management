<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <title>Document</title>
</head>
<body>
    <x-flash-messages />
    
    <form action="{{ route('login') }}" method="POST" class="form">
        @csrf
        <h2 class="mb-3">Log In</h2>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" required class="form-control" name="email" value="{{ old('email') }}">
            <x-error name="email" />
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" required class="form-control" name="password">
            <x-error name="password" />
        </div>

        <button type="submit" class="btn btn-primary my-btn">Log In</button>
        <p class="mt-3">Don't have a account? <a href="{{ route('signup.create') }}">SignUp</a></p>
    </form>

</body>
</html>