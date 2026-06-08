@extends('layouts.kaiadmin')

@section('title', 'API List')

@section('content')
@php
  $dynamicRoot = request()->getScheme() . '://' . request()->getHttpHost() . '/';
  $apiBase     = $dynamicRoot;
  $base        = rtrim($dynamicRoot, '/') . '/api/v1/ngd';
@endphp
<div class="page-inner">

  <div class="page-header-bar">
    <div class="header-left">
      <h3><i class="fas fa-code me-2 text-primary"></i> API List</h3>
      <p>All available Ngendev Video API endpoints</p>
    </div>
  </div>

  <h2 class="api-doc-title">API Documentation</h2>

  {{-- ── Module Badge ──────────────────────────────────────────── --}}
  <span class="api-module-badge blue">NGD Module</span>

  {{-- ── 2-column card grid ───────────────────────────────────── --}}
  <div class="api-grid">

    {{-- Card 1 --}}
    <div class="api-card">
      <div class="api-card-num">1. Get AI Video Categories</div>

      <div class="api-row">
        <span class="api-lbl">Method:</span>
        <span class="api-method-get">GET</span>
      </div>

      <div class="api-row">
        <span class="api-lbl">URL:</span>
        <span class="api-url-val">{{ $base }}/getAiVideoCategories</span>
      </div>

      <div class="api-row">
        <span class="api-lbl">Description:</span>
        <div class="api-desc-val">
          Retrieves a list of AI Video categories available in the app.
        </div>
      </div>
    </div>

    {{-- Card 2 --}}
    <div class="api-card">
      <div class="api-card-num">2. Get AI Videos by Category ID</div>

      <div class="api-row">
        <span class="api-lbl">Method:</span>
        <span class="api-method-post">POST</span>
      </div>

      <div class="api-row">
        <span class="api-lbl">URL:</span>
        <span class="api-url-val">{{ $base }}/getAiVideoByCategoryId</span>
      </div>

      <div class="api-row">
        <span class="api-lbl">Parameters:</span><br>
        <span class="api-param-pill">category_id</span>
        <span class="api-param-note">(required) e.g. 5 &nbsp;|&nbsp; use <strong>0</strong> for Latest</span>
      </div>

      <div class="api-row">
        <span class="api-lbl">Description:</span>
        <div class="api-desc-val">
          Returns all AI-generated videos and their thumbnails for the specified category ID.
        </div>
      </div>
    </div>

    {{-- Card 3 --}}
    <div class="api-card">
      <div class="api-card-num">3. Get All Category Names</div>

      <div class="api-row">
        <span class="api-lbl">Method:</span>
        <span class="api-method-get">GET</span>
      </div>

      <div class="api-row">
        <span class="api-lbl">URL:</span>
        <span class="api-url-val">{{ $base }}/getAllCategoryNames</span>
      </div>

      <div class="api-row">
        <span class="api-lbl">Description:</span>
        <div class="api-desc-val">
          Returns a lightweight list of all active categories (ID + name only).
          Useful for populating category dropdowns in the app.
        </div>
      </div>
    </div>

  </div>{{-- /api-grid --}}

</div>
<script>
function copyBaseUrl(btn) {
  navigator.clipboard.writeText(btn.dataset.url).then(function() {
    var icon = btn.querySelector('i');
    icon.className = 'fas fa-check';
    btn.style.color = '#28a745';
    setTimeout(function() { icon.className = 'fas fa-copy'; btn.style.color = ''; }, 1500);
  });
}
</script>
@endsection
