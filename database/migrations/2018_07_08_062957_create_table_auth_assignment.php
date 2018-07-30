<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAuthAssignment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auth_assignment', function (Blueprint $table) {
            $table->string('item_name', 64);
            $table->integer('group_id');
            $table->string('user_id', 64);

            $table->primary(['item_name', 'group_id', 'user_id']);
            $table->index('user_id', 'inx_user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auth_assignment');
    }
}
