<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class IngredientQuantityThreshold extends Notification
{
    use Queueable;

    private array $ingredients;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(array $ingredients)
    {
        $this->ingredients = $ingredients;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
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
            ->greeting('Ingredients Quantity Alert')
            ->line('One of your ingredients quantity becomes below 50%!')
            ->view('mail.ingredients-quantity', [
                'ingredients' => $this->ingredients
            ])
            ->action(
                'Take an action',
                sprintf('https://my-awesome-shop.online/merchant/%s/ingredients', $notifiable->id)
            )
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
            //
        ];
    }
}
