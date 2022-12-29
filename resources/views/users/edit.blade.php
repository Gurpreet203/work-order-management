@extends('layouts.main')

@section('content')
    <x-nav />
    <x-flash-messages />

    <form action="{{ route('users.update', $user) }}" method="post" class="my-form mt-5">
        @csrf
        @method('PUT')

        <h2>Edit User</h2>

        <div class="mb-3">
            <label for="first_name" class="form-label">First Name</label>
            <input type="text" name="first_name" class="form-control" required placeholder="First Name" value="{{ $user->first_name }}">
            <x-error name="first_name" />
        </div>

        <div class="mb-3">
            <label for="last_name" class="form-label">Last Name</label>
            <input type="text" name="last_name" class="form-control" required placeholder="Last Name" value="{{ $user->last_name }}">
            <x-error name="last_name" />
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" disabled value="{{ $user->email }}">
        </div>

        @if ($managers)
            <div class="mb-3">
                <label for="user_id" class="form-label">Manager</label>
                <select name="user_id" id="" class="form-select">
                    @foreach ($managers as $manager)
                        <option value="{{ $manager->id }}" @if ($user->user_id == $manager->id) Selected @endif>{{ $manager->name }}</option>
                    @endforeach
                </select>
                <x-error name="user_id" />
            </div>
        @endif

        <button type="submit" class="btn btn-secondary me-5">Update</button>
        <a href="{{ route('users') }}" class="btn btn-outline-secondary">Cancel</a>
    </form>
@endsection