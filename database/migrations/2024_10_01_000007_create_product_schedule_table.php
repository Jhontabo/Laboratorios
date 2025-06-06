<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('product_schedule', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('schedule_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['product_id', 'schedule_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_schedule');
    }
};
