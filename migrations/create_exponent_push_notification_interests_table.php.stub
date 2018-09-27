<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExponentPushNotificationInterestsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create(config('exponent-push-notifications.interests.database.table_name'), function (Blueprint $table) {
            $table->string('key')->index();
            $table->string('value');

            $table->unique(['key','value']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop(config('exponent-push-notifications.interests.database.table_name'));
    }
}