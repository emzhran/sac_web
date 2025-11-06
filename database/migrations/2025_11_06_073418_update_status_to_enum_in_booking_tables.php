<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = ['futsals', 'baskets', 'volis', 'badmintons'];
        $enum_values = ['pending', 'approved', 'rejected'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) use ($enum_values) {
                $table->string('status_temp')->after('status')->nullable(); 
            });

            DB::statement("UPDATE `{$table}` SET `status_temp` = `status`");

            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn('status');
            });

            Schema::table($table, function (Blueprint $table) use ($enum_values) {
                $table->enum('status', $enum_values)
                      ->after('status_temp')
                      ->default($enum_values[0]); 
            });

            DB::statement("UPDATE `{$table}` SET `status` = CASE 
                            WHEN `status_temp` = 'accepted' THEN 'approved'
                            WHEN `status_temp` = 'pending' THEN 'pending'
                            WHEN `status_temp` = 'rejected' THEN 'rejected'
                            ELSE 'pending' 
                          END");

            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn('status_temp');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['futsals', 'baskets', 'volis', 'badmintons'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->string('status_temp')->after('status')->nullable();
            });

            DB::statement("UPDATE `{$table}` SET `status_temp` = `status`");

            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn('status');
            });
            
            Schema::table($table, function (Blueprint $table) {
                $table->string('status')->after('status_temp')->default('pending'); 
            });

            DB::statement("UPDATE `{$table}` SET `status` = `status_temp`");

            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn('status_temp');
            });
        }
    }
};