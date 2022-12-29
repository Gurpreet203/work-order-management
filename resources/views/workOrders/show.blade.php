@extends('layouts.main')

@section('content')
    <x-nav />
    <div class="rest-body">
        <x-flash-messages />

        <div class="work-order-detail">
            <p><span>Title:= </span>{{ $workOrder->title }}</p>
            <p><span>Description:= </span>{{ $workOrder->description }}</p>
            <p><span>Status: = </span>{{ $workOrder->status->name }}</p>
        </div>

        @php
            $assignedUserId = null;
        @endphp

        @if (Auth::user()->role_id == 1 || Auth::user()->customer)
            <h2>Progress:-</h2>

            <div class="work-order-detail">
                @foreach ($progress as $progress)
                    @php
                        $assignedUserId = $progress->assigned_user->id
                    @endphp
                    <p> <span>(assigned by)</span> {{ $progress->user->name }} <span class="progress-user-role"> ({{ $progress->user->email }}) </span> 
                        <  <span>(assigned to)</span> {{ $progress->assigned_user->name }} <span class="progress-user-role"> ({{ $progress->assigned_user->email }}) </span> 
                    </p>
                    <p>
                        <span>Status= </span>{{ $progress->status->name }} 
                    </p>
                    <p style="color: grey;font-size: 14px;">{{ $progress->description }}</p>
                    <div class="attachments">
                        @if ($progress->attachment)
                            @if ($progress->attachment->extension == 'jpg')   
                                <div>
                                    <i class="bi bi-image"></i>
                                    <a href="{{ asset('storage/'.$progress->attachment->url) }}" target="_blank">image.{{$progress->attachment->extension}}</a>
                                </div>
                            @elseif ($progress->attachment->extension == 'mp3')
                                <div>
                                    <i class="bi bi-filetype-mp3"></i>
                                    <a href="{{ asset('storage/'.$progress->attachment->url) }}" target="_blank">audio.{{$progress->attachment->extension}}</a>
                                </div>
                                
                            @elseif ($progress->attachment->extension == 'mp4')
                                <div>
                                    <i class="bi bi-filetype-mp4"></i>
                                    <a href="{{ asset('storage/'.$progress->attachment->url) }}" target="_blank">video.{{$progress->attachment->extension}}</a>
                                </div>
                                
                            @else
                                <div>
                                    <i class="bi bi-file"></i>
                                    <a href="{{ asset('storage/'.$progress->attachment->url) }}" target="_blank">file.{{$progress->attachment->extension}}</a>
                                </div>
                            @endif
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        @if (!Auth::user()->customer)
            <h2>Edit Section:- </h2>

        <div>
            <form action="{{ route('assigned.store', $workOrder) }}" method="POST" class="my-form edit-section" enctype="multipart/form-data">
                @csrf

                @if (Auth::user()->role_id !=3)
                    <div class="mb-3">
                        <label for="user_id" class="form-label">Assigned To</label>
                        <select name="user_id" id="" class="form-select">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @if ($user->id == $assignedUserId)
                                    Selected
                                @endif>{{ $user->name }}</option>
                            @endforeach
                        </select>
                        <x-error name="user_id" />
                    </div>
                @endif

                <div class="mb-3">
                    <label for="status_id" class="form-label">Status</label>
                    <select name="status_id" id="" class="form-select">
                        @foreach ($statuses as $status)
                            @if (!Auth::user()->is_employee && $status->id == 2)
                                @continue
                            @endif
                            @if (Auth::user()->is_employee && $status->id == 1)
                                @continue
                            @endif
                            <option value="{{ $status->id }}" @if ($status->id == $workOrder->status->id)
                                Selected
                            @endif>{{ $status->name }}</option>
                        @endforeach
                    </select>
                    <x-error name="status_id" />
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" class ="form-control" id="" cols="30" rows="5" placeholder="Description">{{ old('description') }}</textarea>
                    <x-error name="description" />
                </div>

                <div class="mb-3">
                    <label for="file" class="form-label">Attachment (optional)</label>
                    <input type="file" name="file" class="form-control">
                    <x-error name="file" />
                </div>
                <p>Supported Attachments:- jpg , jpeg , png , mp4 , pdf , ppt , xlsx , doc , docx , csv , txt , gif , mp3</p>

                <button type="submit" class="btn btn-secondary">Save</button>
                <a href="{{ route('work-orders.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </form>
        </div>
        @endif
    </div>
@endsection