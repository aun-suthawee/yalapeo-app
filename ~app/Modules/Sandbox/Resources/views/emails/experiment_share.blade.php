<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shared Experiment</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 3px solid #667eea;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #667eea;
            margin: 0 0 10px 0;
        }
        .content {
            margin-bottom: 20px;
        }
        .experiment-info {
            background: #f7fafc;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
        }
        .experiment-info p {
            margin: 8px 0;
        }
        .experiment-info strong {
            color: #667eea;
        }
        .custom-message {
            background: #fff9e6;
            border-left: 4px solid #fbbf24;
            padding: 15px;
            margin: 15px 0;
            font-style: italic;
        }
        .btn {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            color: #718096;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📊 Shared Experiment</h1>
            <p>{{ $sender->name }} แชร์ Experiment กับคุณ</p>
        </div>

        <div class="content">
            <p>สวัสดีครับ/ค่ะ</p>
            <p>คุณ <strong>{{ $sender->name }}</strong> ได้แชร์ What-If Analysis Experiment ให้คุณ</p>

            <div class="experiment-info">
                <p><strong>ชื่อ Experiment:</strong> {{ $experiment->name }}</p>
                <p><strong>คำอธิบาย:</strong> {{ $experiment->description ?: 'ไม่มีคำอธิบาย' }}</p>
                <p><strong>ปีฐาน:</strong> {{ $experiment->base_year + 543 }}</p>
                <p><strong>จำนวน Scenarios:</strong> {{ $experiment->scenarios->count() }}</p>
                <p><strong>สถานะ:</strong> {{ ucfirst($experiment->status) }}</p>
            </div>

            @if($custom_message)
                <div class="custom-message">
                    <strong>💬 ข้อความจากผู้ส่ง:</strong><br>
                    {{ $custom_message }}
                </div>
            @endif

            <p>เอกสารรายงานฉบับเต็มแนบมาพร้อมกับอีเมลนี้</p>

            @if($experiment->is_public && $experiment->share_token)
                <p style="text-align: center;">
                    <a href="{{ route('sandbox.experiments.share', $experiment->share_token) }}" class="btn">
                        🔗 ดู Experiment แบบออนไลน์
                    </a>
                </p>
            @endif
        </div>

        <div class="footer">
            <p>อีเมลนี้ส่งจากระบบ What-If Analysis - Sandbox Module</p>
            <p>{{ config('app.name') }} | {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>
</body>
</html>
