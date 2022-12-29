<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Role;
use App\Models\Status;
use App\Models\StatusManage;
use App\Models\User;
use App\Models\WorkOrder;
use App\Notifications\WorkOrderClosedNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class StatusManageController extends Controller
{
    public function store($attributes, WorkOrder $workOrder)
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
            $user = User::find($workOrder->user_id);

            Notification::send($user, new WorkOrderClosedNotification(Auth::user(), $workOrder));

        }

        elseif ($attributes['status_id'] == Status::CLOSE)
        {
            $workOrder->update([
                'assigned_to' => Auth::user()->user_id
            ]);

            return Assignment::updateOrCreate([
                'work_order_id' => $workOrder->id,
            ], [
                'user_id' => Auth::user()->user_id,
                'assigned_by' => Auth::id()
            ]);
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
    }
}
