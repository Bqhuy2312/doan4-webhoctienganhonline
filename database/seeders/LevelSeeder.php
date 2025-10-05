<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Level;

class LevelSeeder extends Seeder
{
    public function run(): void
    {
        Level::insert([
            ['name' => 'Beginner', 'description' => 'Dành cho người mới bắt đầu'],
            ['name' => 'Intermediate', 'description' => 'Dành cho người đã có kiến thức cơ bản'],
            ['name' => 'Advanced', 'description' => 'Dành cho người học nâng cao'],
        ]);
    }
}

