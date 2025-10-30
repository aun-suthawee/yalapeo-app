@extends('sandbox::layouts.master')

@section('stylesheet-content')
<link rel="stylesheet" href="{{ asset('assets/common/css/sandbox-experiments.css') }}">
<style>
.experiments-page {
    background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-purple) 100%);
    min-height: 100vh;
    padding: 40px 0;
}

.experiment-card {
    background: var(--accent-white);
    border-radius: var(--border-radius);
    padding: 24px;
    box-shadow: var(--shadow-soft);
    transition: var(--transition);
    height: 100%;
    display: flex;
    flex-direction: column;
}

.experiment-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
}

.experiment-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 16px;
}

.experiment-title {
    font-size: 18px;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 8px;
}

.experiment-description {
    color: #718096;
    font-size: 14px;
    margin-bottom: 16px;
    flex: 1;
}

.experiment-meta {
    display: flex;
    gap: 16px;
    margin-bottom: 16px;
    font-size: 13px;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #4a5568;
}

.meta-item i {
    color: #667eea;
}

.status-badge {
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}

.status-draft {
    background: #fef3c7;
    color: #92400e;
}

.status-running {
    background: #dbeafe;
    color: #1e40af;
}

.status-completed {
    background: #d1fae5;
    color: #065f46;
}

.experiment-actions {
    display: flex;
    gap: 8px;
    margin-top: auto;
    padding-top: 16px;
    border-top: 1px solid #e2e8f0;
}

.btn-experiment {
    flex: 1;
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    text-align: center;
    transition: all 0.2s;
    border: none;
    cursor: pointer;
}

.btn-view {
    background: #667eea;
    color: white;
}

.btn-view:hover {
    background: #5568d3;
    color: white;
}

.btn-edit {
    background: #f59e0b;
    color: white;
}

.btn-edit:hover {
    background: #d97706;
    color: white;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 16px;
    margin-bottom: 32px;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
}

.stat-value {
    font-size: 32px;
    font-weight: 700;
    color: #667eea;
}

.stat-label {
    font-size: 13px;
    color: #718096;
    margin-top: 4px;
}

.filter-tabs {
    background: white;
    border-radius: 12px;
    padding: 8px;
    margin-bottom: 24px;
    display: flex;
    gap: 8px;
}

.filter-tab {
    flex: 1;
    padding: 12px 20px;
    border-radius: 8px;
    background: transparent;
    border: none;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    color: #4a5568;
}

.filter-tab.active {
    background: #667eea;
    color: white;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 16px;
}

.empty-state i {
    font-size: 64px;
    color: #cbd5e0;
    margin-bottom: 16px;
}

.empty-state h3 {
    color: #2d3748;
    margin-bottom: 8px;
}

.empty-state p {
    color: #718096;
}
</style>
@endsection

@section('content')
<div class="experiments-page">
    <div class="container" style="max-width: 1200px;">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="text-white mb-1">
                    <i class="fas fa-flask me-2"></i>Experiments - What-If Analysis
                </h2>
                <p class="text-white-50 mb-0">วิเคราะห์สถานการณ์สมมติและเปรียบเทียบผลลัพธ์</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('sandbox.experiments.create') }}" class="btn btn-light">
                    <i class="fas fa-plus me-2"></i>สร้าง Experiment ใหม่
                </a>
                <a href="{{ route('sandbox.dashboard') }}" class="btn btn-outline-light">
                    <i class="fas fa-arrow-left me-2"></i>กลับ
                </a>
            </div>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value">{{ $stats['total'] }}</div>
                <div class="stat-label">Total Experiments</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $stats['my_experiments'] }}</div>
                <div class="stat-label">My Experiments</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $stats['draft'] }}</div>
                <div class="stat-label">Draft</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $stats['running'] }}</div>
                <div class="stat-label">Running</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $stats['completed'] }}</div>
                <div class="stat-label">Completed</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $stats['public_experiments'] }}</div>
                <div class="stat-label">Public</div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <button class="filter-tab {{ request('filter', 'all') == 'all' ? 'active' : '' }}" 
                    onclick="window.location.href='{{ route('sandbox.experiments.index', ['filter' => 'all']) }}'">
                <i class="fas fa-globe me-2"></i>ทั้งหมด
            </button>
            <button class="filter-tab {{ request('filter') == 'my' ? 'active' : '' }}" 
                    onclick="window.location.href='{{ route('sandbox.experiments.index', ['filter' => 'my']) }}'">
                <i class="fas fa-user me-2"></i>ของฉัน
            </button>
            <button class="filter-tab {{ request('filter') == 'public' ? 'active' : '' }}" 
                    onclick="window.location.href='{{ route('sandbox.experiments.index', ['filter' => 'public']) }}'">
                <i class="fas fa-eye me-2"></i>สาธารณะ
            </button>
        </div>

        <!-- Experiments Grid -->
        @if($experiments->count() > 0)
            <div class="row g-4 mb-4">
                @foreach($experiments as $experiment)
                    <div class="col-md-6 col-lg-4">
                        <div class="experiment-card">
                            <div class="experiment-header">
                                <div>
                                    <h3 class="experiment-title">{{ $experiment->name }}</h3>
                                    <span class="status-badge status-{{ $experiment->status }}">
                                        @if($experiment->status === 'draft')
                                            <i class="fas fa-pencil-alt"></i> Draft
                                        @elseif($experiment->status === 'running')
                                            <i class="fas fa-play"></i> Running
                                        @else
                                            <i class="fas fa-check"></i> Completed
                                        @endif
                                    </span>
                                </div>
                                @if($experiment->is_public)
                                    <span class="badge bg-success">
                                        <i class="fas fa-globe"></i> Public
                                    </span>
                                @endif
                            </div>

                            <p class="experiment-description">
                                {{ Str::limit($experiment->description, 100) }}
                            </p>

                            <div class="experiment-meta">
                                <div class="meta-item">
                                    <i class="fas fa-calendar"></i>
                                    <span>ปีฐาน: {{ $experiment->base_year + 543 }}</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-layer-group"></i>
                                    <span>{{ $experiment->scenarios->count() }} scenarios</span>
                                </div>
                            </div>

                            <div class="experiment-meta">
                                <div class="meta-item">
                                    <i class="fas fa-user"></i>
                                    <span>{{ $experiment->creator->name ?? 'Unknown' }}</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-clock"></i>
                                    <span>{{ $experiment->created_at->diffForHumans() }}</span>
                                </div>
                            </div>

                            <div class="experiment-actions">
                                <a href="{{ route('sandbox.experiments.show', $experiment) }}" class="btn-experiment btn-view">
                                    <i class="fas fa-eye"></i> ดู
                                </a>
                                @if(Auth::id() === $experiment->created_by && $experiment->isEditable())
                                    <a href="{{ route('sandbox.experiments.edit', $experiment) }}" class="btn-experiment btn-edit">
                                        <i class="fas fa-edit"></i> แก้ไข
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($experiments->hasPages())
                <div class="d-flex justify-content-center">
                    {{ $experiments->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <i class="fas fa-flask"></i>
                <h3>ยังไม่มี Experiment</h3>
                <p>เริ่มสร้าง Experiment แรกของคุณเพื่อวิเคราะห์ What-If scenarios</p>
                <a href="{{ route('sandbox.experiments.create') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-plus me-2"></i>สร้าง Experiment ใหม่
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
