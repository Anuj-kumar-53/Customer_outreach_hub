<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Phase 4: store campaign category as a string (dropdown values)
     * instead of a foreign key to categories table.
     */
    public function up(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            if (Schema::hasColumn('campaigns', 'category_id')) {
                $table->dropForeign(['category_id']);
                $table->dropColumn('category_id');
            }
        });

        if (! Schema::hasColumn('campaigns', 'category')) {
            Schema::table('campaigns', function (Blueprint $table) {
                $table->string('category', 100);
            });
        }
    }

    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            if (Schema::hasColumn('campaigns', 'category')) {
                $table->dropColumn('category');
            }
        });

        if (! Schema::hasColumn('campaigns', 'category_id')) {
            Schema::table('campaigns', function (Blueprint $table) {
                $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            });
        }
    }
};
