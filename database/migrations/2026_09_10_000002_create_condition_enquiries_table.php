<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('condition_enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('condition', 120);
            $table->string('name', 120);
            $table->date('dob')->nullable();
            $table->string('phone', 40);
            $table->string('email', 180)->nullable();
            $table->text('symptoms')->nullable();
            $table->json('answers')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('condition_enquiries');
    }
};
