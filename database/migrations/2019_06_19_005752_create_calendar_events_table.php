<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalendarEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->unsignedTinyInteger('calendar_id');
			$table->unsignedBigInteger('user_id');
            $table->string('title');
			$table->string('description')->nullable();
			$table->string('url')->nullable();
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
			//$table->boolean('is_all_day')->default(false);
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
        Schema::dropIfExists('calendar_events');
    }
}
