<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Partner::create([
            'name'  => 'Abad',
        ]);
        Partner::create([
            'name'  => 'Ferreteria Ubetense',
        ]);
        Partner::create([
            'name'  => 'Magserveis',
        ]);
        Partner::create([
            'name'  => 'Calefon',
        ]);
    }
}
