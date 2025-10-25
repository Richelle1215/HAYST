<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class UpdateProductShopNamesSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::with('seller')->get();
        
        foreach($products as $product) {
            if($product->seller) {
                $product->shop_name = $product->seller->shop_name;
                $product->save();
            }
        }
        
        $this->command->info('Product shop names updated successfully!');
    }
}