<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('source_user_id')->index('source_user');
            $table->foreign('source_user_id')->references('id')->on('users');
            $table->unsignedBigInteger('target_user_id')->nullable(true)->index('target_user');
            $table->foreign('target_user_id')->references('id')->on('users');
            $table->unsignedInteger('amount')->comment('Operation amount in major units');
            $table->integer('source_new_balance')->comment('Source user balance after operation');
            $table->integer('target_new_balance')->nullable(true)->comment('Target user balance after operation');
            $table->timestamps();
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('operations');
    }
}
