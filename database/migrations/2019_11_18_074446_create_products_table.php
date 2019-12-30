<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('label')->nullable();
            $table->string('slug')->nullable();
            $table->integer('category_id')->nullable();
            $table->text('description')->nullable();
            $table->integer('in_stock')->nullable();
            $table->integer('track_stock')->nullable();
            $table->integer('qty')->nullable();
            $table->tinyInteger('is_taxable')->nullable();
            $table->decimal('price', 10, 6)->nullable();
            $table->decimal('cost_price', 10, 6)->nullable();
            $table->string('color')->nullable();
            $table->double('weight', 8, 2)->nullable();
            $table->double('width', 8, 2)->nullable();
            $table->double('height', 8, 2)->nullable();
            $table->double('length', 8, 2)->nullable();
            $table->text('featured_image')->nullable();
            $table->text('size')->nullable();
            $table->tinyInteger('is_featured')->default(0);
            $table->enum('status', ['Enabled', 'Disabled'])->default('Enabled');
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
}
