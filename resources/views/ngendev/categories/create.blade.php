@extends('layouts.kaiadmin')

@section('title', 'Add New Category')

@section('content')
<div class="page-inner">
  <div class="page-header-bar">
    <div>
      <h3><i class="fas fa-plus-circle me-2 text-primary"></i> Add New Category</h3>
      <p>Create a new Ngendev category</p>
    </div>
    <a href="{{ route('ngendev.categories.index') }}" class="btn-back">
      <i class="fas fa-arrow-left"></i> Back to Categories
    </a>
  </div>

  @if($errors->any())
    <div class="alert alert-danger" style="border-radius:10px;font-size:13px;max-width:640px;">
      <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
  @endif

  <div class="form-card">
    <form method="POST" action="{{ route('ngendev.categories.store') }}" enctype="multipart/form-data">
      @csrf

      <div class="mb-3">
        <label class="form-label">Category Name</label>
        <input type="text" name="category_name" class="form-control"
               value="{{ old('category_name') }}" placeholder="Enter category name" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Type</label>
        <select name="type" class="form-select" id="typeSelect" onchange="toggleStatusHint()">
          <option value="Solo"   {{ old('type','Solo')=='Solo'  ?'selected':'' }}>Solo</option>
          <option value="Couple" {{ old('type')=='Couple'?'selected':'' }}>Couple</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Category Image(s)</label>
        <input type="file" name="category_image[]" class="form-control" multiple accept="image/*">
        <div class="hint-text">You can select multiple images. Max 5 MB each.</div>
      </div>

      <div class="mb-4">
        <div class="toggle-row">
          <label class="toggle">
            <input type="checkbox" name="status" checked id="statusToggle">
            <span class="slider"></span>
          </label>
          <span class="form-label mb-0">Active Status</span>
        </div>
        <div class="hint-text" id="statusHint">Solo categories are always active.</div>
      </div>

      <button type="submit" class="btn-submit">
        <i class="fas fa-plus me-2"></i> Add Category
      </button>
    </form>
  </div>
</div>
@endsection

