@extends('layouts.kaiadmin')

@section('title', 'NGD Settings')

@section('content')
<div class="page-inner">

  <div class="page-header-bar">
    <div class="header-left">
      <h3><i class="fas fa-cog me-2 text-primary"></i> NGD Video Settings</h3>
      <p>Configure AI model and category display options</p>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" style="border-radius:10px;font-size:13px;" role="alert">
      <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <div class="form-card" style="max-width:560px;">
    <form method="POST" action="{{ route('ngendev.settings.update') }}">
      @csrf

      <div class="mb-4">
        <label class="form-label">AI Model Name</label>
        <input type="text" name="model" class="form-control"
               value="{{ old('model', $setting->model) }}"
               placeholder="e.g. Ngendev Video">
        <div class="hint-text mt-1">This value is returned as <code>model</code> in every API response.</div>
      </div>

      <div class="mb-4">
        <div class="toggle-row">
          <label class="toggle">
            <input type="checkbox" name="couple_active" id="coupleActive"
                   {{ $setting->couple_active ? 'checked' : '' }}>
            <span class="slider"></span>
          </label>
          <span class="form-label mb-0">Couple Categories Active</span>
        </div>
        <div class="hint-text mt-1">When disabled, Couple-type categories are hidden from all API responses.</div>
      </div>

      <button type="submit" class="btn btn-submit">
        <i class="fas fa-save me-2"></i> Save Settings
      </button>
    </form>
  </div>

</div>
@endsection
