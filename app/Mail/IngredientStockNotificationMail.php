<?php

namespace App\Mail;

use App\Models\Ingredient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class IngredientStockNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Ingredient
     */
    protected $ingredient;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Ingredient $ingredient)
    {
        $this->ingredient = $ingredient;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.ingredient-stock-notification-email', [
            'ingredient' => $this->ingredient->name,
            'admin' => "Admin"
        ]);
    }
}
