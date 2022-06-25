<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->string("name", 55);
            $table->string("reference", 55);
            $table->decimal("price", 10, 2);
            $table->decimal("weight", 10, 2);
            $table->bigInteger("category");
            $table->bigInteger("stock");
            $table->string("image", 255)->nullable();
            $table->enum('status', ['1', '0'])->nullable()->default('0');
            $table->enum('delete', ['1', '0'])->nullable()->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
