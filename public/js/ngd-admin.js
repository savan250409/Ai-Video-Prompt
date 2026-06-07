/* ============================================================
   NGD Admin — Single JS file
   All custom logic for every admin page in one place.
   Routes are injected via window.NgdRoutes (set in layout).
   ============================================================ */

(function () {
  'use strict';

  /* ── Helpers ─────────────────────────────────────────────── */
  function csrfToken() {
    var m = document.querySelector('meta[name="csrf-token"]');
    return m ? m.content : '';
  }

  function postJson(url, data) {
    return fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken()
      },
      body: JSON.stringify(data)
    }).then(function (r) { return r.json(); });
  }

  /* ── Per-page selector (categories + videos) ─────────────── */
  function changePerPage(val) {
    var url = new URL(window.location.href);
    url.searchParams.set('per_page', val);
    url.searchParams.set('page', 1);
    window.location.href = url.toString();
  }
  window.changePerPage = changePerPage;

  /* ── Category: inline type update ───────────────────────── */
  function updateType(id, type) {
    var routes = window.NgdRoutes || {};
    if (!routes.categoryUpdateType) return;
    postJson(routes.categoryUpdateType, { id: id, type: type })
      .then(function (d) { if (!d.success) alert('Type update failed.'); });
  }
  window.updateType = updateType;

  /* ── Category: toggle status ─────────────────────────────── */
  function updateStatus(id, status) {
    var routes = window.NgdRoutes || {};
    if (!routes.categoryUpdateStatus) return;
    var badge = document.getElementById('badge-' + id);
    postJson(routes.categoryUpdateStatus, { id: id, status: status })
      .then(function (d) {
        if (d.success && badge) {
          badge.textContent = status ? 'Active' : 'Inactive';
          badge.className   = status ? 'badge-active' : 'badge-inactive';
        }
      });
  }
  window.updateStatus = updateStatus;

  /* ── Video: prompt character counter ────────────────────── */
  function updateChar(el) {
    var counter = document.getElementById('char-count');
    if (counter) counter.textContent = el.value.length + '/3990';
  }
  window.updateChar = updateChar;

  /* ── Category create: status hint based on type ─────────── */
  function toggleStatusHint() {
    var sel  = document.getElementById('typeSelect');
    var hint = document.getElementById('statusHint');
    if (!sel || !hint) return;
    hint.textContent = sel.value === 'Solo'
      ? 'Solo categories are always active.'
      : 'Toggle to activate or deactivate this couple category.';
  }
  window.toggleStatusHint = toggleStatusHint;

  /* ── Drag & Drop WebP upload zones ──────────────────────── */
  function initDropzones() {
    document.querySelectorAll('.ngd-dropzone').forEach(function (zone) {
      var input    = zone.querySelector('input[type="file"]');
      var body     = zone.querySelector('.ngd-dz-body');
      var preview  = zone.querySelector('.ngd-dz-preview');
      var prevImg  = preview ? preview.querySelector('img') : null;
      var prevName = preview ? preview.querySelector('.ngd-dz-fname') : null;
      var errMsg   = zone.querySelector('.ngd-dz-error-msg');

      function showPreview(file) {
        var reader = new FileReader();
        reader.onload = function (e) {
          if (prevImg)  prevImg.src = e.target.result;
          if (prevName) prevName.textContent = file.name;
          if (body)    body.style.display    = 'none';
          if (preview) preview.style.display = 'flex';
          zone.classList.remove('dz-error');
          if (errMsg) errMsg.style.display = 'none';
        };
        reader.readAsDataURL(file);
      }

      function handleFile(file) {
        if (file.type !== 'image/webp') {
          zone.classList.add('dz-error');
          if (errMsg) { errMsg.textContent = 'Only WebP images are allowed.'; errMsg.style.display = 'block'; }
          input.value = '';
          return;
        }
        var dt = new DataTransfer();
        dt.items.add(file);
        input.files = dt.files;
        showPreview(file);
      }

      zone.addEventListener('click', function (e) {
        if (e.target.classList.contains('ngd-dz-change')) return;
        input.click();
      });

      zone.addEventListener('dragover', function (e) {
        e.preventDefault();
        zone.classList.add('dz-over');
      });
      zone.addEventListener('dragleave', function (e) {
        if (!zone.contains(e.relatedTarget)) zone.classList.remove('dz-over');
      });
      zone.addEventListener('drop', function (e) {
        e.preventDefault();
        zone.classList.remove('dz-over');
        var file = e.dataTransfer.files[0];
        if (file) handleFile(file);
      });

      input.addEventListener('change', function () {
        if (input.files[0]) showPreview(input.files[0]);
      });

      /* "Change" link resets to body */
      var changeBtn = zone.querySelector('.ngd-dz-change');
      if (changeBtn) {
        changeBtn.addEventListener('click', function (e) {
          e.stopPropagation();
          input.value = '';
          if (body)    body.style.display    = '';
          if (preview) preview.style.display = 'none';
          zone.classList.remove('dz-error');
          if (errMsg) errMsg.style.display = 'none';
          input.click();
        });
      }
    });
  }

  /* ── Auto-init on DOMContentLoaded ───────────────────────── */
  document.addEventListener('DOMContentLoaded', function () {

    /* Prompt counter init */
    var promptArea = document.getElementById('promptArea');
    if (promptArea) updateChar(promptArea);

    /* Drag & drop zones */
    initDropzones();

    /* Auto-dismiss alerts after 4 s */
    document.querySelectorAll('.alert.alert-success').forEach(function (el) {
      setTimeout(function () {
        el.classList.remove('show');
        el.style.opacity = '0';
        setTimeout(function () { el.remove(); }, 300);
      }, 4000);
    });

  });

})();
