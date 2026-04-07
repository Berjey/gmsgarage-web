<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('brand')->nullable()->change();
            $table->string('model')->nullable()->change();
            $table->integer('year')->nullable()->change();
            $table->decimal('price', 12, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('brand')->nullable(false)->change();
            $table->string('model')->nullable(false)->change();
            $table->integer('year')->nullable(false)->change();
            $table->decimal('price', 12, 2)->nullable(false)->change();
        });
    }
};
