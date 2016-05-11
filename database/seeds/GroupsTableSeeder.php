<?php

use Illuminate\Database\Seeder;
use League\Csv\Reader;

class GroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('groups')->delete();

        $reader = Reader::createFromPath(database_path('groups.csv'));

        $groups = [];
        foreach ($reader as $index => $row)
        {
            $groups [] = array(
                'name' => $row[0],
            );
        }

        DB::table('groups')->insert($groups);
    }
}
