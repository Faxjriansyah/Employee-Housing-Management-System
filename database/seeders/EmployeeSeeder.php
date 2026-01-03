<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $employees = [];

        // 60 karyawan aktif
        for ($i = 1; $i <= 60; $i++) {
            $employees[] = [
                'employee_code' => 'EMP' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'name' => 'Employee ' . $i,
                'department_id' => rand(1, 5),
                'is_active' => true,
            ];
        }

        // 12 karyawan nonaktif
        for ($i = 61; $i <= 72; $i++) {
            $employees[] = [
                'employee_code' => 'EMP' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'name' => 'Employee ' . $i,
                'department_id' => rand(1, 5),
                'is_active' => false,
            ];
        }

        DB::table('employees')->insert($employees);
    }
}
