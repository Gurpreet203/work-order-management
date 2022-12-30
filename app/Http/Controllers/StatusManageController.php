<?php

namespace App\Http\Controllers;

use App\Models\Progress;
use App\Models\Status;
use App\Models\StatusManage;
use App\Models\User;
use App\Models\WorkOrder;
use App\Notifications\WorkOrderClosedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class StatusManageController extends Controller
{
    public function store(WorkOrder $workOrder, Request $request)
    {
        $attributes = $request->validate([
            'status_id' => 'required|exists:statuses,id',
            'description' => 'required_if:status_id,3,4',
            'file' => 'mimes:jpg,jpeg,png,mp4,pdf,ppt,xlsx,doc,docx,csv,txt,gif,mp3'
        ], [
            'description.required_if' => 'Description is required',
            'file.mimes' => 'File Extension Not Supported'
        ]);

        StatusManage::updateOrCreate([
            'work_order_id' => $workOrder->id,
            'user_id' => Auth::id(),
        ], [
            'status_id' => $attributes['status_id']
        ]);

        if ($attributes['status_id'] == Status::CLOSE && Auth::user()->is_admin)
        {

            $workOrder->update([
                'status_id' => Status::CLOSE
            ]);

            $user = User::find($workOrder->user_id);
            Notification::send($user, new WorkOrderClosedNotification(Auth::user(), $workOrder));

            return to_route('work-orders.index');
        }
    }

    public function update($workOrder, $attributes)
    {
        StatusManage::updateOrCreate([
            'work_order_id' => $workOrder->id,
            'user_id' => Auth::id()
        ], [
            'status_id' => $attributes['status_id']
        ]);

        Progress::where('work_order_id', $workOrder->id)
            ->where('assigned_to', Auth::id())
            ->update([
                'status_id' => $attributes['status_id']
            ]);
    }
}
