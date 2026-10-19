<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Optional, per-event notification preferences. Opt-out model: every
     * channel is on by default, and the user may disable any of them from
     * their profile. These govern notifications sent to the report owner.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('notify_on_received')->default(true)->after('active');
            $table->boolean('notify_on_status_change')->default(true)->after('notify_on_received');
            $table->boolean('notify_on_closed')->default(true)->after('notify_on_status_change');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['notify_on_received', 'notify_on_status_change', 'notify_on_closed']);
        });
    }
};
