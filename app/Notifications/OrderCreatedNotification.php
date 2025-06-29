<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCreatedNotification extends Notification implements ShouldQueue
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
            'service_title' => $this->order->service->title,
            'provider_name' => $this->order->provider->name,
            'message' => 'Your order "' . $this->order->title . '" has been created successfully!',
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Order Created Successfully')
            ->greeting('Hello ' . $notifiable->name)
            ->line('Your order "' . $this->order->title . '" has been created successfully!')
            ->line('Service: ' . $this->order->service->title)
            ->line('Provider: ' . $this->order->provider->name)
            ->line('Price: $' . number_format($this->order->price, 2))
            ->action('View Order', url(route('orders.show', $this->order)))
            ->line('The provider will review your request and update the status soon.');
    }
}
