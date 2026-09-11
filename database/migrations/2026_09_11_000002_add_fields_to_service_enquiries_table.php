<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_enquiries', function (Blueprint $table) {
            $table->string('postcode')->nullable()->after('address');
            $table->string('gp')->nullable()->after('postcode');
            $table->text('medications')->nullable()->after('gp');
            $table->string('delivery')->nullable()->after('medications');
            $table->string('nhs_number')->nullable()->after('delivery');
            $table->string('duration')->nullable()->after('nhs_number');
            $table->text('symptoms')->nullable()->after('duration');
        });
    }

    public function down(): void
    {
        Schema::table('service_enquiries', function (Blueprint $table) {
            $table->dropColumn(['postcode', 'gp', 'medications', 'delivery', 'nhs_number', 'duration', 'symptoms']);
        });
    }
};
