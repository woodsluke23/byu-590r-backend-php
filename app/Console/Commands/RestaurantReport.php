<?php

namespace App\Console\Commands;

use App\Mail\RestaurantListMail;
use App\Models\Restaurant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class RestaurantReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:restaurant-report {--email=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'A report of all of my restaurants';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $restaurants = Restaurant::all();
        $sendToEmail = $this->option('email');

        Mail::to($sendToEmail)->send(new RestaurantListMail($restaurants));

        return Command::SUCCESS;
    }
}
