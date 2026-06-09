@extends('layouts.kaiadmin')

@section('title', 'Dashboard')

@section('content')
<div class="page-inner">

  {{-- Welcome Banner --}}
  <div class="dash-welcome-banner">
    <div class="dash-welcome-left">
      <div class="dash-welcome-avatar">
        <img src="{{ asset('img/ngd-logo.png') }}" alt="NGD"
             onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
        <span class="dash-avatar-fallback" style="display:none;"><i class="fas fa-user"></i></span>
      </div>
      <div>
        <h2 class="dash-welcome-title">Welcome back, {{ auth('admin')->user()->name }} 👋</h2>
        <p class="dash-welcome-sub">
          <i class="fas fa-calendar-alt me-1"></i>
          {{ now()->format('l, d M Y') }} &nbsp;|&nbsp;
          <i class="fas fa-robot me-1"></i> AI Prompt Model: <strong>{{ $aiModel }}</strong>
        </p>
      </div>
    </div>
    <div class="dash-welcome-right">
      <div class="dash-couple-badge {{ $coupleActive ? 'couple-badge-on' : 'couple-badge-off' }}">
        <i class="fas fa-user-friends me-1"></i>
        Couple Mode: <strong>{{ $coupleActive ? 'ON' : 'OFF' }}</strong>
      </div>
    </div>
  </div>

  {{-- Stat Cards Row --}}
  <div class="dash-stat-grid">

    <a href="{{ route('ngendev.categories.index') }}" class="dash-stat-card dash-card-blue">
      <div class="dash-card-glow"></div>
      <div class="dash-card-top">
        <div class="dash-card-icon">
          <i class="fas fa-tags"></i>
        </div>
        <div class="dash-card-trend">
          <i class="fas fa-layer-group"></i> Total
        </div>
      </div>
      <div class="dash-stat-num">{{ $categoryCount }}</div>
      <div class="dash-stat-label">Video Categories</div>
      <div class="dash-card-bar">
        <div class="dash-card-bar-fill" style="width:100%"></div>
      </div>
      <div class="dash-view-link">View All <i class="fas fa-arrow-right ms-1"></i></div>
    </a>

    <a href="{{ route('ngendev.videos.index') }}" class="dash-stat-card dash-card-purple">
      <div class="dash-card-glow"></div>
      <div class="dash-card-top">
        <div class="dash-card-icon">
          <i class="fas fa-video"></i>
        </div>
        <div class="dash-card-trend">
          <i class="fas fa-film"></i> Total
        </div>
      </div>
      <div class="dash-stat-num">{{ $videoCount }}</div>
      <div class="dash-stat-label">Videos</div>
      <div class="dash-card-bar">
        <div class="dash-card-bar-fill" style="width:100%"></div>
      </div>
      <div class="dash-view-link">View All <i class="fas fa-arrow-right ms-1"></i></div>
    </a>

    <a href="{{ route('ngendev.categories.index') }}" class="dash-stat-card dash-card-green">
      <div class="dash-card-glow"></div>
      <div class="dash-card-top">
        <div class="dash-card-icon">
          <i class="fas fa-check-circle"></i>
        </div>
        <div class="dash-card-trend">
          <i class="fas fa-toggle-on"></i> Active
        </div>
      </div>
      <div class="dash-stat-num">{{ $activeCategoryCount }}</div>
      <div class="dash-stat-label">Active Categories</div>
      <div class="dash-card-bar">
        @php $activePct = $categoryCount > 0 ? round(($activeCategoryCount/$categoryCount)*100) : 0; @endphp
        <div class="dash-card-bar-fill" style="width:{{ $activePct }}%"></div>
      </div>
      <div class="dash-view-link">{{ $activePct }}% Active <i class="fas fa-arrow-right ms-1"></i></div>
    </a>

    <div class="dash-stat-card dash-card-orange">
      <div class="dash-card-glow"></div>
      <div class="dash-card-top">
        <div class="dash-card-icon">
          <i class="fas fa-user-friends"></i>
        </div>
        <div class="dash-card-trend">
          <i class="fas fa-venus-mars"></i> Types
        </div>
      </div>
      <div class="dash-stat-num">{{ $coupleCount }}</div>
      <div class="dash-stat-label">Couple Categories</div>
      <div class="dash-card-bar">
        @php $couplePct = $categoryCount > 0 ? round(($coupleCount/$categoryCount)*100) : 0; @endphp
        <div class="dash-card-bar-fill" style="width:{{ $couplePct }}%"></div>
      </div>
      <div class="dash-view-link">{{ $soloCount }} Solo &nbsp;|&nbsp; {{ $coupleCount }} Couple</div>
    </div>

  </div>

  {{-- Bottom Row --}}
  <div class="dash-bottom-row">

    {{-- Recent Categories --}}
    <div class="dash-panel">
      <div class="dash-panel-header">
        <span><i class="fas fa-clock me-2 text-primary"></i>Recent Categories</span>
        <a href="{{ route('ngendev.categories.index') }}" class="dash-panel-link">View All</a>
      </div>
      <div class="dash-panel-body">
        @forelse($recentCategories as $cat)
        <div class="dash-recent-row">
          <div class="dash-recent-icon">
            @if($cat->first_image)
              <img src="{{ asset('upload/ngendev/videos/' . rawurlencode($cat->category_name) . '/category_thumbnail_image/' . $cat->first_image) }}"
                   alt="{{ $cat->category_name }}"
                   onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
              <span style="display:none;"><i class="fas fa-tags"></i></span>
            @else
              <i class="fas fa-tags"></i>
            @endif
          </div>
          <div class="dash-recent-info">
            <span class="dash-recent-name">{{ $cat->category_name }}</span>
            <span class="dash-recent-meta">#{{ $cat->id }}</span>
          </div>
          <div class="dash-recent-badges">
            <span class="dash-badge-type {{ $cat->type === 'Couple' ? 'type-couple' : 'type-solo' }}">
              {{ $cat->type }}
            </span>
            <span class="dash-badge-status {{ $cat->status ? 'status-active' : 'status-inactive' }}">
              {{ $cat->status ? 'Active' : 'Inactive' }}
            </span>
          </div>
        </div>
        @empty
        <p class="dash-empty">No categories found.</p>
        @endforelse
      </div>
    </div>

    {{-- Quick Actions + System Info --}}
    <div class="dash-side-col">

      {{-- Quick Actions --}}
      <div class="dash-panel">
        <div class="dash-panel-header">
          <span><i class="fas fa-bolt me-2 text-warning"></i>Quick Actions</span>
        </div>
        <div class="dash-panel-body dash-quick-actions">
          <a href="{{ route('ngendev.categories.create') }}" class="dash-action-btn dash-action-blue">
            <i class="fas fa-plus-circle"></i>
            <span>Add Category</span>
          </a>
          <a href="{{ route('ngendev.videos.create') }}" class="dash-action-btn dash-action-purple">
            <i class="fas fa-film"></i>
            <span>Add Video</span>
          </a>
          <a href="{{ route('ngendev.categories.index') }}" class="dash-action-btn dash-action-green">
            <i class="fas fa-tags"></i>
            <span>Categories</span>
          </a>
          <a href="{{ route('ngendev.api.list') }}" class="dash-action-btn dash-action-orange">
            <i class="fas fa-code"></i>
            <span>API List</span>
          </a>
        </div>
      </div>

      {{-- System Info --}}
      <div class="dash-panel dash-info-panel">
        <div class="dash-panel-header">
          <span><i class="fas fa-info-circle me-2 text-info"></i>System Info</span>
        </div>
        <div class="dash-panel-body">
          <div class="dash-info-row">
            <span class="dash-info-label"><i class="fas fa-robot"></i> AI Model</span>
            <span class="dash-info-val">{{ $aiModel }}</span>
          </div>
          <div class="dash-info-row">
            <span class="dash-info-label"><i class="fas fa-user-friends"></i> Couple Mode</span>
            <span class="dash-info-val {{ $coupleActive ? 'val-green' : 'val-red' }}">
              {{ $coupleActive ? 'ON' : 'OFF' }}
            </span>
          </div>
          <div class="dash-info-row">
            <span class="dash-info-label"><i class="fas fa-layer-group"></i> Total Categories</span>
            <span class="dash-info-val">{{ $categoryCount }}</span>
          </div>
          <div class="dash-info-row">
            <span class="dash-info-label"><i class="fas fa-video"></i> Total Videos</span>
            <span class="dash-info-val">{{ $videoCount }}</span>
          </div>
          <div class="dash-info-row">
            <span class="dash-info-label"><i class="fas fa-toggle-on"></i> Active Categories</span>
            <span class="dash-info-val val-green">{{ $activeCategoryCount }}</span>
          </div>
          <div class="dash-info-row">
            <span class="dash-info-label"><i class="fas fa-toggle-off"></i> Inactive Categories</span>
            <span class="dash-info-val val-red">{{ $inactiveCategoryCount }}</span>
          </div>
        </div>
      </div>

    </div>
  </div>

</div>
@endsection
