<?php

namespace Database\Seeders;

use App\Enums\AppRoles;
use App\Models\Authority;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Note: cities and areas are seeded by their respective migrations, so they
     * are intentionally not re-seeded here.
     */
    public function run(): void
    {
        $this->call([
            PermissionAndRoleSeeder::class,
        ]);

        $admin = User::updateOrCreate(
            ['phone' => '0900000000'],
            [
                'name' => 'Super Admin',
                'password' => 'password',
                'phone_verified_at' => now(),
                'active' => true,
            ],
        );

        $admin->syncRoles([AppRoles::SUPER_ADMIN->value]);

        // Sample authorities + demo accounts are demo data only — keep them out
        // of the test database so feature tests can assert exact counts.
        if (! app()->environment('testing')) {
            $this->call([AuthoritySeeder::class]);
            $this->seedDemoAccounts();
        }
    }

    /**
     * Ready-to-use demo accounts (all password: "password").
     */
    private function seedDemoAccounts(): void
    {
        $citizen = User::updateOrCreate(
            ['phone' => '0911111111'],
            ['name' => 'مواطن تجريبي', 'password' => 'password', 'phone_verified_at' => now(), 'active' => true],
        );
        $citizen->syncRoles([AppRoles::USER->value]);

        $admin = User::updateOrCreate(
            ['phone' => '0922222222'],
            ['name' => 'مدير تجريبي', 'password' => 'password', 'phone_verified_at' => now(), 'active' => true],
        );
        $admin->syncRoles([AppRoles::ADMIN->value]);

        if ($authority = Authority::query()->first()) {
            $rep = User::updateOrCreate(
                ['phone' => '0933333333'],
                [
                    'name' => 'مندوب تجريبي',
                    'password' => 'password',
                    'phone_verified_at' => now(),
                    'active' => true,
                    'authority_id' => $authority->id,
                ],
            );
            $rep->syncRoles([AppRoles::REPRESENTATIVE->value]);
        }
    }
}
