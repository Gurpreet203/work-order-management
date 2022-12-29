@extends('layouts.main')

@section('content')
    <x-nav />
    <x-flash-messages />

    <form action="{{ route('work-orders.store') }}" method="post" class="my-form" enctype="multipart/form-data">
        @csrf

        <h2>Create Work Order</h2>
        
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" class="form-control" required placeholder="Title" value="{{ old('title') }}">
            <x-error name="title" />
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="" cols="30" rows="5" class="form-control" placeholder="Description">{{ old('description') }}</textarea>
            <x-error name="description" />
        </div>

        <div class="mb-3">
            <label for="file" class="form-label">Attachment (optional)</label>
            <input type="file" name="file" class="form-control">
            <x-error name="file" />
            <p>Supported Attachments:- jpg , jpeg , png , mp4 , pdf , ppt , xlsx , doc , docx , csv , txt , gif, mp3</p>
        </div>

        <button type="submit" class="btn btn-secondary me-5">Create</button>
        <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </form>
@endsection