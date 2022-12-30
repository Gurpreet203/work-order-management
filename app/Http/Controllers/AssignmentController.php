<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Progress;
use App\Models\Role;
use App\Models\Status;
use App\Models\User;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AssignmentController extends Controller
{
    public function index()
    {
        return view('workOrders.index', [
            'workOrders' => WorkOrder::wherehas('assignment')
                ->with('status', 'StatusManage')
                ->latest()
                ->paginate()
        ]);
    }

    public function store(Request $request, WorkOrder $workOrder)
    {
        $attributes = $request->validate([
            'user_id' => ['sometimes','required', 
                        Rule::in(User::AssignedUsers()
                            ->get()
                            ->pluck('id')
                            ->toArray()
                        )],
            'status_id' => 'required|exists:statuses,id',
            'description' => 'required_if:status_id,3,4',
            'file' => 'mimes:jpg,jpeg,png,mp4,pdf,ppt,xlsx,doc,docx,csv,txt,gif,mp3'
        ], [
            'description.required_if' => 'Description is required',
            'file.mimes' => 'File Extension Not Supported'
        ]);

        $StatusManage = new StatusManageController;

        if ($attributes['status_id'] != Status::RESOLVE && Auth::user()->is_employee)
        {
            $StatusManage->update($workOrder, $attributes);

            return back()->with('success', 'Status Updates');
        }

        $route =  $StatusManage->store($workOrder, $request);

        if (!Auth::user()->is_employee && $attributes['status_id'] != Status::RESOLVE && $attributes['status_id'] != Status::CLOSE )
        {
            $assigned_to = $attributes['user_id'];
        }
        else
        {
            $assigned_to = Auth::user()->user_id == 0 ? $workOrder->user_id : Auth::user()->user_id;
        }


        if (!Assignment::exist($workOrder)->first())
        {
            $assignment = $workOrder->assignment()->save($workOrder, [
                'user_id' => $assigned_to,
                'assigned_by' => Auth::id()
            ]); 
        }
        else
        {
            $workOrder->assignment()->detach([
                'work_order_id' => $workOrder->id,
                'user_id' => $assigned_to,
                'assigned_by' => Auth::id()
            ]);

            $workOrder->assignment()->save($workOrder, [
                'user_id' => $assigned_to,
                'assigned_by' => Auth::id()
            ]);
            $assignment = Assignment::exist($workOrder)->first();

        }
        
        $progress = Progress::create([
            'work_order_id' => $workOrder->id,
            'user_id' => Auth::id(),
            'assigned_to' => $assigned_to,
            'description' => $attributes['description'],
            'status_id' => $attributes['status_id']
        ]);

        if ($request['file'])
        {
            $attachment = $request->file('file')->store('/attachments');

            $assignment->attachments()->create([
                'url' => $attachment,
                'work_order_id' => $workOrder->id,
                'extension' => $request->file('file')->extension(),
                'progress_id' => $progress->id
            ]);
        }

        $workOrder->update([
            'assigned_to' => $assigned_to
        ]);

        if (Auth::user()->is_admin)
        {
            return $route ? $route->with('success', 'Successfully Work Order Closed') :
                back()->with('success', 'Successfully work assigned');
        }

        return to_route('assigned.index')->with('success', 'Successfully work assigned');
    }
}
