<?php

use App\Enums\ProductStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->foreignId('category_id')->constrained();
            $table->string('title');
            $table->longText('content');
            $table->longText('description');
            $table->string('image');
            $table->string('slug')->unique();
            $table->bigInteger('quantity');
            $table->bigInteger('sold')->default(0);
            $table->double('retail_price');
            $table->double('original_price');
            $table->double('avg_rate')->default(0);
            $table->tinyInteger('status')->unsigned()->default(ProductStatus::in_stock);
            $table->timestamps();
            $table->softDeletes();
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
