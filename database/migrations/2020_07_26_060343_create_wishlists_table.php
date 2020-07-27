<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWishlistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Contain all the wishilists
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');

            $table->string('name', '150');
        });

        // Contains all the wishlist items
        Schema::create('wishlists_items', function(Blueprint $table){
            $table->id();
            $table->timestamps();

            $table->foreignId('wishlist_id');
            $table->foreign('wishlist_id')->references('id')->on('wishlists')->onDelete('CASCADE')->onUpdate('CASCADE');

            $table->string('name', 150);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wishlists_items');
        Schema::dropIfExists('wishlists');
    }
}
