<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $experiment->name }} - Experiment Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Sarabun', 'DejaVu Sans', sans-serif;
            font-size: 12pt;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #667eea;
        }

        .header h1 {
            font-size: 24pt;
            color: #667eea;
            margin-bottom: 10px;
        }

        .header .subtitle {
            font-size: 14pt;
            color: #666;
        }

        .meta-info {
            background: #f7fafc;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .meta-info table {
            width: 100%;
            border-collapse: collapse;
        }

        .meta-info td {
            padding: 5px 10px;
            font-size: 11pt;
        }

        .meta-info td:first-child {
            font-weight: bold;
            color: #667eea;
            width: 150px;
        }

        .section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 16pt;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e2e8f0;
        }

        .comparison-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .comparison-table thead {
            background: #667eea;
            color: white;
        }

        .comparison-table th,
        .comparison-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
            font-size: 10pt;
        }

        .comparison-table th {
            font-weight: bold;
            font-size: 9pt;
        }

        .comparison-table tbody tr:nth-child(even) {
            background: #f7fafc;
        }

        .comparison-table tbody tr:hover {
            background: #edf2f7;
        }

        .baseline-badge {
            background: #d1fae5;
            color: #065f46;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 8pt;
            font-weight: bold;
        }

        .improvement-positive {
            color: #10b981;
            font-weight: bold;
        }

        .improvement-negative {
            color: #ef4444;
            font-weight: bold;
        }

        .improvement-neutral {
            color: #718096;
        }

        .scenario-details {
            background: #f9fafb;
            padding: 15px;
            border-left: 4px solid #667eea;
            margin-bottom: 15px;
        }

        .scenario-details h4 {
            color: #667eea;
            margin-bottom: 10px;
            font-size: 13pt;
        }

        .scenario-details p {
            margin-bottom: 8px;
            font-size: 11pt;
        }

        .parameters-grid {
            display: table;
            width: 100%;
            margin-top: 10px;
        }

        .parameter-item {
            display: table-row;
        }

        .parameter-item .label {
            display: table-cell;
            font-weight: bold;
            color: #4a5568;
            padding: 5px 10px 5px 0;
            width: 200px;
        }

        .parameter-item .value {
            display: table-cell;
            color: #2d3748;
            padding: 5px 0;
        }

        .insights-box {
            background: #f0fdf4;
            border-left: 4px solid #10b981;
            padding: 15px;
            margin-top: 20px;
        }

        .insights-box h4 {
            color: #065f46;
            margin-bottom: 10px;
            font-size: 13pt;
        }

        .insights-box p {
            color: #047857;
            font-size: 11pt;
            line-height: 1.8;
        }

        .chart-placeholder {
            background: #f7fafc;
            border: 2px dashed #cbd5e0;
            padding: 30px;
            text-align: center;
            color: #718096;
            margin: 20px 0;
            border-radius: 8px;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
            text-align: center;
            font-size: 10pt;
            color: #718096;
        }

        .page-break {
            page-break-after: always;
        }

        @media print {
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>{{ $experiment->name }}</h1>
        <div class="subtitle">What-If Analysis Experiment Report</div>
    </div>

    <!-- Meta Information -->
    <div class="meta-info">
        <table>
            <tr>
                <td>ชื่อการทดลอง:</td>
                <td>{{ $experiment->name }}</td>
            </tr>
            <tr>
                <td>คำอธิบาย:</td>
                <td>{{ $experiment->description ?: '-' }}</td>
            </tr>
            <tr>
                <td>ผู้สร้าง:</td>
                <td>{{ $experiment->creator->name ?? 'Unknown' }}</td>
            </tr>
            <tr>
                <td>ปีฐาน:</td>
                <td>{{ $experiment->base_year + 543 }}</td>
            </tr>
            <tr>
                <td>ประเภท:</td>
                <td>{{ ucfirst($experiment->type) }}</td>
            </tr>
            <tr>
                <td>สถานะ:</td>
                <td>{{ ucfirst($experiment->status) }}</td>
            </tr>
            <tr>
                <td>จำนวน Scenarios:</td>
                <td>{{ $experiment->scenarios->count() }} scenarios</td>
            </tr>
            <tr>
                <td>วันที่สร้าง:</td>
                <td>{{ $experiment->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @if($experiment->completed_at)
            <tr>
                <td>วันที่เสร็จสิ้น:</td>
                <td>{{ $experiment->completed_at->format('d/m/Y H:i') }}</td>
            </tr>
            @endif
        </table>
    </div>

    <!-- Overall Comparison Table -->
    <div class="section">
        <h2 class="section-title">📊 Overall Comparison Summary</h2>
        <table class="comparison-table">
            <thead>
                <tr>
                    <th style="text-align: left;">Scenario</th>
                    <th>NT Math</th>
                    <th>NT Thai</th>
                    <th>RT Reading</th>
                    <th>O-NET Math</th>
                    <th>O-NET Thai</th>
                    <th>O-NET Eng</th>
                    <th>O-NET Sci</th>
                    <th>Average</th>
                    <th>Improvement</th>
                </tr>
            </thead>
            <tbody>
                @foreach($experiment->scenarios as $scenario)
                    @php
                        $result = $scenario->results ? $scenario->results->first() : null;
                        $metrics = $result ? $result->metrics : null;
                        $isBaseline = $scenario->scenario_number == 0;
                    @endphp
                    <tr>
                        <td style="text-align: left;">
                            <strong>{{ $scenario->name }}</strong>
                            @if($isBaseline)
                                <span class="baseline-badge">Baseline</span>
                            @endif
                        </td>
                        @if($metrics)
                            <td>{{ number_format($metrics['nt_math_avg'] ?? 0, 2) }}</td>
                            <td>{{ number_format($metrics['nt_thai_avg'] ?? 0, 2) }}</td>
                            <td>{{ number_format($metrics['rt_reading_avg'] ?? 0, 2) }}</td>
                            <td>{{ number_format($metrics['onet_math_avg'] ?? 0, 2) }}</td>
                            <td>{{ number_format($metrics['onet_thai_avg'] ?? 0, 2) }}</td>
                            <td>{{ number_format($metrics['onet_eng_avg'] ?? 0, 2) }}</td>
                            <td>{{ number_format($metrics['onet_sci_avg'] ?? 0, 2) }}</td>
                            <td><strong>{{ number_format($metrics['avg_score'] ?? 0, 2) }}</strong></td>
                            <td>
                                @php
                                    $improvement = $metrics['improvement_percent'] ?? 0;
                                    $class = $improvement > 0 ? 'improvement-positive' : ($improvement < 0 ? 'improvement-negative' : 'improvement-neutral');
                                @endphp
                                <span class="{{ $class }}">{{ number_format($improvement, 2) }}%</span>
                            </td>
                        @else
                            <td colspan="8" style="color: #999;">Not calculated</td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Page Break -->
    <div class="page-break"></div>

    <!-- Detailed Scenario Information -->
    <div class="section">
        <h2 class="section-title">📋 Detailed Scenario Information</h2>
        @foreach($experiment->scenarios as $scenario)
            <div class="scenario-details">
                <h4>{{ $scenario->scenario_number }}. {{ $scenario->name }}</h4>
                <p><strong>คำอธิบาย:</strong> {{ $scenario->description ?: 'ไม่มีคำอธิบาย' }}</p>
                
                @if($scenario->parameters && count($scenario->parameters) > 0)
                    <p><strong>Parameters:</strong></p>
                    <div class="parameters-grid">
                        @foreach($scenario->parameters as $key => $value)
                            <div class="parameter-item">
                                <span class="label">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                                <span class="value">{{ $value }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                @php
                    $result = $scenario->results ? $scenario->results->first() : null;
                    $metrics = $result ? $result->metrics : null;
                @endphp

                @if($metrics)
                    <p style="margin-top: 10px;"><strong>ผลลัพธ์:</strong></p>
                    <div class="parameters-grid">
                        <div class="parameter-item">
                            <span class="label">คะแนนเฉลี่ยรวม:</span>
                            <span class="value">{{ number_format($metrics['avg_score'] ?? 0, 2) }}</span>
                        </div>
                        <div class="parameter-item">
                            <span class="label">การปรับปรุง:</span>
                            <span class="value">{{ number_format($metrics['improvement_percent'] ?? 0, 2) }}%</span>
                        </div>
                        @if(isset($metrics['calculated_at']))
                        <div class="parameter-item">
                            <span class="label">คำนวณเมื่อ:</span>
                            <span class="value">{{ \Carbon\Carbon::parse($metrics['calculated_at'])->format('d/m/Y H:i') }}</span>
                        </div>
                        @endif
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <!-- Key Insights -->
    <div class="section">
        <h2 class="section-title">💡 Key Insights & Recommendations</h2>
        <div class="insights-box">
            @php
                $bestScenario = $experiment->scenarios
                    ->filter(fn($s) => $s->results && $s->results->isNotEmpty())
                    ->sortByDesc(fn($s) => $s->results->first()->metrics['avg_score'] ?? 0)
                    ->first();
                
                $avgImprovement = $experiment->scenarios
                    ->filter(fn($s) => $s->results && $s->results->isNotEmpty() && $s->scenario_number > 0)
                    ->avg(fn($s) => $s->results->first()->metrics['improvement_percent'] ?? 0);
            @endphp

            <h4>🏆 Best Performing Scenario</h4>
            @if($bestScenario)
                <p>
                    <strong>{{ $bestScenario->name }}</strong> ได้คะแนนเฉลี่ยสูงสุดที่ 
                    <strong>{{ number_format($bestScenario->results->first()->metrics['avg_score'] ?? 0, 2) }}</strong> คะแนน
                    @if($bestScenario->scenario_number > 0)
                        ซึ่งมีการปรับปรุงจาก baseline 
                        <strong>{{ number_format($bestScenario->results->first()->metrics['improvement_percent'] ?? 0, 2) }}%</strong>
                    @endif
                </p>
            @else
                <p>ยังไม่มีข้อมูลผลลัพธ์</p>
            @endif

            <h4 style="margin-top: 15px;">📈 Overall Trend</h4>
            @if($avgImprovement !== null)
                @if($avgImprovement > 0)
                    <p>
                        ผลการทดลองแสดงแนวโน้มเชิงบวก โดยมีการปรับปรุงเฉลี่ย 
                        <strong class="improvement-positive">{{ number_format($avgImprovement, 2) }}%</strong> 
                        จาก scenarios ทั้งหมด แสดงให้เห็นว่าการปรับเปลี่ยนพารามิเตอร์ส่งผลดีต่อผลการเรียน
                    </p>
                @elseif($avgImprovement < 0)
                    <p>
                        บาง scenarios แสดงผลลัพธ์ลดลง โดยมีค่าเฉลี่ย 
                        <strong class="improvement-negative">{{ number_format($avgImprovement, 2) }}%</strong>
                        ควรทบทวนและปรับปรุงพารามิเตอร์ให้เหมาะสม
                    </p>
                @else
                    <p>Scenarios แสดงผลลัพธ์ผสมผสาน โดยมีการเปลี่ยนแปลงน้อยมาก</p>
                @endif
            @else
                <p>กรุณารัน scenarios เพื่อดูผลการวิเคราะห์และข้อเสนอแนะ</p>
            @endif

            @if($bestScenario && isset($bestScenario->parameters) && count($bestScenario->parameters) > 0)
                <h4 style="margin-top: 15px;">💼 Recommendations</h4>
                <p>
                    จากผลการทดลองแนะนำให้พิจารณานำ parameters จาก <strong>{{ $bestScenario->name }}</strong> 
                    มาประยุกต์ใช้ในการพัฒนาโรงเรียน โดยเฉพาะ:
                </p>
                <ul style="margin-left: 20px; margin-top: 8px;">
                    @foreach(array_slice($bestScenario->parameters, 0, 3, true) as $key => $value)
                        <li style="margin-bottom: 5px;">
                            {{ ucfirst(str_replace('_', ' ', $key)) }}: <strong>{{ $value }}</strong>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>สร้างโดยระบบ What-If Analysis - Sandbox Module</p>
        <p>วันที่พิมพ์: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
