<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $dbConnection = DB::connection();
        $autoIncrement = 10000000;
        $tables = [
            'users', 'login_histories', 'oauth_clients',
        ];
        if ($dbConnection->getConfig('driver') == 'mysql') {
            $tablePrefix = DB::connection()->getTablePrefix();
            foreach ($tables as $table) {
                DB::statement('ALTER TABLE ' . $tablePrefix . $table . ' AUTO_INCREMENT = ' . $autoIncrement);
            }
        }
        $this->call(SettingsSeeder::class);
        $this->call(RegionSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(PassportSeeder::class);

    }
}
