<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempCurrentUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_current_users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable()->default(0); // One to One relation with user
            $table->bigInteger('submission_id')->nullable()->default(0); // One to One relation with submission
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
        Schema::dropIfExists('temp_current_users');
    }
}
