<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call([
//            PermissionsSeeder::class,
            PermissionRoleSeeder::class,
//            EmailMessagesSeeder::class,
//            smsMessagesSeeder::class,
//            PosSettingSeeder::class,
//            PaymentMethodSeeder::class,
//            CurrencySeeder::class,
//            SettingSeeder::class,
//            PermissionsSeeder::class,
//            RoleSeeder::class,
//            UserSeeder::class,
//            PermissionRoleSeeder::class,
        ]);

    }
}
