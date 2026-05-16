<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
            $table->string('account_status', 32)->default('active')->after('role');
            $table->timestamp('suspended_at')->nullable()->after('account_status');
            $table->timestamp('banned_at')->nullable()->after('suspended_at');
            $table->text('suspension_reason')->nullable()->after('banned_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn([
                'account_status',
                'suspended_at',
                'banned_at',
                'suspension_reason',
            ]);
        });
    }
};
