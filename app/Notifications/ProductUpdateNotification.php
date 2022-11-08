<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class ProductUpdateNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(protected Product $product, protected bool $updatedPrice, protected bool $updatedQuantity, protected bool $updatedDiscount)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return $notifiable->telegram_id ? ['telegram'] : ['mail'];
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

    public function toTelegram($notifiable)
    {
        $text = "Hello, the product {$this->product->title} was updated: \n";

        if ($this->updatedDiscount) {
            $text .= "\n\t- discount increase to {$this->product->discount}%";
        }
        if ($this->updatedQuantity) {
            $text .= "\n\t- product was delivered to our shop";
        }
        if ($this->updatedPrice) {
            $text .= "\n\t- price decrease to {$this->product->end_price}";
        }
        $text .= "\n\n Hurry up";


        return TelegramMessage::create()
            ->to($notifiable->telegram_id)
            ->content($text)
            ->button('Visit Product', route('products.show', $this->product));
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
            //
        ];
    }
}
