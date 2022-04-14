<?php

namespace App\Notifications;

use App\Models\Order;
use App\Enums\OrderStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UpdateOrderStatus extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;

    protected $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;

        $orderStatusMessage = [
            OrderStatus::NEW_ORDER => 'recently added',
            OrderStatus::IN_PROCCESS => 'has been approved',
            OrderStatus::IN_SHIPPING => 'in transit',
            OrderStatus::COMPLETED => 'is completed',
            OrderStatus::CANCELED => 'is canceled',
        ];

        $this->message =  __(
            'Order #:code ' . $orderStatusMessage[$order->status],
            ['code' => $order->order_code]
        );
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [
            'database',
            'broadcast',
            'mail',
        ];
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
            ->subject('[' . env('APP_NAME') . '] ' . __('Order Status Update'))
            ->line($this->message)
            ->action(
                __('Click here to view your order'),
                route('user.purchase.details', $this->order->id)
            )
            ->line(__('Thank you for your order!'));
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
            'id' => $this->id,
            'read_at' => null,
            'data' => [
                'title' => __('Order Status Update'),
                'message' => $this->message,
                'link' => route('user.purchase.details', $this->order->id),
            ],
        ];
    }

    /**
     * Get the database representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'title' => __('Order Status Update'),
            'message' => $this->message,
            'link' => route('user.purchase.details', $this->order->id),
        ];
    }
}
