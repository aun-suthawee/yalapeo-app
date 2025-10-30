@extends('sandbox::layouts.master')

@section('stylesheet-content')
<style>
.share-view {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 40px 0;
}

.share-header {
    background: white;
    border-radius: 16px;
    padding: 32px;
    margin-bottom: 24px;
    text-align: center;
}

.share-badge {
    display: inline-block;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 8px 20px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 16px;
}

.share-title {
    font-size: 32px;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 12px;
}

.share-desc {
    color: #718096;
    font-size: 16px;
    margin-bottom: 24px;
}

.share-meta {
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
}

.share-meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #4a5568;
    font-size: 14px;
}

.share-meta-item i {
    color: #667eea;
}

.copy-experiment-btn {
    background: #667eea;
    color: white;
    padding: 12px 32px;
    border-radius: 8px;
    border: none;
    font-weight: 600;
    margin-top: 24px;
    cursor: pointer;
    transition: all 0.2s;
}

.copy-experiment-btn:hover {
    background: #5568d3;
    transform: translateY(-2px);
}
</style>
@endsection

@section('content')
<div class="share-view">
    <div class="container" style="max-width: 1400px;">
        <!-- Share Header -->
        <div class="share-header">
            <div class="share-badge">
                <i class="fas fa-share-alt me-2"></i>Shared Experiment
            </div>
            <h1 class="share-title">{{ $experiment->name }}</h1>
            <p class="share-desc">{{ $experiment->description }}</p>
            
            <div class="share-meta">
                <div class="share-meta-item">
                    <i class="fas fa-user"></i>
                    <span>By {{ $experiment->creator->name ?? 'Unknown' }}</span>
                </div>
                <div class="share-meta-item">
                    <i class="fas fa-calendar"></i>
                    <span>{{ $experiment->created_at->format('d M Y') }}</span>
                </div>
                <div class="share-meta-item">
                    <i class="fas fa-layer-group"></i>
                    <span>{{ $experiment->scenarios->count() }} Scenarios</span>
                </div>
            </div>

            @auth
                <form action="{{ route('sandbox.experiments.duplicate', $experiment) }}" method="POST">
                    @csrf
                    <button type="submit" class="copy-experiment-btn">
                        <i class="fas fa-copy me-2"></i>Copy to My Experiments
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="copy-experiment-btn" style="display: inline-block; text-decoration: none;">
                    <i class="fas fa-sign-in-alt me-2"></i>Login to Copy This Experiment
                </a>
            @endauth
        </div>

        <!-- Include Same Content as Show View -->
        @include('sandbox::experiments.show', ['readOnly' => true])
    </div>
</div>
@endsection
