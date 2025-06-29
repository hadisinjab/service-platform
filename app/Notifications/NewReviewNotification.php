<?php

namespace App\Notifications;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewReviewNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $review;

    public function __construct(Review $review)
    {
        $this->review = $review;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable)
    {
        return [
            'review_id' => $this->review->id,
            'order_id' => $this->review->order_id,
            'client_name' => $this->review->client->name,
            'rating' => $this->review->rating,
            'message' => 'You have received a new review from ' . $this->review->client->name,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Review Received')
            ->greeting('Hello ' . $notifiable->name)
            ->line('You have received a new review from ' . $this->review->client->name)
            ->action('View Review', url(route('reviews.show', $this->review)))
            ->line('Thank you for using our platform!');
    }
}
