<?php

namespace Database\Seeders;

use App\Enums\UserTypeEnum;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends SequentialSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::factory()->createMany([
            [
                'type' => UserTypeEnum::USER_TYPE_CUSTOMER,
                'name' => fake()->name(),
                'email' => fake()->safeEmail(),
                'email_verified_at' => now(),
                'password' => Hash::make('p@ssw0rd'), // password
                'remember_token' => Str::random(10),
            ],
            [
                'type' => UserTypeEnum::USER_TYPE_MERCHANT,
                'name' => fake()->name(),
                'email' => fake()->safeEmail(),
                'email_verified_at' => now(),
                'password' => Hash::make('p@ssw0rd'), // password
                'remember_token' => Str::random(10),
            ]
        ]);

        self::$user = $users[0];
        self::$merchant = $users[1];
    }
}
