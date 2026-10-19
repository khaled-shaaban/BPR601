<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Coverage radius (in metres) around the governorate centre — drawn as a
     * circle on the map picker.
     */
    public function up(): void
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->unsignedInteger('radius')->nullable()->after('longitude');
        });
    }

    public function down(): void
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->dropColumn('radius');
        });
    }
};
