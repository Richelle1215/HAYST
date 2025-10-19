<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Seller; 
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB; 
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Fortify\Rules\Password;

class CreateNewUser implements CreatesNewUsers
{
    /**
     * Ibinabalik ang password validation rules.
     */
    protected function passwordRules()
    {
        return ['required', 'string', new Password, 'confirmed'];
    }

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input)
    {
        // 1. Determine Role and Validation Rules
        $userCount = User::count();
        $isFirstUser = ($userCount === 0);
        
        // Kung unang user, siya ay Admin. Kung hindi, gamitin ang role na galing sa input.
        $role = $isFirstUser ? 'admin' : $input['role'];
        
        // Base Validation Rules
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'role' => ['required', 'string', Rule::in(['customer', 'seller'])],
        ];

        // Seller-specific Validation Rules
        if ($role === 'seller' && !$isFirstUser) {
             // FIXED: Gamitin ang 'store_name' column na talagang existing sa database
             $rules['shopname'] = ['required', 'string', 'max:255', 'unique:sellers,store_name']; 
        }
        
        // Run Validation
        Validator::make($input, $rules)->validate();

        return DB::transaction(function () use ($input, $role, $isFirstUser) {
            
            // 2. Create the User
            $user = User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
                'role' => $role,
            ]);
            
            // 3. Create Related Profile (Seller or Customer)
            if ($user->role === 'seller') {
                // Shop name logic: Default name kung admin, o gamitin ang input kung seller
                $shopName = $isFirstUser ? $user->name . ' Shop' : $input['shopname'];
                
                // FIXED: Gamitin ang 'store_name' column na talagang existing sa database
                Seller::create([
                    'user_id' => $user->id,
                    'store_name' => $shopName,
                ]);
            } elseif ($user->role === 'customer') {
                Customer::create(['user_id' => $user->id]);
            }
            
            return $user;
        });
    }
}