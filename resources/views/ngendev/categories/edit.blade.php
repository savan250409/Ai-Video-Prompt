@extends('layouts.kaiadmin')

@section('title', 'Edit Category')

@section('content')
<div class="page-inner">
  <div class="page-header-bar">
    <div>
      <h3><i class="fas fa-edit me-2 text-primary"></i> Edit Category</h3>
      <p>Update "{{ $category->category_name }}"</p>
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
    <form method="POST" action="{{ route('ngendev.categories.update', $category->id) }}" enctype="multipart/form-data">
      @csrf @method('PUT')

      <div class="mb-3">
        <label class="form-label">Category Name</label>
        <input type="text" name="category_name" class="form-control"
               value="{{ old('category_name', $category->category_name) }}" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Type</label>
        <select name="type" class="form-select">
          <option value="Solo"   {{ old('type', $category->type)=='Solo'  ?'selected':'' }}>Solo</option>
          <option value="Couple" {{ old('type', $category->type)=='Couple'?'selected':'' }}>Couple</option>
        </select>
      </div>

      <div class="mb-4">
        <label class="form-label">Category Image(s) <span class="hint-text">(leave blank to keep existing)</span></label>
        @if($category->category_image && count($category->category_image) > 0)
          <div class="img-preview-wrap">
            @foreach($category->category_image as $img)
              <img src="{{ asset('upload/ngendev/videos/' . rawurlencode($category->category_name) . '/category_thumbnail_image/' . $img) }}"
                   class="img-preview" alt="Current image"
                   onerror="this.style.opacity=.3">
            @endforeach
          </div>
        @endif
        <input type="file" name="category_image[]" class="form-control mt-2" multiple accept="image/*">
        <div class="hint-text">Uploading new images will replace existing ones.</div>
      </div>

      <button type="submit" class="btn-submit">
        <i class="fas fa-save me-2"></i> Update Category
      </button>
    </form>
  </div>
</div>
@endsection
