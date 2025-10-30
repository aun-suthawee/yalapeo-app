<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExperimentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('experiments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('type')->default('what_if'); // what_if, comparison, prediction
            $table->unsignedBigInteger('created_by')->nullable();
            
            // Base data snapshot
            $table->integer('base_year')->default(2025);
            $table->json('base_data_snapshot')->nullable(); // Snapshot of original data
            
            // Experiment settings
            $table->json('settings')->nullable(); // Filter criteria, scope, etc.
            $table->boolean('is_public')->default(false);
            $table->string('share_token')->unique()->nullable();
            
            // Status
            $table->string('status')->default('draft'); // draft, running, completed, archived
            $table->timestamp('completed_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['created_by', 'status']);
            $table->index('share_token');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('experiments');
    }
}
