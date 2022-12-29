@extends('layouts.main')

@section('content')
    <x-nav />

    <div class="user-ask">
        <a href="{{ route('users.create') }}" class="{{ (request()->has('type'))? '' : 'active' }}">Create Manager</a>
        <a href="{{ route('users.create') }}?type=employee" class="{{ (request('type') == 'employee')? 'active' : '' }}">Create Employee</a>
    </div>

    <form action="{{ route('users.store') }}" method="post" class="my-form">
        @csrf

        <h2>Create User</h2>
        
        <div class="mb-3">
            <label for="first_name" class="form-label">First Name</label>
            <input type="text" name="first_name" class="form-control" required placeholder="First Name" value="{{ old('first_name') }}">
            <x-error name="first_name" />
        </div>

        <div class="mb-3">
            <label for="last_name" class="form-label">Last Name</label>
            <input type="text" name="last_name" class="form-control" required placeholder="Last Name" value="{{ old('last_name') }}">
            <x-error name="last_name" />
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required placeholder="Email" value="{{ old('email') }}">
            <x-error name="email" />
        </div>

        @if ($managers)
            <div class="mb-3">
                <label for="user_id" class="form-label">Manager</label>
                <select name="user_id" id="" class="form-select">
                    @foreach ($managers as $manager)
                        <option value="{{ $manager->id }}" @if (old('user_id') == $manager->id) Selected @endif>{{ $manager->name }}</option>
                    @endforeach
                </select>
                <x-error name="user_id" />
            </div>
        @endif

        <button type="submit" class="btn btn-secondary me-5">Invite User</button>
        <a href="{{ route('users') }}" class="btn btn-outline-secondary">Cancel</a>
    </form>
@endsection