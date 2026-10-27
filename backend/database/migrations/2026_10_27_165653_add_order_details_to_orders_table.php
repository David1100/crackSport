<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shirt_fabric')->nullable();
            $table->string('short_fabric')->nullable();
            $table->string('sock_quality')->nullable();
            $table->string('collar_type')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['shirt_fabric', 'short_fabric', 'sock_quality', 'collar_type']);
        });
    }
};