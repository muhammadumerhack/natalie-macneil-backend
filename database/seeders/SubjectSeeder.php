<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Subject::create([
            'certificate_code'=>'123',
            'economics'=>50,
            'islamic_studies'=>60,
            'mathemetics'=>50,
            'agriculture_science'=>80,
            'biology'=>20,
            'chemistry'=>30,
            'physics'=>50,
        ]);
        Subject::create([
            'certificate_code'=>'234',
            'economics'=>50,
            'islamic_studies'=>60,
            'mathemetics'=>50,
            'agriculture_science'=>80,
            'biology'=>20,
            'chemistry'=>30,
            'physics'=>50,
        ]);
        Subject::create([
            'certificate_code'=>'345',
            'economics'=>50,
            'islamic_studies'=>60,
            'mathemetics'=>50,
            'agriculture_science'=>80,
            'biology'=>20,
            'chemistry'=>30,
            'physics'=>50,
        ]);

    }
}
