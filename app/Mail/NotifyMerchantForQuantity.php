<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyMerchantForQuantity extends Mailable
{
    use Queueable, SerializesModels;

    public array $ingredients;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $ingredients)
    {
        $this->ingredients = $ingredients;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.ingredient-quantity-exceeded');
    }
}
