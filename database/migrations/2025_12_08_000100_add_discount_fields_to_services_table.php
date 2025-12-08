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
        Schema::table('services', function (Blueprint $table): void {
            $table->decimal('discount_percentage', 5, 2)->nullable()->after('price');
            $table->string('discount_label')->nullable()->after('discount_percentage');
            $table->timestamp('discount_ends_at')->nullable()->after('discount_label');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table): void {
            $table->dropColumn(['discount_percentage', 'discount_label', 'discount_ends_at']);
        });
    }
};
