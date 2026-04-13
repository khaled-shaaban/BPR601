<?php

use App\Enums\ReportStatus;
use App\Enums\ReportPriority;
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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('authority_id')->constrained('authorities');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('status')->default(ReportStatus::PENDING->value);
            $table->string('priority')->default(ReportPriority::LOW->value);
            $table->string('title');
            $table->text('description');
            $table->string('type');
            $table->dateTime('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
