<?php

use Database\Seeders\StorageLocationSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('storage_locations', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
        });

        Artisan::call('db:seed', [
            '--class' => StorageLocationSeeder::class,
            '--force' => true,
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('storage_locations');
    }
};
