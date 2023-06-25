<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Create a admin user
        \App\Models\User::create([
            'name' => 'Brevis Nguyen',
            'email' => 'brevisnguyen@gmail.com',
            'password' => Hash::make('admin123'),
        ]);

        // Create a category
        $cat_1 = \App\Models\Category::create(['name' => 'Danh mục 1']);
        $cat_2 = \App\Models\Category::create(['name' => 'Danh mục 2']);

        // Create some products
        \App\Models\Product::create([
            'name' => 'Sản phẩm 1',
            'code' => 'SP1',
            'category_id' => $cat_1->id,
            'price' => 200,
            'unit' => 'a',
            'image' => 'YZOLGSTaspU3evFYtreWATbAFdF9Ik-metadGFzdHktYnVyZ2VyLWlzb2xhdGVkLXdoaXRlLWJhY2tncm91bmQtZnJlc2gtaGFtYnVyZ2VyLWZhc3Rmb29kLXdpdGgtYmVlZi1jaGVlc2UuanBn-.jpg'
        ]);
        \App\Models\Product::create([
            'name' => 'Sản phẩm 2',
            'code' => 'SP1',
            'category_id' => $cat_1->id,
            'price' => 300,
            'unit' => 'kg',
            'image' => 'css5Y2ijuCo6MGJplcDue6ap7ywobZ-metaYmFrZWQtY2hpY2tlbi13aW5ncy1hc2lhbi1zdHlsZS10b21hdG9lcy1zYXVjZS1wbGF0ZS5qcGc=-.jpg'
        ]);
        \App\Models\Product::create([
            'name' => 'Sản phẩm 3',
            'code' => 'A02',
            'category_id' => $cat_2->id,
            'price' => 400,
            'unit' => 'pair',
            'image' => 'rdG06odkeFCudQWTDEMyveD0OqY2Xf-metaYmFrZWQtY2hpY2tlbi13aW5ncy1hc2lhbi1zdHlsZS10b21hdG9lcy1zYXVjZS1wbGF0ZS5qcGc=-.jpg'
        ]);
        \App\Models\Product::create([
            'name' => 'Sản phẩm 4',
            'code' => 'B01',
            'category_id' => $cat_2->id,
            'price' => 500,
            'unit' => 'a',
            'image' => '9WD4NH2VngalJem9Yl3QBZmPOKN68T-metadGFzdHktYnVyZ2VyLWlzb2xhdGVkLXdoaXRlLWJhY2tncm91bmQtZnJlc2gtaGFtYnVyZ2VyLWZhc3Rmb29kLXdpdGgtYmVlZi1jaGVlc2UuanBn-.jpg'
        ]);
    }
}
