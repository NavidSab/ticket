<?php

namespace Modules\Ticket\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TicketDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
 
        DB::table('tickets')->insert([
            'name' => Str::random(10),
            'email' => Str::random(10).'@gmail.com',
            'title' => Str::random(10),
            'content' => Str::random(10),
            'attachment' => Str::random(10)
        ]);
 

    }
}

