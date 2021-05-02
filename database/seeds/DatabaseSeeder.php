<?php

use App\User;
use App\Product;
use App\Category;
use App\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        //our tables have foreign keys between them,so we need to desable temporally onlyu for the DatabaseSeeder the foreign key checks.

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        // $this->call(UsersTableSeeder::class);

        // to be sure that every time the seeding or the DatabaseSeeder is executed, we restart the database to the original status, we are going to empty all the tables and we can do that by using the truncate method in every one of our models or tables.
        User::truncate();
        Category::truncate();
        Product::truncate();
        Transaction::truncate();
        DB::table('category_product')->truncate(); //pivot table

        //seed中はmail転送を中断する
        User::flushEventListeners();
        Category::flushEventListeners();
        Product::flushEventListeners();
        Transaction::flushEventListeners();

        // defined how much eusers , categories , products & transactions we are going to create,
        $usersQuantity = 1000;
        $categoriesQuantity = 30;
        $productsQuantity = 1000;
        $transactionsQuantity = 100;

        factory(User::class, $usersQuantity)->create();
        factory(Category::class, $categoriesQuantity)->create();
        factory(Product::class, $productsQuantity)->create()->each(
            function ($product) {
                //we only need id field on category table
                $categories = Category::all()->random(mt_rand(1, 5))->pluck('id');
                $product->categories()->attach($categories);
            }
        );
        factory(Transaction::class, $transactionsQuantity)->create();
    }
}
