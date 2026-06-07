@extends('layouts.kaiadmin')

@section('title', 'Video Category Management')

@section('content')
<div class="page-inner">

  <!-- Page Header -->
  <div class="page-header-bar">
    <div class="header-left">
      <h3><i class="fas fa-tags me-2 text-primary"></i> Ngendev Video Category Management</h3>
      <p>Manage all Ngendev video categories in the system</p>
    </div>
    <div class="header-right">
      <span class="stat-pill">
        <i class="fas fa-layer-group"></i> Total:{{ $totalCount }} Categories
      </span>
      <span class="stat-pill">
        <i class="fas fa-user-friends"></i> Couple Status: {{ $coupleCount > 0 ? $coupleCount : '-' }}
      </span>
      <a href="{{ route('ngendev.categories.reindex') }}" class="btn-indexing">
        <i class="fas fa-sort-numeric-down me-1"></i> Indexing
      </a>
      <a href="{{ route('ngendev.categories.create') }}" class="btn-add-cat">
        <i class="fas fa-plus me-1"></i> Add Category
      </a>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" style="border-radius:10px;font-size:13px;" role="alert">
      <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <!-- Table Card -->
  <div class="table-card">
    <div class="table-controls">
      <div class="show-entries">
        Show
        <select id="perPage" onchange="changePerPage(this.value)">
          <option value="10" {{ request('per_page',10)==10?'selected':'' }}>10</option>
          <option value="25" {{ request('per_page',10)==25?'selected':'' }}>25</option>
          <option value="50" {{ request('per_page',10)==50?'selected':'' }}>50</option>
        </select>
        entries
      </div>
      <div class="search-box">
        <form method="GET" action="{{ route('ngendev.categories.index') }}" style="display:flex;gap:6px;">
          <input type="text" name="search" value="{{ request('search') }}" placeholder="Search categories...">
          <button type="submit" class="btn-filter"><i class="fas fa-search"></i></button>
        </form>
      </div>
    </div>

    <table class="data-table">
      <thead>
        <tr>
          <th>Image</th>
          <th>Category Name</th>
          <th>Type</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($categories as $cat)
        <tr>
          <td>
            @php $img = $cat->first_image; @endphp
            @if($img)
              <img src="{{ asset('upload/ngendev/category_thumbnail_image/' . $img) }}"
                   class="cat-img" alt="{{ $cat->category_name }}"
                   onerror="this.style.display='none';this.nextElementSibling.style.display='inline-flex';">
              <span class="cat-img-placeholder" style="display:none;"><i class="fas fa-image"></i></span>
            @else
              <span class="cat-img-placeholder"><i class="fas fa-image"></i></span>
            @endif
          </td>
          <td><strong>{{ $cat->category_name }}</strong></td>
          <td>
            <select class="type-select"
                    onchange="updateType({{ $cat->id }}, this.value, this)">
              <option value="Solo"   {{ $cat->type=='Solo'  ?'selected':'' }}>Solo</option>
              <option value="Couple" {{ $cat->type=='Couple'?'selected':'' }}>Couple</option>
            </select>
          </td>
          <td>
            <div class="toggle-wrap">
              <label class="toggle">
                <input type="checkbox" {{ $cat->status ? 'checked' : '' }}
                       onchange="updateStatus({{ $cat->id }}, this.checked ? 1 : 0, this)">
                <span class="slider"></span>
              </label>
              <span class="badge-{{ $cat->status ? 'active' : 'inactive' }}" id="badge-{{ $cat->id }}">
                {{ $cat->status ? 'Active' : 'Inactive' }}
              </span>
            </div>
          </td>
          <td>
            <div style="display:flex;gap:6px;">
              <a href="{{ route('ngendev.categories.edit', $cat->id) }}" class="btn-edit" title="Edit">
                <i class="fas fa-pencil-alt"></i>
              </a>
              <form method="POST" action="{{ route('ngendev.categories.destroy', $cat->id) }}"
                    onsubmit="return confirm('Delete this category and all its videos?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-delete" title="Delete">
                  <i class="fas fa-trash"></i>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" class="text-center text-muted py-4">No categories found.</td>
        </tr>
        @endforelse
      </tbody>
    </table>

    <!-- Pagination -->
    <div class="pagination-row">
      <div class="pagination-info">
        Showing {{ $categories->firstItem() ?? 0 }} to {{ $categories->lastItem() ?? 0 }}
        of {{ $categories->total() }} entries
      </div>
      <div class="pagination-btns">
        @if($categories->onFirstPage())
          <span class="page-btn" style="opacity:.5;cursor:default;">Previous</span>
        @else
          <a class="page-btn" href="{{ $categories->previousPageUrl() }}">Previous</a>
        @endif

        @foreach($categories->getUrlRange(1, $categories->lastPage()) as $page => $url)
          <a class="page-btn {{ $page == $categories->currentPage() ? 'active' : '' }}"
             href="{{ $url }}">{{ $page }}</a>
        @endforeach

        @if($categories->hasMorePages())
          <a class="page-btn" href="{{ $categories->nextPageUrl() }}">Next</a>
        @else
          <span class="page-btn" style="opacity:.5;cursor:default;">Next</span>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection

