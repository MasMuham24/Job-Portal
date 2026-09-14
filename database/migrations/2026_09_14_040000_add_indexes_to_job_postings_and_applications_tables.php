<?php

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
        Schema::table('job_postings', function (Blueprint $table) {
            $table->index('status');
            $table->index('location');
            $table->index('employment_type');
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['location']);
            $table->dropIndex(['employment_type']);
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });
    }
};
