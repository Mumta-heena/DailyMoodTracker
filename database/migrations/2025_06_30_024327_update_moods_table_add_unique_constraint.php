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
        Schema::table('moods', function (Blueprint $table) {
            // Drop the old unique constraint if it exists (in case it was on entry_date only)
            // You might need to check your previous migration to confirm the name
            $table->dropUnique('moods_entry_date_unique');
            
            // Add the new composite unique constraint
            $table->unique(['user_id', 'entry_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('moods', function (Blueprint $table) {
            // Drop the composite unique constraint
            $table->dropUnique(['user_id', 'entry_date']);
            
            // Re-add the old constraint if you want to revert
            // $table->unique('entry_date');
        });
    }
};