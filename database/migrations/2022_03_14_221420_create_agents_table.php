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
        Schema::create('agents', function (Blueprint $table) {
            $table->increments('agent_id');
            
            $table->unsignedBigInteger('user_id')->constrained()->onDelete('cascade');
            $table->foreign('user_id')
            ->references('user_id')
            ->on('users')
            ->onDelete('cascade');

            $table->string('agency')->unique();
            $table->string('logo');
            $table->string('phone_no1')->unique();
            $table->string('phone_no2')->nullable();

            $table->longText('description');	
            $table->mediumText('address');	
            $table->boolean('verified')->default(false);
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
        Schema::dropIfExists('agents');
    }
};




























// $table->string('website')->nullable();
// $table->string('facebook')->nullable();
// $table->string('linkedin')->nullable();
// $table->string('twitter')->nullable();
