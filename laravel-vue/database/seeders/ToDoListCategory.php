<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ToDoListCategory extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        \App\Models\ToDoListCategory::create([
            'category' => 'Backlog',
        ]);
    }
}
