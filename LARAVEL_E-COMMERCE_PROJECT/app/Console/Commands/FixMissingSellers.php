<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Seller;
use Illuminate\Console\Command;

class FixMissingSellers extends Command
{
    protected $signature = 'fix:missing-sellers';
    protected $description = 'Create seller records for users with seller role but no seller record';

    public function handle()
    {
        $this->info('Checking for users with missing seller records...');
        
        $sellersWithoutRecords = User::where('role', 'seller')
            ->whereDoesntHave('seller')
            ->get();

        if ($sellersWithoutRecords->isEmpty()) {
            $this->info('✓ All seller users have seller records!');
            return 0;
        }

        $this->warn("Found {$sellersWithoutRecords->count()} seller(s) without records:");
        
        foreach ($sellersWithoutRecords as $user) {
            $this->line("  - User ID: {$user->id}, Name: {$user->name}");
            
            Seller::create([
                'user_id' => $user->id,
                'store_name' => $user->name . ' Shop'
            ]);
            
            $this->info("    ✓ Created seller record");
        }

        $this->info("\n✓ All done!");
        return 0;
    }
}