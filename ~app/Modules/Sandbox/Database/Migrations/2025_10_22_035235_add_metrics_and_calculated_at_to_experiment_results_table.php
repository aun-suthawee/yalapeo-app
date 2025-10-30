<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddMetricsAndCalculatedAtToExperimentResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('experiment_results', function (Blueprint $table) {
            // Add new columns for storing all metrics in JSON format
            $table->json('metrics')->nullable()->after('scenario_id');
            $table->timestamp('calculated_at')->nullable()->after('metrics');
        });
        
        // Make metric_name nullable using raw SQL
        DB::statement('ALTER TABLE `experiment_results` MODIFY `metric_name` VARCHAR(255) NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('experiment_results', function (Blueprint $table) {
            $table->dropColumn(['metrics', 'calculated_at']);
        });
        
        // Restore metric_name to NOT NULL
        DB::statement('ALTER TABLE `experiment_results` MODIFY `metric_name` VARCHAR(255) NOT NULL');
    }
}
