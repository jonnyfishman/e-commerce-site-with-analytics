<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id('id');
            $table->timestamps();

            $table->mediumText('name');
            $table->double('price', 5 , 2);
            $table->longText('description');
            $table->mediumText('colour');
            $table->mediumText('image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $product_files = Storage::disk('public')->files( '/product_files/' );
        echo PHP_EOL.'Deleteing '.count($product_files).' images...'.PHP_EOL;
        Storage::delete($product_files);

        Schema::dropIfExists('products');
    }
};
