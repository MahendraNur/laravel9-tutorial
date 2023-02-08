<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Schema::disableForeignKeyConstraints();
        // Student::truncate();
        // Schema::enableForeignKeyConstraints();

        // $data = [
        //     ['name' => 'ayu','gender' => 'P','nis' =>'201114701','class_id' => 2],
        //     ['name' => 'budi','gender' => 'L','nis' =>'201114702','class_id' => 1],
        //     ['name' => 'siti','gender' => 'P','nis' =>'201114703','class_id' => 1],
        //     ['name' => 'yono','gender' => 'L','nis' =>'201114704','class_id' => 2],
        // ];

        // foreach($data as $value){
        //     Student::insert([
        //         'name' => $value['name'],
        //         'gender' => $value['gender'],
        //         'nis' => $value['nis'],
        //         'class_id' => $value['class_id'],
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ]);
        // }

        Student::factory()->count(1000)->create();
    }
}
