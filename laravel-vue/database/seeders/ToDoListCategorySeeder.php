<?php

namespace Database\Seeders;

use App\Models\ToDoListCategory;
use Illuminate\Database\Seeder;

class ToDoListCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ToDoListCategory::insert([
            [ 'category' => 'Backlog'],
            [ 'category' => 'To Do'],
            [ 'category' => 'In Progress'],
            [ 'category' => 'Done'],

        ]);
    }
}
