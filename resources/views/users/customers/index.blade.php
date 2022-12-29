@extends('layouts.main')

@section('content')
    <x-nav />
    <div class="rest-body">

        <x-flash-messages />

        <div class="d-flex justify-content-between ">
            <form action="{{ route('customers.index') }}" method="GET">
                <input type="text" placeholder="Search by title" name="search" value="{{ request('search') }}" class="form-control mb-3 search">
            </form>

            <a href="{{ route('customers.index') }}" class="btn" style="font-weight: bold">All Work Orders</a>
        </div>

        <table class="table table-striped">
            <tr>
                <th>Title</th>
                <th>Created</th>
                <th>Status</th>
            </tr>

            @if ($workOrders->count() > 0)
                
                @foreach ($workOrders as $workOrder)
                    <tr>
                        <td><a href="">{{ $workOrder->title }}</a></td>
                        <td> {{ $workOrder->created_at->diffForHumans() }} </td>
                        <td> {{ $workOrder->status }} </td>
                    </tr>
                @endforeach
            @else
                </table>
                <h2>No Work Order Created </h2>
            @endif
        </table>
    </div>

    {{ $workOrders->links() }}

@endsection