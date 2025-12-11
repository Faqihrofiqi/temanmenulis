<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('institution')->nullable()->after('phone');
            $table->string('study_program')->nullable()->after('institution');
            $table->string('city')->nullable()->after('study_program');
            $table->string('student_id')->nullable()->after('city');
            $table->string('linkedin_url')->nullable()->after('student_id');
            $table->string('profile_verification_status')->default('draft')->after('linkedin_url');
            $table->timestamp('profile_verified_at')->nullable()->after('profile_verification_status');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn([
                'institution',
                'study_program',
                'city',
                'student_id',
                'linkedin_url',
                'profile_verification_status',
                'profile_verified_at',
            ]);
        });
    }
};
