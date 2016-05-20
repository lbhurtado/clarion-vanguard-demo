<?php

use Illuminate\Database\Seeder;
use League\Csv\Reader;

class BlacklistedNumbersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('blacklisted_numbers')->delete();

        $reader = Reader::createFromPath(database_path('blacklist.csv'));

        $blacklisted_numbers = [];
        foreach ($reader as $index => $row)
        {
            $blacklisted_numbers [] = array(
                'mobile' => $row[0],
            );
        }

        DB::table('blacklisted_numbers')->insert($blacklisted_numbers);
    }
}
