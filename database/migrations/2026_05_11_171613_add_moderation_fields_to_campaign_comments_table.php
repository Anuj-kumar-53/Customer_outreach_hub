<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campaign_comments', function (Blueprint $table) {
            $table->string('moderation_status', 32)->default('approved')->after('comment');
            $table->unsignedTinyInteger('spam_score')->default(0)->after('moderation_status');
            $table->timestamp('moderated_at')->nullable()->after('spam_score');
            $table->foreignId('moderated_by')->nullable()->after('moderated_at')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('campaign_comments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('moderated_by');
            $table->dropColumn(['moderation_status', 'spam_score', 'moderated_at']);
        });
    }
};
