<?php

namespace Database\Seeders;

use App\Models\Title;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TitleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Title::truncate();




        $roles = [
            [
                'name' => 'موظف',
                'slug' => 'employee',
            ],
            [
                'name' => 'مدير المشروع',
                'slug' => 'manager',
            ],
            [
                'name' => 'مندوب المشتريات',
                'slug' => 'purchasing',
            ],
            [
                'name' => 'سكرتير المشروع',
                'slug' => 'pro_secretary',
            ],
            [
                'name' => 'سكرتير الادارة',
                'slug' => 'man_secretary',
            ],
            [
                'name' => 'مدير المشاريع',
                'slug' => 'pro_manager',
            ],
            [
                'name' => 'الادارة المالية',
                'slug' => 'finance_manager',
            ],
            [
                'name' => ' قسم التخطيط',
                'slug' => 'planning',
            ],
            // [
            //     'name' => 'مدير المبيعات',
            //     'slug' => 'sales',
            // ],
        ];

        foreach ($roles as $role) {
            Title::create($role);
        }
    }
}
