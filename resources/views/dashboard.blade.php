@extends('layouts.kaiadmin')

@section('title', 'Dashboard')

@section('content')
<div class="page-inner">

  {{-- Module Header --}}
  <div class="dash-module-header">
    <div class="dash-module-icon">
      <i class="fas fa-film"></i>
    </div>
    <div>
      <h3 class="dash-module-title">AI Video Prompt</h3>
      <p class="dash-module-sub">NGD AI video categories and videos</p>
    </div>
  </div>

  {{-- Stat Cards --}}
  <div class="dash-stat-grid">

    {{-- Categories --}}
    <a href="{{ route('ngendev.categories.index') }}" class="dash-stat-card dash-card-pink">
      <div class="dash-bubble dash-bubble-1"></div>
      <div class="dash-bubble dash-bubble-2"></div>
      <div class="dash-icon-circle">
        <i class="fas fa-tags"></i>
      </div>
      <div class="dash-stat-num">{{ $categoryCount }}</div>
      <div class="dash-stat-label">Categories</div>
      <div class="dash-view-btn">View &rarr;</div>
    </a>

    {{-- Videos --}}
    <a href="{{ route('ngendev.videos.index') }}" class="dash-stat-card dash-card-orange">
      <div class="dash-bubble dash-bubble-1"></div>
      <div class="dash-bubble dash-bubble-2"></div>
      <div class="dash-icon-circle">
        <i class="fas fa-video"></i>
      </div>
      <div class="dash-stat-num">{{ $videoCount }}</div>
      <div class="dash-stat-label">Videos</div>
      <div class="dash-view-btn">View &rarr;</div>
    </a>

  </div>

</div>
@endsection
