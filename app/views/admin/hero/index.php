<?php ob_start(); ?>

<div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
    <div>
        <p class="text-xs font-semibold tracking-widest uppercase text-primary-600">Homepage</p>
        <h1 class="mt-2 text-2xl font-bold tracking-tight text-gray-900">Hero slides</h1>
        <p class="mt-1 text-sm text-gray-500">Upload images that rotate inside the hero card on the homepage. Drag to reorder, click a card to edit its caption.</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="<?= APP_URL ?>" target="_blank" class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50">
            <i class="fa-solid fa-up-right-from-square text-[11px]"></i>
            View homepage
        </a>
    </div>
</div>

<div id="hero-drop-zone" class="card mb-6 flex cursor-pointer flex-col items-center justify-center gap-3 border-2 border-dashed border-slate-300 bg-white p-8 text-center transition hover:border-primary-500 hover:bg-primary-50/30">
    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-primary-50 text-primary-600">
        <i class="fa-solid fa-cloud-arrow-up"></i>
    </div>
    <div>
        <p class="text-sm font-semibold text-slate-900">Drop images here or click to upload</p>
        <p class="mt-1 text-xs text-slate-500">You can drop or pick several at once · JPG, PNG, GIF, WebP · auto-compressed</p>
    </div>
    <input id="hero-files" type="file" accept="image/*" class="hidden" multiple>
    <div id="hero-upload-progress" class="hidden w-full max-w-md text-left text-xs"></div>
</div>

<div class="card">
    <div class="mb-4 flex items-center justify-between">
        <h2 class="text-sm font-semibold text-slate-900">Current slides</h2>
        <span class="rounded-full bg-slate-100 px-3 py-0.5 text-[11px] font-medium text-slate-700" id="hero-count"><?= count($slides ?? []) ?> total</span>
    </div>
    <div id="hero-empty" class="<?= empty($slides) ? '' : 'hidden' ?> rounded-xl border border-dashed border-slate-200 bg-slate-50/60 py-12 text-center">
        <p class="text-sm text-slate-500">No slides yet. Drop images above to start.</p>
    </div>
    <ul id="hero-list" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <?php foreach (($slides ?? []) as $slide):
            $src = !empty($slide['image_path']) ? rtrim(APP_URL, '/') . '/' . ltrim($slide['image_path'], '/') : '';
        ?>
        <li class="hero-tile group relative flex flex-col gap-2 rounded-xl border border-slate-200 bg-white p-3" data-id="<?= (int) $slide['id'] ?>">
            <div class="relative aspect-[4/3] overflow-hidden rounded-lg bg-slate-100">
                <img src="<?= htmlspecialchars($src) ?>" alt="" class="h-full w-full object-cover">
                <span class="absolute left-2 top-2 inline-flex h-7 w-7 cursor-grab items-center justify-center rounded-md bg-black/55 text-white opacity-0 transition group-hover:opacity-100" title="Drag to reorder">
                    <i class="fa-solid fa-grip-vertical text-xs"></i>
                </span>
                <label class="absolute right-2 top-2 inline-flex items-center gap-1.5 rounded-full bg-white/95 px-2.5 py-0.5 text-[11px] font-semibold text-slate-700 shadow ring-1 ring-black/5">
                    <input type="checkbox" class="hero-active h-3 w-3 accent-primary-600" <?= !empty($slide['is_active']) ? 'checked' : '' ?>>
                    <span>Active</span>
                </label>
            </div>
            <input type="text" class="hero-caption input rounded-md px-2 py-1.5 text-xs" placeholder="Caption (optional)" value="<?= htmlspecialchars($slide['caption'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            <div class="flex items-center justify-between">
                <span class="text-[11px] text-slate-400">#<?= (int) $slide['id'] ?></span>
                <button type="button" class="hero-delete inline-flex items-center gap-1 text-xs font-medium text-red-600 hover:text-red-700">
                    <i class="fa-solid fa-trash text-[11px]"></i>
                    Delete
                </button>
            </div>
        </li>
        <?php endforeach; ?>
    </ul>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js" defer></script>
<script>
(function () {
    var ADMIN_URL = <?= json_encode(rtrim(ADMIN_URL, '/') . '/index.php') ?>;
    var dropZone = document.getElementById('hero-drop-zone');
    var fileInput = document.getElementById('hero-files');
    var list = document.getElementById('hero-list');
    var empty = document.getElementById('hero-empty');
    var count = document.getElementById('hero-count');
    var progress = document.getElementById('hero-upload-progress');
    if (!dropZone || !fileInput || !list) return;

    function refreshCount() {
        var n = list.querySelectorAll('li.hero-tile').length;
        count.textContent = n + ' total';
        if (n === 0) empty.classList.remove('hidden');
        else empty.classList.add('hidden');
    }

    function tileFromServer(payload) {
        var li = document.createElement('li');
        li.className = 'hero-tile group relative flex flex-col gap-2 rounded-xl border border-slate-200 bg-white p-3';
        li.setAttribute('data-id', String(payload.id));
        li.innerHTML =
            '<div class="relative aspect-[4/3] overflow-hidden rounded-lg bg-slate-100">' +
                '<img src="' + payload.url + '" alt="" class="h-full w-full object-cover">' +
                '<span class="absolute left-2 top-2 inline-flex h-7 w-7 cursor-grab items-center justify-center rounded-md bg-black/55 text-white opacity-0 transition group-hover:opacity-100" title="Drag to reorder"><i class="fa-solid fa-grip-vertical text-xs"></i></span>' +
                '<label class="absolute right-2 top-2 inline-flex items-center gap-1.5 rounded-full bg-white/95 px-2.5 py-0.5 text-[11px] font-semibold text-slate-700 shadow ring-1 ring-black/5">' +
                    '<input type="checkbox" class="hero-active h-3 w-3 accent-primary-600" checked>' +
                    '<span>Active</span>' +
                '</label>' +
            '</div>' +
            '<input type="text" class="hero-caption input rounded-md px-2 py-1.5 text-xs" placeholder="Caption (optional)" value="">' +
            '<div class="flex items-center justify-between">' +
                '<span class="text-[11px] text-slate-400">#' + payload.id + '</span>' +
                '<button type="button" class="hero-delete inline-flex items-center gap-1 text-xs font-medium text-red-600 hover:text-red-700"><i class="fa-solid fa-trash text-[11px]"></i>Delete</button>' +
            '</div>';
        return li;
    }

    function uploadOne(file) {
        return new Promise(function (resolve) {
            var fd = new FormData();
            fd.append('file', file);
            var xhr = new XMLHttpRequest();
            xhr.open('POST', ADMIN_URL + '?action=hero_slide_upload', true);
            xhr.onload = function () {
                try {
                    var data = JSON.parse(xhr.responseText || '{}');
                    if (xhr.status >= 200 && xhr.status < 300 && data.ok) {
                        list.appendChild(tileFromServer(data));
                        refreshCount();
                        resolve({ ok: true });
                    } else {
                        resolve({ ok: false, error: data.error || ('HTTP ' + xhr.status) });
                    }
                } catch (e) {
                    resolve({ ok: false, error: 'Bad server response' });
                }
            };
            xhr.onerror = function () { resolve({ ok: false, error: 'Network error' }); };
            xhr.send(fd);
        });
    }

    function showProgress(items) {
        if (!progress) return;
        progress.classList.remove('hidden');
        progress.innerHTML = items.map(function (it) {
            return '<div class="flex items-center justify-between border-b border-slate-100 py-1.5"><span class="truncate pr-2">' +
                it.name + '</span><span class="' + (it.status === 'done' ? 'text-emerald-600' : it.status === 'error' ? 'text-red-600' : 'text-slate-500') + '">' +
                (it.status === 'pending' ? 'Uploading…' : it.status === 'done' ? 'Done' : 'Failed: ' + (it.error || '')) +
                '</span></div>';
        }).join('');
    }

    function handleFiles(files) {
        var arr = Array.prototype.filter.call(files, function (f) { return /^image\//.test(f.type); });
        if (!arr.length) return;
        var items = arr.map(function (f) { return { name: f.name, status: 'pending' }; });
        showProgress(items);

        var concurrency = 3;
        var i = 0;
        function next() {
            if (i >= arr.length) {
                setTimeout(function () { if (progress) progress.classList.add('hidden'); }, 1500);
                return;
            }
            var file = arr[i];
            var item = items[i];
            i++;
            uploadOne(file).then(function (res) {
                item.status = res.ok ? 'done' : 'error';
                if (!res.ok) item.error = res.error || '';
                showProgress(items);
                next();
            });
        }
        for (var k = 0; k < concurrency; k++) next();
    }

    dropZone.addEventListener('click', function (e) {
        if (e.target.tagName !== 'INPUT') fileInput.click();
    });
    fileInput.addEventListener('change', function () {
        if (fileInput.files && fileInput.files.length) handleFiles(fileInput.files);
        fileInput.value = '';
    });
    ['dragenter', 'dragover'].forEach(function (evt) {
        dropZone.addEventListener(evt, function (e) {
            e.preventDefault(); e.stopPropagation();
            dropZone.classList.add('border-primary-500', 'bg-primary-50/40');
        });
    });
    ['dragleave', 'drop'].forEach(function (evt) {
        dropZone.addEventListener(evt, function (e) {
            e.preventDefault(); e.stopPropagation();
            dropZone.classList.remove('border-primary-500', 'bg-primary-50/40');
        });
    });
    dropZone.addEventListener('drop', function (e) {
        if (e.dataTransfer && e.dataTransfer.files) handleFiles(e.dataTransfer.files);
    });

    function debounce(fn, ms) {
        var t;
        return function () {
            var ctx = this, args = arguments;
            clearTimeout(t);
            t = setTimeout(function () { fn.apply(ctx, args); }, ms);
        };
    }

    function update(id, payload) {
        var fd = new FormData();
        fd.append('id', String(id));
        Object.keys(payload).forEach(function (k) { fd.append(k, payload[k]); });
        return fetch(ADMIN_URL + '?action=hero_slide_update', { method: 'POST', body: fd });
    }

    list.addEventListener('input', debounce(function (e) {
        var t = e.target;
        var tile = t.closest('.hero-tile');
        if (!tile) return;
        var id = parseInt(tile.getAttribute('data-id'), 10);
        if (!id) return;
        if (t.classList.contains('hero-caption')) {
            update(id, { caption: t.value });
        }
    }, 350));

    list.addEventListener('change', function (e) {
        var t = e.target;
        var tile = t.closest('.hero-tile');
        if (!tile) return;
        var id = parseInt(tile.getAttribute('data-id'), 10);
        if (!id) return;
        if (t.classList.contains('hero-active')) {
            update(id, { is_active: t.checked ? '1' : '0' });
        }
    });

    list.addEventListener('click', function (e) {
        var btn = e.target.closest('.hero-delete');
        if (!btn) return;
        var tile = btn.closest('.hero-tile');
        if (!tile) return;
        if (!confirm('Delete this slide?')) return;
        var id = parseInt(tile.getAttribute('data-id'), 10);
        window.location = ADMIN_URL + '?action=hero_slide_delete&id=' + id;
    });

    function bindSortable() {
        if (typeof Sortable === 'undefined') return setTimeout(bindSortable, 100);
        Sortable.create(list, {
            animation: 150,
            ghostClass: 'opacity-50',
            onEnd: function () {
                var ids = Array.prototype.map.call(
                    list.querySelectorAll('li.hero-tile'),
                    function (li) { return parseInt(li.getAttribute('data-id'), 10); }
                );
                fetch(ADMIN_URL + '?action=hero_slide_reorder', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ ids: ids })
                });
            }
        });
    }
    bindSortable();
})();
</script>

<?php
$content = ob_get_clean();
$title = 'Hero Slides';
require __DIR__ . '/../layout.php';
?>
