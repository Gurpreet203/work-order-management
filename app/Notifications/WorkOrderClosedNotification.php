<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\WorkOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WorkOrderClosedNotification extends Notification
{
    use Queueable;
    public $user;
    public $workOrder;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, WorkOrder $workOrder)
    {
        $this->user = $user;
        $this->workOrder = $workOrder;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('Hi '.$notifiable->name)
                    ->line('Your Work Order '.$this->workOrder->title.' is Resolved Now');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'to' => $notifiable->email,
            'from' => $this->user->email,
            'for' => 'told the user his/her work order is resolved now'
        ];
    }
}
