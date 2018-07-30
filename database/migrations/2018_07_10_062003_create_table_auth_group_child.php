<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAuthGroupChild extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auth_group_child', function (Blueprint $table) {
            $table->integer('group_id');
            $table->string('child', 64);
            $table->tinyInteger('type')->comment('1:user_id 2:item_name');

            $table->primary(['group_id', 'child', 'type']);
            $table->index('child', 'inx_child');
            $table->index('type', 'inx_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auth_group_child');
    }
}
