<?php ob_start(); ?>
<?php
$faculties = ['Engineering', 'Construction', 'Technology', 'Automotive', 'General'];
$programId = isset($program['id']) ? (int) $program['id'] : 0;
$media = $media ?? [];
?>

<div class="mb-6">
    <a href="<?= ADMIN_URL ?>?action=programs" class="inline-flex items-center text-sm text-primary-600 hover:text-primary-800">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Back to Programs
    </a>
    <h1 class="text-2xl font-bold text-gray-900 mt-3"><?= isset($program) ? 'Edit Program' : 'Add Program' ?></h1>
    <p class="mt-1 text-sm text-gray-500">Describe each academic or technical program clearly for prospective students.</p>
</div>

<div class="card max-w-4xl">
    <form method="POST" action="<?= ADMIN_URL ?>?action=program_save" class="space-y-6">
        <?php if (isset($program)): ?>
        <input type="hidden" name="id" value="<?= (int) $program['id'] ?>">
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="label">Program Name</label>
                <input type="text" name="name" required class="input" value="<?= htmlspecialchars($program['name'] ?? '') ?>" placeholder="Mechanical Engineering">
            </div>
            <div>
                <label class="label">URL slug</label>
                <input type="text" name="slug" class="input font-mono text-sm" value="<?= htmlspecialchars($program['slug'] ?? '') ?>" placeholder="mechanical-engineering">
                <p class="mt-1 text-xs text-gray-500">Leave blank to generate from the program name. Must be unique.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="label">Faculty</label>
                <select name="faculty" class="input">
                    <option value="">— Select —</option>
                    <?php foreach ($faculties as $f): ?>
                    <option value="<?= htmlspecialchars($f) ?>" <?= (($program['faculty'] ?? '') === $f) ? 'selected' : '' ?>><?= htmlspecialchars($f) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="label">Department</label>
                <input type="text" name="department" class="input" value="<?= htmlspecialchars($program['department'] ?? '') ?>" placeholder="Department name">
            </div>
        </div>

        <div>
            <label class="label">Cover image path</label>
            <input type="text" name="cover_image" class="input font-mono text-sm" value="<?= htmlspecialchars($program['cover_image'] ?? '') ?>" placeholder="uploads/programs/example.jpg">
            <p class="mt-1 text-xs text-gray-500">Relative to the site root (e.g. <code class="text-xs">uploads/programs/…</code>) or set from gallery “Set cover”.</p>
        </div>

        <div>
            <label class="label">Description</label>
            <textarea name="description" rows="5" class="input text-sm leading-relaxed" placeholder="Short summary for listings and hero"><?= htmlspecialchars($program['description'] ?? '') ?></textarea>
        </div>

        <div>
            <label class="label">Detail content</label>
            <textarea name="detail_content" rows="10" class="input text-sm leading-relaxed" placeholder="Longer text for the program detail page"><?= htmlspecialchars($program['detail_content'] ?? '') ?></textarea>
        </div>

        <div class="flex flex-wrap gap-3">
            <button type="submit" class="btn btn-primary">
                <?= isset($program) ? 'Update Program' : 'Add Program' ?>
            </button>
            <a href="<?= ADMIN_URL ?>?action=programs" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php if ($programId > 0): ?>
<div class="card max-w-4xl mt-8 space-y-8">
    <div>
        <h2 class="text-lg font-semibold text-gray-900">Gallery images</h2>
        <p class="text-sm text-gray-500 mt-1">Drop image files here (JPEG, PNG, etc.). Files are converted to JPEG.</p>
        <div id="drop-images" class="mt-3 flex min-h-[140px] cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 px-4 py-8 text-center text-sm text-gray-600 hover:border-primary-400 hover:bg-primary-50/30 transition-colors">
            <i class="fa-solid fa-image text-2xl text-primary-500 mb-2"></i>
            <span>Drag and drop images here or click to choose files</span>
            <input type="file" id="file-images" class="hidden" accept="image/*" multiple>
        </div>
    </div>

    <div>
        <h2 class="text-lg font-semibold text-gray-900">Videos (MP4)</h2>
        <p class="text-sm text-gray-500 mt-1">Upload MP4 files or add a YouTube / external URL below.</p>
        <div id="drop-videos" class="mt-3 flex min-h-[120px] cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 px-4 py-8 text-center text-sm text-gray-600 hover:border-primary-400 hover:bg-primary-50/30 transition-colors">
            <i class="fa-solid fa-film text-2xl text-primary-500 mb-2"></i>
            <span>Drag and drop MP4 here or click to choose</span>
            <input type="file" id="file-videos" class="hidden" accept="video/mp4,.mp4" multiple>
        </div>
        <form id="form-video-url" class="mt-4 flex flex-col sm:flex-row gap-2 sm:items-end">
            <div class="flex-1">
                <label class="label mb-1">External video URL</label>
                <input type="url" name="external_url" class="input text-sm" placeholder="https://www.youtube.com/watch?v=…">
            </div>
            <button type="submit" class="btn btn-secondary whitespace-nowrap">Add URL</button>
        </form>
    </div>

    <div>
        <h2 class="text-lg font-semibold text-gray-900 mb-2">Media order</h2>
        <p class="text-sm text-gray-500 mb-3">Drag rows to reorder. Order is saved automatically.</p>
        <ul id="media-sortable" class="space-y-2">
            <?php foreach ($media as $m): ?>
            <?php
            $isVideo = ($m['media_type'] ?? '') === 'video';
            $thumb = '';
            if (!$isVideo && !empty($m['file_path'])) {
                $thumb = rtrim(APP_URL, '/') . '/' . ltrim($m['file_path'], '/');
            }
            ?>
            <li class="media-row flex flex-wrap items-start gap-3 rounded-lg border border-gray-200 bg-white p-3" data-id="<?= (int) $m['id'] ?>">
                <span class="drag-handle cursor-grab active:cursor-grabbing text-gray-400 pt-2 select-none" title="Drag to reorder"><i class="fa-solid fa-grip-vertical"></i></span>
                <div class="h-16 w-24 flex-shrink-0 overflow-hidden rounded-md bg-gray-100 border border-gray-100 flex items-center justify-center">
                    <?php if ($isVideo): ?>
                    <span class="text-xs font-semibold uppercase text-primary-700"><i class="fa-solid fa-video mr-1"></i>Video</span>
                    <?php elseif ($thumb): ?>
                    <img src="<?= htmlspecialchars($thumb) ?>" alt="" class="h-full w-full object-cover">
                    <?php else: ?>
                    <span class="text-xs text-gray-400">—</span>
                    <?php endif; ?>
                </div>
                <div class="flex-1 min-w-[200px] space-y-2">
                    <form class="caption-form flex gap-2 items-end" data-media-id="<?= (int) $m['id'] ?>">
                        <div class="flex-1">
                            <label class="text-xs text-gray-500">Caption</label>
                            <input type="text" name="caption" class="input text-sm py-1.5" value="<?= htmlspecialchars($m['caption'] ?? '') ?>" placeholder="Optional caption">
                        </div>
                        <button type="submit" class="text-xs font-medium text-primary-600 hover:text-primary-800 px-2 py-1.5">Save</button>
                    </form>
                    <div class="flex flex-wrap gap-3 text-xs">
                        <?php if (!$isVideo && !empty($m['file_path'])): ?>
                        <a href="<?= ADMIN_URL ?>?action=program_media_set_cover&id=<?= (int) $m['id'] ?>" class="font-medium text-accent-700 hover:text-accent-900">Set cover</a>
                        <?php endif; ?>
                        <a href="<?= ADMIN_URL ?>?action=program_media_delete&id=<?= (int) $m['id'] ?>" class="font-medium text-red-600 hover:text-red-800" onclick="return confirm('Delete this media item?');">Delete</a>
                    </div>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php if (empty($media)): ?>
        <p class="text-sm text-gray-500 mt-2" id="media-empty-hint">No media yet. Upload images or videos above.</p>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
(function () {
    var ADMIN_URL = <?= json_encode(ADMIN_URL) ?>;
    var PROGRAM_ID = <?= (int) $programId ?>;

    function uploadFiles(action, files) {
        if (!files || !files.length) return;
        var i, fd, p = Promise.resolve();
        function one(file) {
            fd = new FormData();
            fd.append('program_id', String(PROGRAM_ID));
            fd.append('file', file);
            return fetch(ADMIN_URL + '?action=' + action, { method: 'POST', body: fd, credentials: 'same-origin' })
                .then(function (r) { return r.json(); })
                .then(function (j) {
                    if (!j.ok) throw new Error(j.error || 'Upload failed');
                });
        }
        for (i = 0; i < files.length; i++) {
            p = p.then(one.bind(null, files[i]));
        }
        return p;
    }

    function wireDrop(zoneId, inputId, action) {
        var zone = document.getElementById(zoneId);
        var input = document.getElementById(inputId);
        if (!zone || !input) return;
        zone.addEventListener('click', function () { input.click(); });
        ['dragenter', 'dragover'].forEach(function (ev) {
            zone.addEventListener(ev, function (e) { e.preventDefault(); e.stopPropagation(); zone.classList.add('border-primary-500', 'bg-primary-50'); });
        });
        ['dragleave', 'drop'].forEach(function (ev) {
            zone.addEventListener(ev, function (e) { e.preventDefault(); e.stopPropagation(); zone.classList.remove('border-primary-500', 'bg-primary-50'); });
        });
        zone.addEventListener('drop', function (e) {
            var files = e.dataTransfer.files;
            uploadFiles(action, files).then(function () { window.location.reload(); }).catch(function (err) { alert(err.message || String(err)); });
        });
        input.addEventListener('change', function () {
            uploadFiles(action, input.files).then(function () { window.location.reload(); }).catch(function (err) { alert(err.message || String(err)); });
        });
    }

    wireDrop('drop-images', 'file-images', 'program_media_upload');
    wireDrop('drop-videos', 'file-videos', 'program_video_upload');

    var urlForm = document.getElementById('form-video-url');
    if (urlForm) {
        urlForm.addEventListener('submit', function (e) {
            e.preventDefault();
            var fd = new FormData(urlForm);
            fd.append('program_id', String(PROGRAM_ID));
            fetch(ADMIN_URL + '?action=program_video_url_save', { method: 'POST', body: fd, credentials: 'same-origin' })
                .then(function (r) { return r.json(); })
                .then(function (j) {
                    if (!j.ok) throw new Error(j.error || 'Failed');
                    window.location.reload();
                })
                .catch(function (err) { alert(err.message || String(err)); });
        });
    }

    var list = document.getElementById('media-sortable');
    if (list && typeof Sortable !== 'undefined') {
        Sortable.create(list, {
            animation: 150,
            handle: '.drag-handle',
            onEnd: function () {
                var ids = [];
                list.querySelectorAll('.media-row').forEach(function (el) { ids.push(parseInt(el.getAttribute('data-id'), 10)); });
                fetch(ADMIN_URL + '?action=program_media_reorder', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ ids: ids }),
                    credentials: 'same-origin'
                })
                    .then(function (r) { return r.json(); })
                    .then(function (j) { if (!j.ok) throw new Error(j.error || 'Reorder failed'); })
                    .catch(function (err) { alert(err.message || String(err)); });
            }
        });
    }

    document.querySelectorAll('.caption-form').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            var id = form.getAttribute('data-media-id');
            var cap = form.querySelector('input[name="caption"]');
            var fd = new FormData();
            fd.append('id', id);
            fd.append('caption', cap ? cap.value : '');
            fetch(ADMIN_URL + '?action=program_media_caption_save', { method: 'POST', body: fd, credentials: 'same-origin' })
                .then(function (r) { return r.json(); })
                .then(function (j) { if (!j.ok) throw new Error(j.error || 'Save failed'); })
                .catch(function (err) { alert(err.message || String(err)); });
        });
    });
})();
</script>
<?php endif; ?>

<?php
$content = ob_get_clean();
$title = isset($program) ? 'Edit Program' : 'Add Program';
require __DIR__ . '/../layout.php';
?>
