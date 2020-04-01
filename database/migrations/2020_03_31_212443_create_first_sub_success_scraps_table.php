<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFirstSubSuccessScrapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('first_sub_success_scraps', function (Blueprint $table) {
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
        Schema::dropIfExists('first_sub_success_scraps');
    }
}
