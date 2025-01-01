<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Employee::insert( [
            ["department_id" => 3, "emply_id" => 491, "name" => "Mehedi Hasan", "designation" => "Remediation Programme Officer", "phone" => "01894971811" , "email" => "mehedi.hasan1@rsc-bd.org", "date_of_join" => "2024-11-03", "status" => 1],
            ["department_id" => 3, "emply_id" => 492, "name" => "Asaduzzaman Hridoy", "designation" => "Remediation Programme Officer", "phone" => "01894971812", "email" => "asaduzzaman.hridoy@rsc-bd.org", "date_of_join" => "2024-11-06", "status" => 1],
            ["department_id" => 3, "emply_id" => 493, "name" => "Md. Mostafizur Rahman", "designation" => "Remediation Programme Officer", "phone" => "01894971810", "email" => "md.mostafizur@rsc-bd.org", "date_of_join" => "2024-11-06", "status" => 1],
            ["department_id" => 9, "emply_id" => 494, "name" => "Md. Humayun Kabir Tipu", "designation" => "Boiler Safety Engineer", "phone" => "01894971814", "email" => "humayun.kabir@rsc-bd.org", "date_of_join" => "2024-11-03", "status" => 1],
            ["department_id" => 9, "emply_id" => 495, "name" => "Mushfiq Ibne Kader", "designation" => "Boiler Safety Engineer", "phone" => "01894971817", "email" => "mushfiq.kader@rsc-bd.org", "date_of_join" => "2024-11-03", "status" => 1],
            ["department_id" => 8, "emply_id" => 496, "name" => "Protik Kumar Das", "designation" => "Fire & Life Safety Engineer", "phone" => "01894971809", "email" => "protik.kumar@rsc-bd.org", "date_of_join" => "2024-11-06", "status" => 1],
            ["department_id" => 9, "emply_id" => 497, "name" => "Md. Abu Sayed", "designation" => "Boiler Safety Engineer", "phone" => "01894971815", "email" => "abu.sayed@rsc-bd.org", "date_of_join" => "2024-12-01", "status" => 1],
            ["department_id" => 9, "emply_id" => 498, "name" => "Nawaze Mahmud", "designation" => "Boiler Safety Engineer", "phone" => "01894971813", "email" => "nawaze.mahmud@rsc-bd.org", "date_of_join" => "2024-12-01", "status" => 1],
            ["department_id" => 9, "emply_id" => 499, "name" => "Siam Mahbub", "designation" => "Boiler Safety Engineer", "phone" => "01894971816", "email" => "siam.mahbub@rsc-bd.org", "date_of_join" => "2024-12-03", "status" => 1],

        ]);
    }
}
