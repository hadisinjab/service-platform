<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'client_id' => $this->order->client_id,
            'client_name' => $this->order->client->name,
            'service_title' => $this->order->service->title,
            'message' => 'You have received a new order from ' . $this->order->client->name,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Order Received')
            ->greeting('Hello ' . $notifiable->name)
            ->line('You have received a new order from ' . $this->order->client->name)
            ->action('View Order', url(route('orders.show', $this->order)))
            ->line('Thank you for using our platform!');
    }
}
