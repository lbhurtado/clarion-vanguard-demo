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
            $name = $row[0];
            $code = strtolower(isset($row[1]) ?: $row[0]);
            $groups [] = compact('name', 'code');
        }

        DB::table('groups')->insert($groups);
    }
}
