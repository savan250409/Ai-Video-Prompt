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
      <form method="POST" action="{{ route('ngendev.categories.toggleCouple') }}" style="display:inline;">
        @csrf
        <button type="submit" class="btn-couple-toggle {{ $coupleActive ? 'couple-on' : 'couple-off' }}">
          <i class="fas fa-user-friends me-1"></i>
          Couple: {{ $coupleActive ? 'ON' : 'OFF' }}
        </button>
      </form>
      <button type="button" class="btn-indexing" onclick="openSortModal()">
        <i class="fas fa-sort-numeric-down me-1"></i> Indexing
      </button>
      <a href="{{ route('ngendev.categories.create') }}" class="btn-add-cat">
        <i class="fas fa-plus me-1"></i> Add Category
      </a>
    </div>
  </div>

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
              <img src="{{ asset('upload/ngendev/videos/' . rawurlencode($cat->category_name) . '/category_thumbnail_image/' . $img) }}"
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

{{-- ── Sort Order Modal ──────────────────────────────────────────── --}}
<div id="sortModal" class="ngd-modal-overlay" style="display:none;" onclick="closeSortModal(event)">
  <div class="ngd-modal-box">
    <div class="ngd-modal-header">
      <span><i class="fas fa-sort me-2"></i>Category Sort Order</span>
      <button type="button" class="ngd-modal-close" onclick="closeSortModal()"><i class="fas fa-times"></i></button>
    </div>
    <p class="ngd-modal-sub">Drag categories to set the order shown in the API. Top = first in API.</p>
    <div id="sortList" class="ngd-sort-list">
      @foreach($allCategories as $cat)
      <div class="ngd-sort-item" data-id="{{ $cat->id }}">
        <i class="fas fa-grip-vertical ngd-sort-handle"></i>
        <span class="ngd-sort-num">{{ $loop->iteration }}</span>
        <span class="ngd-sort-name">{{ $cat->category_name }}</span>
        <span class="ngd-sort-id">#{{ $cat->id }}</span>
        <span class="ngd-sort-type {{ $cat->type === 'Couple' ? 'type-couple' : 'type-solo' }}">{{ $cat->type }}</span>
      </div>
      @endforeach
    </div>
    <div class="ngd-modal-footer">
      <button type="button" class="ngd-btn-cancel" onclick="closeSortModal()">Cancel</button>
      <button type="button" class="ngd-btn-save" id="sortSaveBtn" onclick="saveSortOrder()">
        <i class="fas fa-save me-1"></i> Save Order
      </button>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
(function () {
  var sortable = null;

  window.openSortModal = function () {
    updateNumbers();
    document.getElementById('sortModal').style.display = 'flex';
    if (!sortable) {
      sortable = Sortable.create(document.getElementById('sortList'), {
        handle: '.ngd-sort-handle',
        animation: 150,
        onEnd: updateNumbers,
      });
    }
  };

  window.closeSortModal = function (e) {
    if (e && e.target !== document.getElementById('sortModal')) return;
    document.getElementById('sortModal').style.display = 'none';
  };

  function updateNumbers() {
    document.querySelectorAll('#sortList .ngd-sort-item').forEach(function (el, i) {
      el.querySelector('.ngd-sort-num').textContent = i + 1;
    });
  }

  window.saveSortOrder = function () {
    var btn = document.getElementById('sortSaveBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Saving…';

    var ids = Array.from(document.querySelectorAll('#sortList .ngd-sort-item'))
                   .map(function (el) { return el.dataset.id; });

    fetch('{{ route("ngendev.categories.saveOrder") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({ order: ids })
    })
    .then(function (r) { return r.json(); })
    .then(function (d) {
      if (d.success) {
        document.getElementById('sortModal').style.display = 'none';
        Swal.fire({ icon: 'success', title: 'Saved', text: 'Category order updated.', timer: 2000, showConfirmButton: false });
      }
    })
    .catch(function () {
      Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to save order.' });
    })
    .finally(function () {
      btn.disabled = false;
      btn.innerHTML = '<i class="fas fa-save me-1"></i> Save Order';
    });
  };
})();
</script>
@endsection

