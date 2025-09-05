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
        DB::statement('CREATE EXTENSION IF NOT EXISTS "uuid-ossp";');

        Schema::create('countries', function(Blueprint $table) {
            $table->bigIncrements('country_id')->primary();
            $table->string('iso_code_2', 2)->unique();
            $table->string('iso_code_3', 3)->unique();
            $table->string('phone_code', 10)->unique();
            $table->string('name')->unique();
            $table->string('currency', 10);
            $table->string('continent', 50);
            $table->boolean('is_active')->default(false);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::create('provinces', function(Blueprint $table) {
            $table->bigIncrements('province_id')->primary();
            $table->foreignId('country_id')->references('country_id')->on('countries');
            $table->string('name')->unique();
            $table->string('code', 10)->unique();
            $table->boolean('is_active')->default(false);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::create('counties', function(Blueprint $table) {
            $table->bigIncrements('county_id')->primary();
            $table->foreignId('province_id')->references('province_id')->on('provinces');
            $table->string('name')->unique();
            $table->string('code', 10)->unique();
            $table->boolean('is_active')->default(false);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        // Schema::create('cities', function(Blueprint $table) {
        //     $table->bigIncrements('city_id')->primary();
        //     $table->foreignId('county_id')->references('county_id')->on('counties');
        //     $table->string('name')->unique();
        //     $table->string('code', 10)->unique();
        //     $table->boolean('is_active')->default(false);
        //     $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        //     $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        // });

        Schema::create('wards', function(Blueprint $table) {
            $table->bigIncrements('ward_id')->primary();
            $table->foreignId('county_id')->references('county_id')->on('counties');
            $table->string('name')->unique();
            $table->string('code', 10)->unique();
            $table->boolean('is_active')->default(false);
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
            'countries',
            'provinces',
            'counties',
            // 'cities',
            'wards'
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
            'countries',
            'provinces',
            'counties',
            // 'cities',
            'wards'
        ];

        foreach($tables as $table) {
            // Drop Trigger & Function First
            DB::unprepared("DROP TRIGGER IF EXISTS set_updated_at ON {$table};");
            DB::unprepared("DROP FUNCTION IF EXISTS update_updated_at_column();");

            Schema::dropIfExists($table);
        }
    }
};
