<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->text('description');
            $table->longtext('image');  //We will convert the image into a very long nines of txt of base64 format example::dog,png-->hjksdfhsjkldas32432432hk3243242....
            $table->unsignedBigInteger('user_id'); //owner of the post
            $table->timestamps(); //created_at,updated_at

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
