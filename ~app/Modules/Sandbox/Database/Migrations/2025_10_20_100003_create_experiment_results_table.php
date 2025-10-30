<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExperimentResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('experiment_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('experiment_id')->constrained('experiments')->onDelete('cascade');
            $table->foreignId('scenario_id')->nullable()->constrained('experiment_scenarios')->onDelete('cascade');
            
            // Result data
            $table->string('metric_name'); // e.g., "average_score", "teacher_student_ratio", "budget_efficiency"
            $table->string('metric_type')->default('numeric'); // numeric, percentage, ratio
            $table->decimal('base_value', 10, 2)->nullable(); // Original value
            $table->decimal('predicted_value', 10, 2)->nullable(); // Predicted value
            $table->decimal('change_value', 10, 2)->nullable(); // Difference
            $table->decimal('change_percentage', 10, 2)->nullable(); // % change
            
            // Grouping (for aggregated results)
            $table->string('group_by')->nullable(); // e.g., "department", "district", "school_type"
            $table->string('group_value')->nullable(); // e.g., "สพป.ยะลา เขต 1"
            
            // Metadata
            $table->json('metadata')->nullable(); // Additional details
            
            $table->timestamps();
            
            $table->index(['experiment_id', 'scenario_id', 'metric_name']);
            $table->index(['group_by', 'group_value']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('experiment_results');
    }
}
