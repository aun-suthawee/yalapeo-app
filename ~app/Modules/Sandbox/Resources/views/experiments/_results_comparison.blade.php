{{-- Results Charts & Comparison --}}
<div class="row g-4 mb-4">
    <!-- Overall Score Comparison Chart -->
    <div class="col-md-6">
        <div class="card" style="border: none; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <div class="card-body" style="padding: 24px;">
                <h6 style="font-weight: 600; margin-bottom: 20px; color: #2d3748;">
                    <i class="fas fa-chart-bar me-2" style="color: #667eea;"></i>Overall Score Comparison
                </h6>
                <canvas id="overallScoreChart" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Improvement Percentage Chart -->
    <div class="col-md-6">
        <div class="card" style="border: none; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <div class="card-body" style="padding: 24px;">
                <h6 style="font-weight: 600; margin-bottom: 20px; color: #2d3748;">
                    <i class="fas fa-chart-line me-2" style="color: #10b981;"></i>Improvement Percentage
                </h6>
                <canvas id="improvementChart" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- NT Subject Comparison -->
    <div class="col-md-6">
        <div class="card" style="border: none; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <div class="card-body" style="padding: 24px;">
                <h6 style="font-weight: 600; margin-bottom: 20px; color: #2d3748;">
                    <i class="fas fa-chart-radar me-2" style="color: #f59e0b;"></i>NT Subject Comparison
                </h6>
                <canvas id="ntSubjectChart" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </div>

    <!-- O-NET Subject Comparison -->
    <div class="col-md-6">
        <div class="card" style="border: none; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <div class="card-body" style="padding: 24px;">
                <h6 style="font-weight: 600; margin-bottom: 20px; color: #2d3748;">
                    <i class="fas fa-chart-column me-2" style="color: #ef4444;"></i>O-NET Subject Comparison
                </h6>
                <canvas id="onetSubjectChart" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Comparison Table -->
<div class="card" style="border: none; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 24px;">
    <div class="card-body" style="padding: 24px;">
        <h6 style="font-weight: 600; margin-bottom: 20px; color: #2d3748;">
            <i class="fas fa-table me-2" style="color: #667eea;"></i>Detailed Comparison
        </h6>
        <div class="table-responsive">
            <table class="table table-hover" style="margin-bottom: 0;">
                <thead style="background: #f7fafc; border-bottom: 2px solid #e2e8f0;">
                    <tr>
                        <th style="padding: 12px; font-weight: 600; color: #4a5568;">Scenario</th>
                        <th style="padding: 12px; font-weight: 600; color: #4a5568; text-align: center;">NT Math</th>
                        <th style="padding: 12px; font-weight: 600; color: #4a5568; text-align: center;">NT Thai</th>
                        <th style="padding: 12px; font-weight: 600; color: #4a5568; text-align: center;">RT Reading</th>
                        <th style="padding: 12px; font-weight: 600; color: #4a5568; text-align: center;">O-NET Math</th>
                        <th style="padding: 12px; font-weight: 600; color: #4a5568; text-align: center;">O-NET Thai</th>
                        <th style="padding: 12px; font-weight: 600; color: #4a5568; text-align: center;">O-NET Eng</th>
                        <th style="padding: 12px; font-weight: 600; color: #4a5568; text-align: center;">O-NET Sci</th>
                        <th style="padding: 12px; font-weight: 600; color: #4a5568; text-align: center;">Average</th>
                        <th style="padding: 12px; font-weight: 600; color: #4a5568; text-align: center;">Improvement</th>
                    </tr>
                </thead>
                <tbody id="comparisonTableBody">
                    @foreach($experiment->scenarios as $scenario)
                        @php
                            $result = $scenario->results ? $scenario->results->first() : null;
                            $metrics = $result ? $result->metrics : null;
                            $isBaseline = $scenario->scenario_number == 0;
                        @endphp
                        <tr>
                            <td style="padding: 12px;">
                                <strong>{{ $scenario->name }}</strong>
                                @if($isBaseline)
                                    <span class="badge" style="background: #e0e7ff; color: #5a67d8; font-size: 11px; margin-left: 8px;">Baseline</span>
                                @endif
                            </td>
                            @if($metrics)
                                <td style="padding: 12px; text-align: center;">
                                    <span class="score-cell">{{ number_format($metrics['nt_math_avg'] ?? 0, 2) }}</span>
                                </td>
                                <td style="padding: 12px; text-align: center;">
                                    <span class="score-cell">{{ number_format($metrics['nt_thai_avg'] ?? 0, 2) }}</span>
                                </td>
                                <td style="padding: 12px; text-align: center;">
                                    <span class="score-cell">{{ number_format($metrics['rt_reading_avg'] ?? 0, 2) }}</span>
                                </td>
                                <td style="padding: 12px; text-align: center;">
                                    <span class="score-cell">{{ number_format($metrics['onet_math_avg'] ?? 0, 2) }}</span>
                                </td>
                                <td style="padding: 12px; text-align: center;">
                                    <span class="score-cell">{{ number_format($metrics['onet_thai_avg'] ?? 0, 2) }}</span>
                                </td>
                                <td style="padding: 12px; text-align: center;">
                                    <span class="score-cell">{{ number_format($metrics['onet_eng_avg'] ?? 0, 2) }}</span>
                                </td>
                                <td style="padding: 12px; text-align: center;">
                                    <span class="score-cell">{{ number_format($metrics['onet_sci_avg'] ?? 0, 2) }}</span>
                                </td>
                                <td style="padding: 12px; text-align: center;">
                                    <strong class="score-cell">{{ number_format($metrics['avg_score'] ?? 0, 2) }}</strong>
                                </td>
                                <td style="padding: 12px; text-align: center;">
                                    @php
                                        $improvement = $metrics['improvement_percent'] ?? 0;
                                        $color = $improvement > 0 ? '#10b981' : ($improvement < 0 ? '#ef4444' : '#6b7280');
                                        $icon = $improvement > 0 ? 'fa-arrow-up' : ($improvement < 0 ? 'fa-arrow-down' : 'fa-minus');
                                    @endphp
                                    <span style="color: {{ $color }}; font-weight: 600;">
                                        <i class="fas {{ $icon }} me-1"></i>{{ number_format($improvement, 2) }}%
                                    </span>
                                </td>
                            @else
                                <td colspan="8" style="padding: 12px; text-align: center; color: #9ca3af;">
                                    <em>Not calculated yet</em>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Key Insights -->
<div class="card" style="border: none; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="card-body" style="padding: 24px; color: white;">
        <h6 style="font-weight: 600; margin-bottom: 16px;">
            <i class="fas fa-lightbulb me-2"></i>Key Insights
        </h6>
        <div id="keyInsights">
            @php
                $bestScenario = $experiment->scenarios
                    ->filter(fn($s) => $s->results && $s->results->isNotEmpty())
                    ->sortByDesc(fn($s) => $s->results->first()->metrics['avg_score'] ?? 0)
                    ->first();
            @endphp
            @if($bestScenario)
                <p style="margin-bottom: 8px; line-height: 1.6;">
                    <strong>Best Performing Scenario:</strong> {{ $bestScenario->name }} 
                    (Avg Score: {{ number_format($bestScenario->results->first()->metrics['avg_score'] ?? 0, 2) }})
                </p>
                <p style="margin-bottom: 0; line-height: 1.6;">
                    <strong>Overall Trend:</strong> 
                    @php
                        $avgImprovement = $experiment->scenarios
                            ->filter(fn($s) => $s->results && $s->results->isNotEmpty() && $s->scenario_number > 0)
                            ->avg(fn($s) => $s->results->first()->metrics['improvement_percent'] ?? 0);
                    @endphp
                    @if($avgImprovement > 0)
                        Positive improvement across scenarios with an average increase of {{ number_format($avgImprovement, 2) }}%.
                    @elseif($avgImprovement < 0)
                        Some scenarios show decline with an average of {{ number_format($avgImprovement, 2) }}% change.
                    @else
                        Scenarios show mixed results with minimal overall change.
                    @endif
                </p>
            @else
                <p style="margin-bottom: 0;">Run scenarios to see key insights and recommendations.</p>
            @endif
        </div>
    </div>
</div>

<style>
.score-cell {
    display: inline-block;
    padding: 4px 8px;
    background: #f7fafc;
    border-radius: 4px;
    font-weight: 500;
    color: #2d3748;
}

.table-hover tbody tr:hover {
    background-color: #f9fafb !important;
}
</style>

<script>
// Chart.js Configuration
document.addEventListener('DOMContentLoaded', function() {
    // Fetch comparison data from API
    fetch('{{ route('sandbox.experiments.comparison-data', $experiment) }}')
        .then(response => response.json())
        .then(data => {
            initializeCharts(data);
        })
        .catch(error => {
            console.error('Error fetching comparison data:', error);
        });
});

function initializeCharts(data) {
    // Overall Score Comparison Chart
    const overallCtx = document.getElementById('overallScoreChart').getContext('2d');
    new Chart(overallCtx, {
        type: 'bar',
        data: {
            labels: data.labels,
            datasets: [{
                label: 'Average Score',
                data: data.datasets.find(d => d.key === 'avg_score').data,
                backgroundColor: 'rgba(102, 126, 234, 0.8)',
                borderColor: 'rgba(102, 126, 234, 1)',
                borderWidth: 2,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Score: ' + context.parsed.y.toFixed(2);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: { font: { size: 12 } }
                },
                x: { ticks: { font: { size: 11 } } }
            }
        }
    });

    // Improvement Percentage Chart
    const improvementCtx = document.getElementById('improvementChart').getContext('2d');
    new Chart(improvementCtx, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [{
                label: 'Improvement %',
                data: data.datasets.find(d => d.key === 'improvement_percent').data,
                borderColor: 'rgba(16, 185, 129, 1)',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: 'rgba(16, 185, 129, 1)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Improvement: ' + context.parsed.y.toFixed(2) + '%';
                        }
                    }
                }
            },
            scales: {
                y: { ticks: { font: { size: 12 } } },
                x: { ticks: { font: { size: 11 } } }
            }
        }
    });

    // NT Subject Comparison (Radar)
    const ntCtx = document.getElementById('ntSubjectChart').getContext('2d');
    const ntMath = data.datasets.find(d => d.key === 'nt_math_avg');
    const ntThai = data.datasets.find(d => d.key === 'nt_thai_avg');
    
    new Chart(ntCtx, {
        type: 'radar',
        data: {
            labels: data.labels,
            datasets: [
                {
                    label: 'NT Math',
                    data: ntMath.data,
                    borderColor: 'rgba(239, 68, 68, 1)',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    borderWidth: 2
                },
                {
                    label: 'NT Thai',
                    data: ntThai.data,
                    borderColor: 'rgba(59, 130, 246, 1)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                r: {
                    beginAtZero: true,
                    max: 100,
                    ticks: { font: { size: 10 } }
                }
            },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { font: { size: 12 } }
                }
            }
        }
    });

    // O-NET Subject Comparison (Grouped Bar)
    const onetCtx = document.getElementById('onetSubjectChart').getContext('2d');
    const onetMath = data.datasets.find(d => d.key === 'onet_math_avg');
    const onetThai = data.datasets.find(d => d.key === 'onet_thai_avg');
    const onetEng = data.datasets.find(d => d.key === 'onet_eng_avg');
    const onetSci = data.datasets.find(d => d.key === 'onet_sci_avg');

    new Chart(onetCtx, {
        type: 'bar',
        data: {
            labels: data.labels,
            datasets: [
                {
                    label: 'Math',
                    data: onetMath.data,
                    backgroundColor: 'rgba(239, 68, 68, 0.8)',
                    borderRadius: 6
                },
                {
                    label: 'Thai',
                    data: onetThai.data,
                    backgroundColor: 'rgba(59, 130, 246, 0.8)',
                    borderRadius: 6
                },
                {
                    label: 'English',
                    data: onetEng.data,
                    backgroundColor: 'rgba(16, 185, 129, 0.8)',
                    borderRadius: 6
                },
                {
                    label: 'Science',
                    data: onetSci.data,
                    backgroundColor: 'rgba(245, 158, 11, 0.8)',
                    borderRadius: 6
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { font: { size: 12 } }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: { font: { size: 12 } }
                },
                x: { ticks: { font: { size: 10 } } }
            }
        }
    });
}
</script>
