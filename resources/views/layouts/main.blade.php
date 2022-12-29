<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <title>Document</title>
</head>
<body>
    <section class="main">
        <div class="side-bar">
                <div class="link-listing">
                    <img src="https://img.freepik.com/free-vector/expert-checking-business-leader-order_74855-10624.jpg?w=996&t=st=1671686784~exp=1671687384~hmac=323217c9252084123cd7a7a5d056a87e67b9958d7e6a8d1b1554c7e4a7ce2dcc" alt="logo">
                </div>

            <ul class="listing">
                @customer

                    <a href="{{ route('customers.index') }}" id="{{ (request()->is('work-orders')) ? 'hovereffect' : '' }}"><li><i class="bi bi-list"></i> Work Orders</li></a>
                    <a href="{{ route('work-orders.create') }}" id="{{ (request()->is('work-orders/create')) ? 'hovereffect' : '' }}"><li><i class="bi bi-pencil-square"></i> Create Work Order</li></a>

                @else
                    @if (Auth::user()->role_id != 3)
                        <a href="{{ route('users') }}" id="{{ (request()->is('users*')) ? 'hovereffect' : '' }}"><li><i class="bi bi-people"></i> Users</li></a>
                    @endif

                    @if (Auth::user()->role_id == 1)

                        <a href="{{ route('work-orders.index') }}" id="{{ (request()->is('work-orders*')) ? 'hovereffect' : '' }}"><li><i class="bi bi-list"></i> Work Orders</li></a>

                    @else

                        <a href="{{ route('assigned.index') }}" id="{{ (request()->is('assigned-work*')) ? 'hovereffect' : '' }}"><li><i class="bi bi-list"></i> Work Assigned</li></a>

                    @endif

                @endcustomer
            </ul>
        </div>
        
        <div>
            @yield('content')
        </div>
    </section>
</body>
</html>
{{-- <a href="{{ route('users.attendence', Auth::user()) }}" id="{{ Request::url() == route('users.attendence', Auth::user()) ? 'hovereffect' : '' }}"><li><i class="bi bi-card-list"></i> Attendance Record</li></a> --}}