@extends('layouts.main')

@section('content')
    <x-nav />
    <div class="rest-body">
        <div class="nav-bottom">
            <h3>Users</h3>
            @if (Auth::id() == 1)
                <div>
                    <a href="{{ route('users.create') }}" class="btn btn-primary">Create User</a>
                </div>
            @endif
        </div>
        <x-flash-messages />

        <div class="d-flex justify-content-between ">
            <form action="{{ route('users') }}" method="GET">
                <input type="text" placeholder="Search by Name or Email" name="search" value="{{ request('search') }}" class="form-control mb-3 search">
            </form>

            <a href="{{ route('users') }}" class="btn" style="font-weight: bold">All Users</a>
        </div>

        <table class="table table-striped">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Designation</th>
                @if (Auth::id() == 1)
                    <th>Belongs To</th>
                    <th>Actions</th>
                @endif
            </tr>

            @if ($users->count() > 0)
                
                @foreach ($users as $user)
                    @if ($user->role_id == 1)
                        @continue
                    @endif

                    <tr>
                        <td> {{ $user->name }} </td>
                        <td> {{ $user->email }} </td>
                        <td> {{ $user->role->name }} </td>
                        @if (Auth::id() == 1)
                            <td> 
                                @if ($user->assigned_to )
                                    {{ $user->assigned_to->name }}
                                @else
                                    Customer*
                                @endif 
                            </td>
                            <td>  
                                @if ($user->role_id == 4)
                                    <p class="text-danger">Customer can't edit</p>
                                @else
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-outline-info">Edit</a>
                                @endif
                            </td>
                        @endif
                    </tr>
                @endforeach

            @else

                </table>
                <h2>No User Exist</h2>

            @endif
        </table>
    </div>

    {{ $users->links() }}

@endsection