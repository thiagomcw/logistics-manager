<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedInteger('storage_location_id')->nullable();
            $table->string('description');
            $table->enum('status', ['stored', 'delivering', 'delivered']);
            $table->text('delivery_address');
            $table->date('delivery_date');

            $table
                ->foreign('storage_location_id', 'packages_storage_location_id')
                ->references('id')
                ->on('storage_locations');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('packages');
    }
};
