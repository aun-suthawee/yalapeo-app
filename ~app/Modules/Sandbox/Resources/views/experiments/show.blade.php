@extends('sandbox::layouts.master')

@section('title', $experiment->name . ' - Experiment Results')

@section('stylesheet-content')
<style>
.experiment-results {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 40px 0;
}

.results-header {
    background: white;
    border-radius: 16px;
    padding: 32px;
    margin-bottom: 24px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.experiment-title {
    font-size: 28px;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 8px;
}

.experiment-meta {
    display: flex;
    gap: 24px;
    flex-wrap: wrap;
    color: #718096;
    font-size: 14px;
}

.meta-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    background: #f7fafc;
    border-radius: 8px;
}

.meta-badge i {
    color: #667eea;
}

.chart-grid {
    display: grid;
    gap: 24px;
    margin-bottom: 24px;
}

.chart-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.chart-title {
    font-size: 18px;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.chart-title i {
    color: #667eea;
}

.chart-container {
    position: relative;
    height: 400px;
}

.comparison-table {
    background: white;
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 24px;
}

.table-responsive {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

thead {
    background: linear-gradient(135deg, #667eea, #764ba2);
}

thead th {
    color: white;
    padding: 16px;
    text-align: left;
    font-weight: 600;
    font-size: 14px;
}

tbody td {
    padding: 14px 16px;
    border-bottom: 1px solid #e2e8f0;
    font-size: 14px;
}

tbody tr:hover {
    background: #f7fafc;
}

.score-cell {
    font-weight: 700;
    color: #2d3748;
}

.improvement-positive {
    color: #10b981;
    font-weight: 600;
}

.improvement-negative {
    color: #ef4444;
    font-weight: 600;
}

.improvement-neutral {
    color: #718096;
}

.scenario-badge {
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    background: #f0f4ff;
    color: #667eea;
}

.scenario-badge.baseline {
    background: #d1fae5;
    color: #065f46;
}

.action-buttons {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.btn-export {
    background: white;
    color: #667eea;
    border: 2px solid #667eea;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-export:hover {
    background: #667eea;
    color: white;
}

.btn-share {
    background: #10b981;
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border: none;
}

.btn-share:hover {
    background: #059669;
}

.insights-panel {
    background: white;
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 24px;
}

.insight-item {
    background: #f0fdf4;
    border-left: 4px solid #10b981;
    padding: 16px;
    margin-bottom: 12px;
    border-radius: 8px;
}

.insight-item h5 {
    font-size: 16px;
    font-weight: 700;
    color: #065f46;
    margin-bottom: 8px;
}

.insight-item p {
    font-size: 14px;
    color: #047857;
    margin: 0;
}

.share-link-box {
    background: #f7fafc;
    border: 2px dashed #cbd5e0;
    border-radius: 8px;
    padding: 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    margin-top: 16px;
}

.share-link-box input {
    flex: 1;
    border: none;
    background: transparent;
    font-family: monospace;
    color: #667eea;
}

.btn-copy {
    background: #667eea;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
}

.btn-copy:hover {
    background: #5568d3;
}
</style>
@endsection

@section('content')
<div class="experiment-results">
    <div class="container-fluid" style="max-width: 1400px;">
        <!-- Header -->
        <div class="results-header">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h1 class="experiment-title">{{ $experiment->name }}</h1>
                    <p class="text-muted">{{ $experiment->description }}</p>
                </div>
                <div class="action-buttons">
                    @if(Auth::check() && Auth::id() === $experiment->created_by)
                        <a href="{{ route('sandbox.experiments.edit', $experiment) }}" class="btn-export">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    @endif
                    <button class="btn-export" onclick="window.print()">
                        <i class="fas fa-print"></i> Print
                    </button>
                    <button class="btn-export" onclick="exportToPDF()">
                        <i class="fas fa-file-pdf"></i> Export PDF
                    </button>
                    <button class="btn-export" onclick="exportToExcel()">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </button>
                    <button class="btn-export" onclick="sendViaEmail()">
                        <i class="fas fa-envelope"></i> Send Email
                    </button>
                    <button class="btn-export" onclick="openBatchEmailModal()">
                        <i class="fas fa-paper-plane"></i> Send to Multiple
                    </button>
                    @if($experiment->is_public)
                        <button class="btn-share" onclick="copyShareLink()">
                            <i class="fas fa-share-alt"></i> Share Link
                        </button>
                    @endif
                </div>
            </div>

            <div class="experiment-meta">
                <span class="meta-badge">
                    <i class="fas fa-user"></i>
                    {{ $experiment->creator->name ?? 'Unknown' }}
                </span>
                <span class="meta-badge">
                    <i class="fas fa-calendar"></i>
                    ปีฐาน: {{ $experiment->base_year + 543 }}
                </span>
                <span class="meta-badge">
                    <i class="fas fa-layer-group"></i>
                    {{ $experiment->scenarios->count() }} Scenarios
                </span>
                <span class="meta-badge">
                    <i class="fas fa-clock"></i>
                    {{ $experiment->created_at->diffForHumans() }}
                </span>
                @if($experiment->completed_at)
                    <span class="meta-badge">
                        <i class="fas fa-check-circle" style="color: #10b981;"></i>
                        Completed: {{ $experiment->completed_at->format('d M Y') }}
                    </span>
                @endif
            </div>

            @if($experiment->is_public)
                <div class="share-link-box">
                    <i class="fas fa-link"></i>
                    <input type="text" id="shareLink" value="{{ route('sandbox.experiments.share', $experiment->share_token) }}" readonly>
                    <button class="btn-copy" onclick="copyShareLink()">
                        <i class="fas fa-copy"></i> Copy
                    </button>
                </div>
            @endif
        </div>

        <!-- Charts Section -->
        <div class="chart-grid">
            <!-- Overall Comparison Chart -->
            <div class="chart-card">
                <h3 class="chart-title">
                    <i class="fas fa-chart-bar"></i>
                    Overall Score Comparison
                </h3>
                <div class="chart-container">
                    <canvas id="overallChart"></canvas>
                </div>
            </div>

            <!-- Improvement Percentage Chart -->
            <div class="chart-card">
                <h3 class="chart-title">
                    <i class="fas fa-percentage"></i>
                    Improvement Percentage
                </h3>
                <div class="chart-container">
                    <canvas id="improvementChart"></canvas>
                </div>
            </div>

            <!-- Subject-wise Comparison -->
            <div class="chart-card">
                <h3 class="chart-title">
                    <i class="fas fa-book"></i>
                    Subject-wise Comparison (NT)
                </h3>
                <div class="chart-container">
                    <canvas id="ntChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <h3 class="chart-title">
                    <i class="fas fa-book-reader"></i>
                    Subject-wise Comparison (O-NET)
                </h3>
                <div class="chart-container">
                    <canvas id="onetChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Detailed Comparison Table -->
        <div class="comparison-table">
            <h3 class="chart-title mb-3">
                <i class="fas fa-table"></i>
                Detailed Comparison
            </h3>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Scenario</th>
                            <th>NT Math</th>
                            <th>NT Thai</th>
                            <th>RT Reading</th>
                            <th>O-NET Math</th>
                            <th>O-NET Thai</th>
                            <th>O-NET English</th>
                            <th>Avg Score</th>
                            <th>Improvement</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($comparisonData['scenarios'] as $index => $scenario)
                            @php
                                $datasets = $comparisonData['datasets'];
                                $improvement = $datasets['improvement_percent'][$index] ?? 0;
                            @endphp
                            <tr>
                                <td>
                                    <span class="scenario-badge {{ $index === 0 ? 'baseline' : '' }}">
                                        {{ $scenario['name'] }}
                                    </span>
                                </td>
                                <td class="score-cell">{{ $datasets['nt_math'][$index] ?? 0 }}</td>
                                <td class="score-cell">{{ $datasets['nt_thai'][$index] ?? 0 }}</td>
                                <td class="score-cell">{{ $datasets['rt_reading'][$index] ?? 0 }}</td>
                                <td class="score-cell">{{ $datasets['onet_math'][$index] ?? 0 }}</td>
                                <td class="score-cell">{{ $datasets['onet_thai'][$index] ?? 0 }}</td>
                                <td class="score-cell">{{ $datasets['onet_english'][$index] ?? 0 }}</td>
                                <td class="score-cell">{{ $datasets['avg_score'][$index] ?? 0 }}</td>
                                <td class="{{ $improvement > 0 ? 'improvement-positive' : ($improvement < 0 ? 'improvement-negative' : 'improvement-neutral') }}">
                                    {{ $improvement > 0 ? '+' : '' }}{{ $improvement }}%
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Key Insights -->
        @if($experiment->results->isNotEmpty())
            <div class="insights-panel">
                <h3 class="chart-title mb-3">
                    <i class="fas fa-lightbulb"></i>
                    Key Insights
                </h3>
                @php
                    $maxImprovement = max($comparisonData['datasets']['improvement_percent']);
                    $bestScenarioIndex = array_search($maxImprovement, $comparisonData['datasets']['improvement_percent']);
                    $bestScenario = $comparisonData['scenarios'][$bestScenarioIndex] ?? null;
                @endphp
                
                @if($bestScenario && $maxImprovement > 0)
                    <div class="insight-item">
                        <h5><i class="fas fa-trophy me-2"></i>Best Performing Scenario</h5>
                        <p>
                            <strong>{{ $bestScenario['name'] }}</strong> shows the highest improvement at 
                            <strong>+{{ $maxImprovement }}%</strong> compared to baseline.
                        </p>
                    </div>
                @endif

                <div class="insight-item">
                    <h5><i class="fas fa-chart-line me-2"></i>Overall Trend</h5>
                    <p>
                        Analyzed {{ $experiment->scenarios->count() }} scenarios across multiple parameters. 
                        Results suggest that targeted interventions can lead to measurable improvements in student outcomes.
                    </p>
                </div>
            </div>
        @endif

        <!-- Back Button -->
        <div class="text-center">
            <a href="{{ route('sandbox.experiments.index') }}" class="btn btn-light btn-lg">
                <i class="fas fa-arrow-left me-2"></i>Back to Experiments
            </a>
        </div>
    </div>
</div>
@endsection

@section('script-content')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const comparisonData = @json($comparisonData);

// Chart.js Configuration
Chart.defaults.font.family = '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial';
Chart.defaults.color = '#4a5568';

// Overall Comparison Chart
const overallCtx = document.getElementById('overallChart').getContext('2d');
new Chart(overallCtx, {
    type: 'bar',
    data: {
        labels: comparisonData.labels,
        datasets: [{
            label: 'Average Score',
            data: comparisonData.datasets.avg_score,
            backgroundColor: 'rgba(102, 126, 234, 0.8)',
            borderColor: 'rgba(102, 126, 234, 1)',
            borderWidth: 2,
            borderRadius: 8,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                titleFont: { size: 14 },
                bodyFont: { size: 13 },
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                max: 100,
                grid: { color: 'rgba(0, 0, 0, 0.05)' }
            },
            x: {
                grid: { display: false }
            }
        }
    }
});

// Improvement Percentage Chart
const improvementCtx = document.getElementById('improvementChart').getContext('2d');
new Chart(improvementCtx, {
    type: 'line',
    data: {
        labels: comparisonData.labels,
        datasets: [{
            label: 'Improvement %',
            data: comparisonData.datasets.improvement_percent,
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            borderColor: 'rgba(16, 185, 129, 1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointRadius: 6,
            pointHoverRadius: 8,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
        },
        scales: {
            y: {
                grid: { color: 'rgba(0, 0, 0, 0.05)' }
            },
            x: {
                grid: { display: false }
            }
        }
    }
});

// NT Chart
const ntCtx = document.getElementById('ntChart').getContext('2d');
new Chart(ntCtx, {
    type: 'radar',
    data: {
        labels: comparisonData.labels,
        datasets: [
            {
                label: 'Math',
                data: comparisonData.datasets.nt_math,
                backgroundColor: 'rgba(239, 68, 68, 0.2)',
                borderColor: 'rgba(239, 68, 68, 1)',
                borderWidth: 2,
            },
            {
                label: 'Thai',
                data: comparisonData.datasets.nt_thai,
                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 2,
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            r: {
                beginAtZero: true,
                max: 100
            }
        }
    }
});

// O-NET Chart
const onetCtx = document.getElementById('onetChart').getContext('2d');
new Chart(onetCtx, {
    type: 'bar',
    data: {
        labels: comparisonData.labels,
        datasets: [
            {
                label: 'Math',
                data: comparisonData.datasets.onet_math,
                backgroundColor: 'rgba(239, 68, 68, 0.8)',
            },
            {
                label: 'Thai',
                data: comparisonData.datasets.onet_thai,
                backgroundColor: 'rgba(59, 130, 246, 0.8)',
            },
            {
                label: 'English',
                data: comparisonData.datasets.onet_english,
                backgroundColor: 'rgba(16, 185, 129, 0.8)',
            },
            {
                label: 'Science',
                data: comparisonData.datasets.onet_science,
                backgroundColor: 'rgba(245, 158, 11, 0.8)',
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'top' }
        },
        scales: {
            y: {
                beginAtZero: true,
                max: 100
            }
        }
    }
});

// Copy Share Link
function copyShareLink() {
    const input = document.getElementById('shareLink');
    input.select();
    document.execCommand('copy');
    alert('✅ Share link copied to clipboard!');
}

// Export to PDF
function exportToPDF() {
    window.location.href = '{{ route('sandbox.experiments.export.pdf', $experiment) }}';
}

// Send via Email
function sendViaEmail() {
    const email = prompt('📧 กรอกอีเมลผู้รับ:');
    if (!email) return;
    
    const message = prompt('💬 ข้อความถึงผู้รับ (ไม่บังคับ):');
    
    fetch('{{ route('sandbox.experiments.send-email', $experiment) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ email, message })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
        } else {
            alert('❌ ' + data.message);
        }
    })
    .catch(error => {
        alert('❌ เกิดข้อผิดพลาด: ' + error);
    });
}

// Export to Excel (collect charts as base64 images)
function exportToExcel() {
    // Collect canvases by IDs used in this view
    const canvasIds = ['overallScoreChart', 'improvementChart', 'ntSubjectChart', 'onetSubjectChart'];
    const charts = [];

    canvasIds.forEach(id => {
        const canvas = document.getElementById(id);
        if (canvas && canvas.toDataURL) {
            charts.push({ name: id, dataUrl: canvas.toDataURL('image/png') });
        }
    });

    fetch('{{ route('sandbox.experiments.export.excel', $experiment) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ charts, include_table: true })
    })
    .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.blob();
    })
    .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `experiment_{{ $experiment->id }}_${new Date().toISOString().slice(0,19).replace(/[:T]/g,'')}.xlsx`;
        document.body.appendChild(a);
        a.click();
        a.remove();
        window.URL.revokeObjectURL(url);
    })
    .catch(err => {
        console.error(err);
        alert('เกิดข้อผิดพลาดในการสร้างไฟล์ Excel');
    });
}

function openBatchEmailModal() {
    document.getElementById('batchEmailModal').style.display = 'block';
}

function closeBatchEmailModal() {
    document.getElementById('batchEmailModal').style.display = 'none';
    document.getElementById('emailList').value = '';
    document.getElementById('batchMessage').value = '';
}

function sendBatchEmail() {
    const emailText = document.getElementById('emailList').value.trim();
    const message = document.getElementById('batchMessage').value.trim();
    
    if (!emailText) {
        alert('กรุณากรอกอีเมล');
        return;
    }
    
    // Parse emails - split by newline or comma
    const emails = emailText.split(/[\n,]/)
        .map(e => e.trim())
        .filter(e => e.length > 0);
    
    if (emails.length === 0) {
        alert('ไม่พบอีเมลที่ถูกต้อง');
        return;
    }
    
    // Validate email format
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const invalidEmails = emails.filter(e => !emailRegex.test(e));
    if (invalidEmails.length > 0) {
        alert('อีเมลไม่ถูกต้อง: ' + invalidEmails.join(', '));
        return;
    }
    
    fetch('/sandbox/experiments/{{ $experiment->id }}/send-batch', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ emails, message })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            closeBatchEmailModal();
        } else {
            alert('เกิดข้อผิดพลาด: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(err => {
        console.error(err);
        alert('เกิดข้อผิดพลาดในการส่งอีเมล');
    });
}
</script>

<!-- Batch Email Modal -->
<div id="batchEmailModal" style="display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; overflow:auto; background-color:rgba(0,0,0,0.5);">
    <div style="background-color:#fff; margin:5% auto; padding:20px; border-radius:8px; width:90%; max-width:600px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h3 style="margin:0;">📧 ส่งอีเมลถึงหลายคน</h3>
            <button onclick="closeBatchEmailModal()" style="background:none; border:none; font-size:24px; cursor:pointer;">&times;</button>
        </div>
        
        <div style="margin-bottom:15px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">อีเมลผู้รับ (แยกด้วย Enter หรือ Comma)</label>
            <textarea id="emailList" rows="6" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:4px; font-family:monospace;" placeholder="example1@email.com&#10;example2@email.com&#10;example3@email.com"></textarea>
            <small style="color:#666;">ใส่อีเมลทีละรายการ หรือคั่นด้วยคอมมา</small>
        </div>
        
        <div style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">ข้อความเพิ่มเติม (ไม่บังคับ)</label>
            <textarea id="batchMessage" rows="3" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:4px;" placeholder="ข้อความที่ต้องการแนบไปกับรายงาน..." maxlength="1000"></textarea>
        </div>
        
        <div style="text-align:right;">
            <button onclick="closeBatchEmailModal()" style="padding:10px 20px; margin-right:10px; background:#6c757d; color:#fff; border:none; border-radius:4px; cursor:pointer;">ยกเลิก</button>
            <button onclick="sendBatchEmail()" style="padding:10px 20px; background:#007bff; color:#fff; border:none; border-radius:4px; cursor:pointer;">
                <i class="fas fa-paper-plane"></i> ส่งอีเมล
            </button>
        </div>
    </div>
</div>
@endsection
