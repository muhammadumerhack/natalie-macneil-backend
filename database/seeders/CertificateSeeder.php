<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Certificate;

class CertificateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Certificate::create([
            'code'=>'123',
            'school'=>'West African Senior',
            'candidate'=>'Jhon Doe',
            'gender'=>'Male',
            'dob'=>'7/june/1997',
            'candidate_no'=>'12345',
            'image'=>'https://www.zoontjens.fr/cms/wp-content/uploads/stock-male.jpg',
            'year'=>'January 2019',
            'institute_id'=>1,    
        ]);
        Certificate::create([
            'code'=>'234',
            'school'=>'Senior School',
            'candidate'=>'Jhane Doe',
            'gender'=>'Female',
            'dob'=>'8/january/2000',
            'candidate_no'=>'1345',
            'image'=>'https://live.staticflickr.com/1509/23557642933_cf7a059acb_n.jpg',
            'year'=>'June 2020',
            'institute_id'=>2,    
        ]);

        Certificate::create([
            'code'=>'345',
            'school'=>'School',
            'candidate'=>'steve smith',
            'gender'=>'Male',
            'dob'=>'9/August/2001',
            'candidate_no'=>'134675',
            'image'=>'https://www.sneak-brands.de/wp-content/uploads/2016/06/team-1.jpg',
            'year'=>'July 2021',
            'institute_id'=>3,
        ]);

    }
}
