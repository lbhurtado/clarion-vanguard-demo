<?php

use Illuminate\Database\Seeder;
use League\Csv\Reader;

class SubscriptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('subscriptions')->delete();

        $reader = Reader::createFromPath(database_path('subscriptions.csv'));

        $subscriptions = [];
        foreach ($reader as $index => $row)
        {
            $code = $row[0];
            $description = $row[1];
            $subscriptions [] = compact('code', 'description');
        }

        DB::table('subscriptions')->insert($subscriptions);
    }
}
