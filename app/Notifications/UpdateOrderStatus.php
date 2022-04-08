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

    protected $orderStatusMessage;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;

        $this->orderStatusMessage = [
            OrderStatus::NEW_ORDER => 'recently added',
            OrderStatus::IN_PROCCESS => 'has been approved',
            OrderStatus::IN_SHIPPING => 'in transit',
            OrderStatus::COMPLETED => 'is completed',
            OrderStatus::CANCELED => 'is canceled',
        ];
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
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
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
                'message' => __(
                    'Order #:code ' . $this->orderStatusMessage[$this->order->status],
                    ['code' => $this->order->order_code]
                ),
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
            'message' => __(
                'Order #:code ' . $this->orderStatusMessage[$this->order->status],
                ['code' => $this->order->order_code]
            ),
            'link' => route('user.purchase.details', $this->order->id),
        ];
    }
}
