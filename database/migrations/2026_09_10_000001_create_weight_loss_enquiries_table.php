<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weight_loss_enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('kind', 20); // consultation | switch | waitlist
            $table->string('name', 120);
            $table->string('phone', 40);
            $table->string('email', 180)->nullable();
            $table->string('treatment', 120)->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weight_loss_enquiries');
    }
};
