<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //purpose
        Schema::create('action_purposes', function(Blueprint $table) {
            $table->bigIncrements('purpose_id')->primary();
            $table->text('description');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::create('booking_types', function(Blueprint $table) {
            $table->bigIncrements('booking_type')->primary();
            $table->string('name');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::create('packages', function(Blueprint $table) {
            $table->bigIncrements('pkg_id')->primary();
            $table->foreignId('booking_type')->references('booking_type')->on('booking_types');
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });




        // Create Trigger Function
        DB::unprepared('
            CREATE OR REPLACE FUNCTION update_updated_at_column()
            RETURNS TRIGGER AS $$
            BEGIN
                NEW.updated_at = NOW();
                RETURN NEW;
            END;
            $$ language \'plpgsql\';
        ');

        $tables = [
            'action_purposes',
            'booking_types',
            'packages',
         ];

        foreach ($tables as $table) {
            DB::unprepared("
                CREATE TRIGGER set_updated_at_{$table}
                BEFORE UPDATE ON {$table}
                FOR EACH ROW
                EXECUTE FUNCTION update_updated_at_column();
            ");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'action_purposes',
            'booking_types',
            'packages',
        ];

        foreach($tables as $table) {
            // Drop Trigger & Function First
            DB::unprepared("DROP TRIGGER IF EXISTS set_updated_at ON {$table};");
            DB::unprepared("DROP FUNCTION IF EXISTS update_updated_at_column();");

            Schema::dropIfExists($table);
        }
    }
};
