<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Group;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Group::create([
            'name'=>'Group1',
            'amount'=> 50    
        ]);
        Group::create([
            'name'=>'Group2',
            'amount'=> 60    
        ]);
        Group::create([
            'name'=>'Group3',
            'amount'=> 70    
        ]);

    }
}
