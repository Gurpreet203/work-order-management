<?php

namespace App\Http\Controllers;

use App\Models\Progress;
use App\Models\Role;
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
    public function store($attributes, WorkOrder $workOrder, Request $request)
    {
        StatusManage::updateOrCreate([
            'work_order_id' => $workOrder->id,
            'user_id' => Auth::id(),
        ], [
            'status_id' => $attributes['status_id']
        ]);

        if ($attributes['status_id'] == Status::CLOSE && Auth::user()->role_id == Role::ADMIN)
        {
            $workOrder->update([
                'status_id' => Status::CLOSE
            ]);

            $progress = Progress::create([
                'work_order_id' => $workOrder->id,
                'user_id' => Auth::id(),
                'assigned_to' => Auth::user()->user_id,
                'description' => $attributes['description'],
                'status_id' => $attributes['status_id']
            ]);

            if ($request['file'])
            {
                $attachment = $request->file('file')->store('/attachments');

                $workOrder->attachments()->create([
                    'url' => $attachment,
                    'work_order_id' => $workOrder->id,
                    'extension' => $request->file('file')->extension(),
                    'progress_id' => $progress->id
                ]);
            }
            $user = User::find($workOrder->user_id);

            //Notification::send($user, new WorkOrderClosedNotification(Auth::user(), $workOrder));

            return to_route('work-orders.index')->with('succcess', 'Successfully Work Order Closed');

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
