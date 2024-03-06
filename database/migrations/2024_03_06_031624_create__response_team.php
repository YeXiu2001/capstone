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
        Schema::create('response_teams', function (Blueprint $table) {
            $table->id();
            $table->string('team_name');
            $table->unsignedBigInteger('members');
            $table->ENUM('status', ['available', 'unavailable', 'busy'])->default('unavailable');
            $table->timestamps();

            $table->foreign('members')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_response_team');
    }
};
