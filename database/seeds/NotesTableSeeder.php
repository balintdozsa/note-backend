<?php

use Illuminate\Database\Seeder;

class NotesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('notes')->insert([
            [
                'user_id' => 1,
                'note' => 'Test Note',
            ],
            [
                'user_id' => 1,
                'note' => 'Test Note 2',
            ],
            [
                'user_id' => 5,
                'note' => 'Test Note 3',
            ]
        ]);
    }
}
