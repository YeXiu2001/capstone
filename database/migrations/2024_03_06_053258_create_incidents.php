<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->string('reporter');
            $table->string('contact');
            $table->string('address');
            $table->string('lat');
            $table->string('long');
            $table->unsignedBigInteger('incident');
            $table->string('eventdesc')->nullable();
            $table->string('imagedir')->nullable();
            $table->enum('status', ['pending', 'ongoing', 'resolved', 'dismissed'])->default('pending');
            $table->timestamps();

            $table->foreign('incident')->references('id')->on('case_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
