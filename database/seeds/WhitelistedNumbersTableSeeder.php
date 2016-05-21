<?php

use Illuminate\Database\Seeder;
use League\Csv\Reader;

class WhitelistedNumbersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('whitelisted_numbers')->delete();

        $reader = Reader::createFromPath(database_path('whitelist.csv'));

        $whitelisted_numbers = [];
        foreach ($reader as $index => $row)
        {
            $whitelisted_numbers [] = array(
                'mobile' => $row[0],
            );
        }

        DB::table('whitelisted_numbers')->insert($whitelisted_numbers);
    }
}
