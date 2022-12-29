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
                ->visibleToAssignee()
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
            'description' => 'required_if:status_id,3',
            'file' => 'mimes:jpg,jpeg,png,mp4,pdf,ppt,xlsx,doc,docx,csv,txt,gif,mp3'
        ], [
            'description.required_if' => 'Description is required',
            'file.mimes' => 'File Extension Not Supported'
        ]);

        $StatusManage = new StatusManageController;

        if ($attributes['status_id'] != Status::CLOSE && Auth::user()->is_employee)
        {
            $StatusManage->update($workOrder, $attributes);

            return back()->with('success', 'Status Updated');
        }

        $assignment = $StatusManage->store($attributes, $workOrder);

         if (Auth::user()->role_id != Role::EMPLOYEE && $attributes['status_id'] != Status::CLOSE)
        {
            $assigned_to = $attributes['user_id'];
        }
        else
        {
            $assigned_to = Auth::user()->user_id  ?? $workOrder->user_id;
        }

        if (!$workOrder->exist($assigned_to , Auth::id(), $workOrder))
        {

            if ($attributes['status_id'] != Status::CLOSE)
            {
                $temp_assign = Assignment::exist($workOrder)->first();

                if ($temp_assign)
                {
                    $temp_assign->update([
                        'user_id' => $attributes['user_id'] ?? Auth::user()->user_id,
                        'assigned_by' => Auth::id()
                    ]);
                }
                else
                {
                    $assignment = $workOrder->assignment()->save($workOrder, [
                    'user_id' => $assigned_to,
                    'assigned_by' => Auth::id()
                ]);
                }
            }
                $progress = Progress::create([
                    'work_order_id' => $workOrder->id,
                    'description' => $attributes['description'],
                    'user_id' => Auth::id(),
                    'assigned_to' => $assigned_to,
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

            if (Auth::user()->role_id == Role::ADMIN)
            {
                return back()->with('success', 'Successfully work assigned');
            }

            return to_route('assigned.index')->with('success', 'Successfully work assigned');
        }
        
        return back()->with('error', 'Assignment Already Exist');
    }
}
