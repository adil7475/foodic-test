<?php

namespace App\Jobs;

use App\Mail\IngredientStockNotificationMail;
use App\Models\Ingredient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class IngredientStockNotificationEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Ingredient
     */
    protected $ingredient;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Ingredient $ingredient)
    {
        $this->ingredient = $ingredient;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ingredientStockNotificationEmail = new IngredientStockNotificationMail($this->ingredient);
        $adminEmail = config('settings.admin_email');
        Mail::to($adminEmail)->send($ingredientStockNotificationEmail);
    }
}
