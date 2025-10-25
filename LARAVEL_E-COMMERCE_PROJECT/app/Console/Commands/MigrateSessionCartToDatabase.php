<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cart;

class MigrateSessionCartToDatabase extends Command
{
    protected $signature = 'cart:migrate-session';
    protected $description = 'Migrate session cart to database';

    public function handle()
    {
        // This is just a template - adjust based on your needs
        $this->info('Session cart migration completed!');
    }
}