<?php

namespace App\Http\Controllers;

use App\Models\Progress;
use App\Models\Role;
use App\Models\Status;
use App\Models\User;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkOrderController extends Controller
{
    public function index()
    {
        return view('workOrders.index', [
            'workOrders' => WorkOrder::with('status', 'StatusManage')
                ->visibleTo()
                ->search(request(['search']))
                ->paginate()
        ]);
    }

    public function create()
    {
        return view('workOrders.create');
    }

    public function store(Request $request)
    {
        $attributes = $request->validate([
            'title' => 'required|min:3|max:255',
            'description' => 'required|min:3',
            'file' => 'mimes:jpg,jpeg,png,mp4,pdf,ppt,xlsx,doc,docx,csv,txt,gif,mp3'
        ], [
            'file.mimes' => 'File Extension Not Supported'
        ]);

        $attributes += [
            'user_id' => Auth::id()
        ];
        
        $workOrder = WorkOrder::create($attributes);

        $progress = Progress::create([
            'work_order_id' => $workOrder->id,
            'user_id' => Auth::id(),
            'assigned_to' => Role::ADMIN,
            'status_id' => Status::OPEN
        ]);

        if ($request['file'])
        {
            $attachment = $request->file('file')->store('/attachments');
            $workOrder->attachments()->create([
                'url' => $attachment,
                'extension' => $request->file('file')->extension(),
                'work_order_id' => $workOrder->id,
                'progress_id' => $progress->id
            ]);
        }

        return to_route('work-orders.index')->with('success', 'Successfully Work Order Created');
    }

    public function show(WorkOrder $workOrder)
    {
        return view('workOrders.show', [
            'workOrder' => $workOrder,
            'users' => User::AssignedUsers()
                ->get(),
            'statuses' => Status::get(),
            'progress' => Progress::with('user', 'assigned_user', 'attachment')
                ->visibleTo($workOrder)
                ->get()
        ]);
    }
}
