<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExperimentScenariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('experiment_scenarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('experiment_id')->constrained('experiments')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            
            // Scenario parameters (what to change)
            $table->json('parameters'); // e.g., {"teacher_ratio": 1.2, "budget_increase": 10, "training_hours": 40}
            
            // Parameter details
            $table->json('changes')->nullable(); // Detailed changes applied
            
            // Calculated results
            $table->json('results')->nullable(); // Predicted outcomes
            $table->timestamp('calculated_at')->nullable();
            
            $table->timestamps();
            
            $table->index(['experiment_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('experiment_scenarios');
    }
}
