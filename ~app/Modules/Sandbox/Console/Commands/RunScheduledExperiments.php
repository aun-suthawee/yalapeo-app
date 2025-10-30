<?php

namespace Modules\Sandbox\Console\Commands;

use Illuminate\Console\Command;
use Modules\Sandbox\Entities\Experiment;
use Modules\Sandbox\Services\AnalyticsService;
use Modules\Sandbox\Entities\ExperimentResult;
use Illuminate\Support\Facades\DB;

class RunScheduledExperiments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sandbox:run-scheduled-experiments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run scheduled experiments that are due to execute';

    protected $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        parent::__construct();
        $this->analyticsService = $analyticsService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Checking for scheduled experiments...');

        // Find experiments with status 'scheduled' and scheduled_at <= now
        $experiments = Experiment::where('status', 'scheduled')
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '<=', now())
            ->with(['scenarios'])
            ->get();

        if ($experiments->isEmpty()) {
            $this->info('No scheduled experiments found.');
            return 0;
        }

        $this->info("Found {$experiments->count()} experiment(s) to run.");

        foreach ($experiments as $experiment) {
            $this->info("Running experiment: {$experiment->name} (ID: {$experiment->id})");

            DB::beginTransaction();
            try {
                $resultsCount = 0;

                foreach ($experiment->scenarios as $scenario) {
                    $results = $this->analyticsService->runScenarioCalculations(
                        $experiment->base_data_snapshot,
                        $scenario->parameters
                    );

                    ExperimentResult::updateOrCreate(
                        [
                            'experiment_id' => $experiment->id,
                            'scenario_id' => $scenario->id,
                        ],
                        [
                            'metrics' => $results,
                            'calculated_at' => now(),
                        ]
                    );

                    $resultsCount++;
                }

                // Update experiment status
                $experiment->update([
                    'status' => 'completed',
                    'completed_at' => now(),
                ]);

                DB::commit();

                $this->info("✅ Completed {$resultsCount} scenario(s) for experiment {$experiment->id}");

            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("❌ Error running experiment {$experiment->id}: " . $e->getMessage());
            }
        }

        $this->info('Scheduled experiments processing complete.');
        return 0;
    }
}
