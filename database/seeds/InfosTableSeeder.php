<?php

use Illuminate\Database\Seeder;
use League\Csv\Reader;

class InfosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('infos')->delete();

        $reader = Reader::createFromPath(database_path('infos.csv'));

        $groups = [];
        foreach ($reader as $index => $row)
        {
            $code = $row[0];
            $description = $row[1];
            $infos [] = compact('code', 'description');
        }

        DB::table('infos')->insert($infos);
    }
}
