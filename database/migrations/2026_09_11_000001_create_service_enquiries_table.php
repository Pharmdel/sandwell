<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('service');          // catalogue slug
            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('detail')->nullable();     // the service-specific answer
            $table->string('best_time')->nullable();
            $table->date('dob')->nullable();
            $table->string('region')->nullable();
            $table->date('travel_date')->nullable();
            $table->string('address')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['service', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_enquiries');
    }
};
