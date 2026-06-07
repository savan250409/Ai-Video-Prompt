@extends('layouts.kaiadmin')

@section('title', 'API List')

@section('content')
@php $base = rtrim(config('app.url'), '/') . '/api/ngendev'; @endphp
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
        <span class="api-url-val">{{ $base }}/categories</span>
      </div>

      <div class="api-row">
        <span class="api-lbl">Description:</span>
        <div class="api-desc-val">
          Returns all active categories with their last 4 videos each.
          Order: Exclusive → Trending → Latest (virtual) → Rest.
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
        <span class="api-url-val">{{ $base }}/videos-by-category</span>
      </div>

      <div class="api-row">
        <span class="api-lbl">Parameters:</span><br>
        <span class="api-param-pill">category_id</span>
        <span class="api-param-note">(required) e.g. 1 &nbsp;|&nbsp; use <strong>0</strong> for Latest</span>
      </div>

      <div class="api-row">
        <span class="api-lbl">Description:</span>
        <div class="api-desc-val">
          Returns all videos for the given category ID.
          Pass <strong>0</strong> to get one latest video from every category.
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
        <span class="api-url-val">{{ $base }}/category-names</span>
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
@endsection
