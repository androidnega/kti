<?php
ob_start();
$isEdit = !empty($event);
$cover = $isEdit && !empty($event['cover_image']) ? rtrim(APP_URL, '/') . '/' . ltrim($event['cover_image'], '/') : '';
$eventDateValue = '';
if ($isEdit && !empty($event['event_date'])) {
    $ts = strtotime($event['event_date']);
    if ($ts) {
        $eventDateValue = date('Y-m-d\TH:i', $ts);
    }
}
$endDateValue = '';
if ($isEdit && !empty($event['end_date'])) {
    $ts = strtotime($event['end_date']);
    if ($ts) {
        $endDateValue = date('Y-m-d\TH:i', $ts);
    }
}
$contentValue = '';
if ($isEdit && isset($event['content'])) {
    $contentValue = htmlspecialchars_decode((string) $event['content']);
}
?>

<div class="mb-6 flex items-center gap-3">
    <a href="<?= ADMIN_URL ?>?action=events" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 hover:text-slate-900">
        <i class="fa-solid fa-arrow-left text-sm"></i>
    </a>
    <div>
        <p class="text-xs font-semibold tracking-widest uppercase text-primary-600">Campus life</p>
        <h1 class="mt-1 text-2xl font-bold tracking-tight text-gray-900"><?= $isEdit ? 'Edit event' : 'New event' ?></h1>
    </div>
</div>

<form action="<?= ADMIN_URL ?>?action=event_save" method="POST" enctype="multipart/form-data" class="space-y-6">
    <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?= (int) $event['id'] ?>">
    <?php endif; ?>

    <div class="card space-y-5">
        <div>
            <label class="label" for="event-title">Event title <span class="text-red-500">*</span></label>
            <input id="event-title" name="title" type="text" required value="<?= htmlspecialchars($event['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="input rounded-lg px-3 py-2.5 text-sm" placeholder="e.g. Speech and Prize Giving Day 2026">
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <div>
                <label class="label" for="event-slug">URL slug</label>
                <input id="event-slug" name="slug" type="text" value="<?= htmlspecialchars($event['slug'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="input rounded-lg px-3 py-2.5 text-sm font-mono" placeholder="auto-generated from title">
                <p class="mt-1 text-xs text-slate-500">Leave blank to auto-generate.</p>
            </div>
            <div>
                <label class="label" for="event-location">Location</label>
                <input id="event-location" name="location" type="text" value="<?= htmlspecialchars($event['location'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="input rounded-lg px-3 py-2.5 text-sm" placeholder="e.g. KTI Main Hall">
            </div>
            <div>
                <label class="label" for="event-date">Starts</label>
                <input id="event-date" name="event_date" type="datetime-local" value="<?= htmlspecialchars($eventDateValue) ?>" class="input rounded-lg px-3 py-2.5 text-sm">
            </div>
            <div>
                <label class="label" for="event-end">Ends (optional)</label>
                <input id="event-end" name="end_date" type="datetime-local" value="<?= htmlspecialchars($endDateValue) ?>" class="input rounded-lg px-3 py-2.5 text-sm">
            </div>
        </div>

        <div>
            <label class="label" for="event-summary">Short summary</label>
            <textarea id="event-summary" name="summary" rows="3" class="input rounded-lg px-3 py-2.5 text-sm" placeholder="One short paragraph shown on the events list."><?= htmlspecialchars($event['summary'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>

        <div>
            <label class="label" for="event-content">Full details</label>
            <div id="event-content-quill" class="rounded-lg border border-slate-300 bg-white min-h-[220px] [&_.ql-container]:rounded-b-lg [&_.ql-container]:border-0 [&_.ql-toolbar]:rounded-t-lg [&_.ql-toolbar]:border-0 [&_.ql-toolbar]:border-b [&_.ql-toolbar]:border-slate-200 [&_.ql-editor]:min-h-[180px]"></div>
            <textarea id="event-content-hidden" name="content" class="hidden"><?= htmlspecialchars($contentValue, ENT_QUOTES, 'UTF-8') ?></textarea>
            <p class="mt-1 text-xs text-slate-500">Use headings, bullet lists, links and bold to format the details.</p>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
            <div class="md:col-span-2 flex items-center gap-3">
                <input id="event-published" name="is_published" type="checkbox" value="1" <?= !$isEdit || !empty($event['is_published']) ? 'checked' : '' ?> class="h-4 w-4 rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                <label for="event-published" class="text-sm text-slate-700">Published (visible on the public events page)</label>
            </div>
            <div>
                <label class="label" for="event-sort">Display order</label>
                <input id="event-sort" name="sort_order" type="number" value="<?= (int) ($event['sort_order'] ?? 0) ?>" class="input rounded-lg px-3 py-2.5 text-sm">
            </div>
        </div>
    </div>

    <div class="card">
        <h2 class="text-sm font-semibold text-slate-900">Cover image</h2>
        <p class="mt-1 text-xs text-slate-500">JPG, PNG, GIF or WebP — automatically compressed.</p>

        <div class="mt-4 flex flex-col gap-4 sm:flex-row sm:items-center">
            <div class="flex aspect-[16/10] w-full max-w-xs items-center justify-center overflow-hidden rounded-xl bg-slate-100 ring-1 ring-slate-200 sm:w-56" id="event-cover-wrap">
                <?php if ($cover): ?>
                    <img id="event-cover-preview" src="<?= htmlspecialchars($cover) ?>" alt="" class="h-full w-full object-cover">
                <?php else: ?>
                    <span class="text-xs text-slate-500">No cover yet</span>
                <?php endif; ?>
            </div>
            <div class="flex-1 space-y-2">
                <label class="inline-flex cursor-pointer items-center gap-2 rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-medium text-slate-800 hover:bg-slate-50">
                    <i class="fa-solid fa-upload text-xs text-slate-500"></i>
                    <span>Choose cover image</span>
                    <input id="event-cover-input" name="cover" type="file" accept="image/*" class="hidden">
                </label>
                <p id="event-cover-name" class="text-xs text-slate-500"><?= $cover ? 'Current cover will be kept until you replace it.' : 'No file chosen' ?></p>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-end gap-2">
        <a href="<?= ADMIN_URL ?>?action=events" class="btn btn-secondary px-4 py-2">Cancel</a>
        <button type="submit" class="btn btn-primary px-5 py-2"><?= $isEdit ? 'Save changes' : 'Create event' ?></button>
    </div>
</form>

<?php $quillVendorBase = rtrim(APP_URL, '/') . '/assets/vendor/quill'; ?>
<link rel="stylesheet" href="<?= htmlspecialchars($quillVendorBase) ?>/quill.snow.css">
<script src="<?= htmlspecialchars($quillVendorBase) ?>/quill.min.js"></script>

<script>
(function () {
    var input = document.getElementById('event-cover-input');
    var nameLabel = document.getElementById('event-cover-name');
    var wrap = document.getElementById('event-cover-wrap');
    if (input) {
        input.addEventListener('change', function () {
            var file = input.files && input.files[0];
            if (!file) {
                if (nameLabel) nameLabel.textContent = 'No file chosen';
                return;
            }
            if (nameLabel) nameLabel.textContent = file.name + ' · ' + Math.round(file.size / 1024) + ' KB';
            var reader = new FileReader();
            reader.onload = function (e) {
                if (!wrap) return;
                wrap.innerHTML = '';
                var img = document.createElement('img');
                img.src = e.target.result;
                img.alt = '';
                img.className = 'h-full w-full object-cover';
                wrap.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    }

    var host = document.getElementById('event-content-quill');
    var hidden = document.getElementById('event-content-hidden');
    var form = document.querySelector('form[action$="event_save"]');
    if (host && typeof Quill !== 'undefined') {
        var quill = new Quill(host, {
            theme: 'snow',
            placeholder: 'Speakers, agenda, what to expect…',
            modules: {
                toolbar: [
                    [{ header: [2, 3, false] }],
                    ['bold', 'italic', 'underline'],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    ['blockquote', 'link'],
                    ['clean'],
                ],
            },
        });
        if (hidden && hidden.value.trim() !== '') {
            quill.root.innerHTML = hidden.value;
        }
        var sync = function () { if (hidden) hidden.value = quill.root.innerHTML; };
        sync();
        quill.on('text-change', sync);
        if (form) {
            form.addEventListener('submit', sync);
        }
        quill.enable(true);
    }
})();
</script>

<?php
$content = ob_get_clean();
$title = $isEdit ? 'Edit Event' : 'New Event';
require __DIR__ . '/../layout.php';
?>
