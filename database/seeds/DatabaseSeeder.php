<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(GroupsTableSeeder::class);
        $this->call(BlacklistedNumbersTableSeeder::class);
        $this->call(WhitelistedNumbersTableSeeder::class);
    }
}
