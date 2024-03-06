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
            $table->decimal('lat');
            $table->decimal('long');
            $table->unsignedBigInteger('incident');
            $table->string('eventdesc');
            $table->string('imagedir');
            $table->enum('status', ['pending', 'ongoing', 'resolved', 'dismissed'])->default('pending');
            $table->unsignedBigInteger('admin_handler');
            $table->unsignedBigInteger('deployed_rt');
            $table->timestamps();

            $table->foreign('incident')->references('id')->on('case_types');
            $table->foreign('admin_handler')->references('id')->on('users');
            $table->foreign('deployed_rt')->references('id')->on('response_teams');
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
