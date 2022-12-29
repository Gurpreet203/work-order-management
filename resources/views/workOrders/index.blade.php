@extends('layouts.main')

@section('content')
    <x-nav />
    <div class="rest-body">

        <x-flash-messages />

        <div class="d-flex justify-content-between ">
            <form action="{{ route('work-orders.index') }}" method="GET">
                <input type="text" placeholder="Search by title" name="search" value="{{ request('search') }}" class="form-control mb-3 search">
            </form>

            <a href="{{ route('work-orders.index') }}" class="btn" style="font-weight: bold">All Work Orders</a>
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
                        <td><a href="{{ route('work-orders.show', $workOrder) }}">{{ $workOrder->title }}</a></td>
                        <td> {{ $workOrder->created_at->diffForHumans() }} </td>
                        <td> {{ $workOrder->status->name }} </td>
                    </tr>
                @endforeach
            @else
                </table>
                <h2>No Work Order Exist </h2>
            @endif
        </table>
    </div>

    {{ $workOrders->links() }}

@endsection