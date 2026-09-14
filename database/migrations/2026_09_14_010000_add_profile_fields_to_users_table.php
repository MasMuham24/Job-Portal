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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('role');
            $table->string('gender')->nullable()->after('phone');
            $table->date('birth_date')->nullable()->after('gender');
            $table->text('address')->nullable()->after('birth_date');
            $table->string('city')->nullable()->after('address');
            $table->string('education')->nullable()->after('city');
            $table->string('school')->nullable()->after('education');
            $table->text('skills')->nullable()->after('school');
            $table->text('experience')->nullable()->after('skills');
            $table->text('bio')->nullable()->after('experience');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'gender',
                'birth_date',
                'address',
                'city',
                'education',
                'school',
                'skills',
                'experience',
                'bio',
            ]);
        });
    }
};
