<?php ob_start(); ?>
<?php
$program = is_array($program ?? null) ? $program : [];
$faculties = ['Engineering', 'Construction', 'Technology', 'Automotive', 'General'];
$programId = isset($program['id']) ? (int) $program['id'] : 0;
$media = $media ?? [];
$slug = trim((string) ($program['slug'] ?? ''));
$publicUrl = $slug !== '' ? (rtrim(APP_URL, '/') . '/?url=program/' . rawurlencode($slug)) : '';
$isEdit = isset($program) && $programId > 0;
?>

<!-- Toast -->
<div id="admin-toast" class="pointer-events-none fixed bottom-6 left-1/2 z-[100] hidden max-w-[calc(100vw-2rem)] -translate-x-1/2 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-800 shadow-lg sm:left-auto sm:right-6 sm:translate-x-0" role="status"></div>

<div class="mb-6 sm:mb-8">
    <nav class="flex flex-wrap items-center gap-2 text-xs font-medium text-slate-500 sm:text-sm">
        <a href="<?= ADMIN_URL ?>?action=programs" class="inline-flex items-center gap-1.5 rounded-lg text-primary-700 hover:text-primary-900 hover:underline">
            <i class="fa-solid fa-arrow-left text-[10px] sm:text-xs"></i>
            Programs
        </a>
        <span class="text-slate-300" aria-hidden="true">/</span>
        <span class="text-slate-700"><?= $isEdit ? 'Edit department' : 'New program' ?></span>
    </nav>
    <div class="mt-4 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div class="min-w-0">
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl"><?= $isEdit ? 'Edit program' : 'Add program' ?></h1>
            <p class="mt-1 max-w-2xl text-sm leading-relaxed text-slate-600 sm:text-base">
                <?= $isEdit ? 'Update how this department appears on the site, gallery, and detail page.' : 'Create a program visitors can open from the Programs page. After you save once, gallery uploads apply immediately—you can add photos before filling every field.' ?>
            </p>
            <?php if ($isEdit && $slug !== ''): ?>
            <div class="mt-3 flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-200/80 px-3 py-1 font-mono text-[11px] text-slate-700 sm:text-xs"><?= htmlspecialchars($slug) ?></span>
                <a href="<?= htmlspecialchars($publicUrl) ?>" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-semibold text-primary-700 shadow-sm hover:border-primary-300 hover:bg-primary-50">
                    <i class="fa-solid fa-external-link-alt text-[10px]"></i>
                    Open public page
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="grid gap-6 lg:grid-cols-3 lg:gap-8">
    <div class="space-y-6 lg:col-span-2">
        <section class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
            <div class="border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white px-4 py-4 sm:px-6">
                <h2 class="flex items-center gap-2 text-base font-semibold text-slate-900">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary-900 text-accent-400"><i class="fa-solid fa-pen-to-square text-sm"></i></span>
                    Basics
                </h2>
                <p class="mt-1 text-xs text-slate-500 sm:text-sm">Name, faculty, and text shown on listing and detail pages.</p>
            </div>
            <div class="p-4 sm:p-6">
                <form id="program-main-form" method="POST" action="<?= htmlspecialchars(ADMIN_INDEX_URL) ?>?action=program_save" class="space-y-5 sm:space-y-6">
                    <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?= (int) $program['id'] ?>">
        <?php endif; ?>

                    <div class="grid gap-4 sm:grid-cols-2 sm:gap-5">
                        <div class="sm:col-span-2">
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-600 sm:text-sm sm:normal-case sm:tracking-normal">Program name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" required class="input w-full rounded-xl border-slate-200 px-4 py-3 text-base shadow-sm focus:border-primary-500 focus:ring-primary-500" value="<?= htmlspecialchars($program['name'] ?? '') ?>" placeholder="e.g. Mechanical Engineering" autocomplete="organization">
            </div>
            <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-600 sm:text-sm sm:normal-case sm:tracking-normal">URL slug</label>
                            <input type="text" name="slug" class="input w-full rounded-xl border-slate-200 px-4 py-3 font-mono text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500" value="<?= htmlspecialchars($program['slug'] ?? '') ?>" placeholder="auto-from-name" inputmode="url" autocapitalize="none" spellcheck="false">
                            <p class="mt-1.5 text-xs text-slate-500">Lowercase, hyphens. Blank = generated from the name.</p>
        </div>
            <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-600 sm:text-sm sm:normal-case sm:tracking-normal">Faculty</label>
                            <select name="faculty" class="input w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                <option value="">Choose faculty…</option>
                    <?php foreach ($faculties as $f): ?>
                    <option value="<?= htmlspecialchars($f) ?>" <?= (($program['faculty'] ?? '') === $f) ? 'selected' : '' ?>><?= htmlspecialchars($f) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
                        <div class="sm:col-span-2">
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-600 sm:text-sm sm:normal-case sm:tracking-normal">Department label</label>
                            <input type="text" name="department" class="input w-full rounded-xl border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500" value="<?= htmlspecialchars($program['department'] ?? '') ?>" placeholder="Shown with the program on the site">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-600 sm:text-sm sm:normal-case sm:tracking-normal">Cover image</label>
                            <?php
                            $coverPathVal = trim((string) ($program['cover_image'] ?? ''));
                            $coverPreviewAbs = $coverPathVal !== '' ? rtrim(APP_URL, '/') . '/' . ltrim($coverPathVal, '/') : '';
                            ?>
                            <?php if ($programId > 0): ?>
                            <div class="mt-2 flex flex-col gap-4 sm:flex-row sm:items-start">
                                <div class="relative h-28 w-40 shrink-0 overflow-hidden rounded-xl border border-slate-200 bg-slate-100">
                                    <img id="program-cover-preview-img" src="<?= $coverPreviewAbs !== '' ? htmlspecialchars($coverPreviewAbs) : '' ?>" alt="" class="h-full w-full object-cover <?= $coverPreviewAbs === '' ? 'hidden' : '' ?>" width="160" height="112">
                                    <div id="program-cover-preview-empty" class="<?= $coverPreviewAbs !== '' ? 'hidden' : '' ?> flex h-full w-full items-center justify-center px-2 text-center text-xs text-slate-400">No cover yet</div>
                                    <div id="program-cover-uploading" class="pointer-events-none absolute inset-0 hidden flex-col items-center justify-center bg-slate-900/75 text-white">
                                        <i class="fa-solid fa-spinner fa-spin text-xl"></i>
                                        <span class="mt-1 text-[10px] font-semibold uppercase tracking-wide">Uploading</span>
                                    </div>
                                </div>
                                <div class="min-w-0 flex-1 space-y-2">
                                    <input type="text" name="cover_image" id="program-cover-image-input" class="input w-full rounded-xl border-slate-200 px-4 py-3 font-mono text-xs shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm" value="<?= htmlspecialchars($coverPathVal) ?>" placeholder="uploads/programs/… (set by upload)">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <button type="button" id="program-cover-file-trigger" class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-800 hover:border-primary-400 hover:bg-primary-50">
                                            <i class="fa-solid fa-image text-primary-600"></i>
                                            Choose cover photo…
                                        </button>
                                        <input type="file" id="program-cover-file" class="sr-only" accept="image/*">
                                    </div>
                                    <p class="text-xs text-slate-500">Shows on program cards and detail hero. Saves to the server as soon as you choose a file—no need to press Save below.</p>
                                </div>
                            </div>
                            <?php else: ?>
                            <input type="text" name="cover_image" class="input mt-2 w-full rounded-xl border-slate-200 px-4 py-3 font-mono text-xs shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm" value="<?= htmlspecialchars($coverPathVal) ?>" placeholder="After you create the program, upload a cover from the edit page.">
                            <p class="mt-1.5 text-xs text-slate-500">Create the program first, then add a cover image from the edit screen.</p>
                            <?php endif; ?>
            </div>
        </div>

        <div>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-600 sm:text-sm sm:normal-case sm:tracking-normal">Short description</label>
                        <textarea name="description" rows="4" class="input w-full resize-y rounded-xl border-slate-200 px-4 py-3 text-sm leading-relaxed shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:min-h-[120px]" placeholder="Summary for cards and headers"><?= htmlspecialchars($program['description'] ?? '') ?></textarea>
        </div>
        <div>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-600 sm:text-sm sm:normal-case sm:tracking-normal">Detail page content</label>
                        <textarea name="detail_content" rows="8" class="input w-full resize-y rounded-xl border-slate-200 px-4 py-3 text-sm leading-relaxed shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:min-h-[200px]" placeholder="Extra paragraphs on the department detail page (plain text)"><?= htmlspecialchars($program['detail_content'] ?? '') ?></textarea>
        </div>

                    <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:items-center sm:justify-between">
                        <a href="<?= ADMIN_URL ?>?action=programs" class="inline-flex justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">Cancel</a>
                        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-primary-900 px-6 py-3 text-sm font-bold text-white shadow-md hover:bg-black focus:outline-none focus:ring-2 focus:ring-accent-400 focus:ring-offset-2">
                            <i class="fa-solid fa-check text-accent-400"></i>
                            <?= $isEdit ? 'Save changes' : 'Create program' ?>
                        </button>
                    </div>
                </form>
            </div>
        </section>
        </div>

    <aside class="space-y-4 lg:col-span-1">
        <div class="rounded-2xl border border-amber-200/80 bg-amber-50/90 p-4 text-sm text-amber-950 shadow-sm">
            <p class="flex items-start gap-2 font-semibold text-amber-900">
                <i class="fa-solid fa-lightbulb mt-0.5 text-amber-600"></i>
                Tips
            </p>
            <ul class="mt-2 list-inside list-disc space-y-1.5 text-xs leading-relaxed text-amber-900/90 sm:text-sm">
                <li>Gallery images and videos show a live preview while uploading; they are stored as soon as each upload finishes—Save is only for the text fields above.</li>
                <li>Cover photo (if shown) uploads immediately too, or use “Set as cover” on a gallery image.</li>
                <li>Large MP4 uploads may need a higher PHP <code class="rounded bg-amber-100/80 px-1">upload_max_filesize</code>.</li>
            </ul>
        </div>
        <?php if ($isEdit && $publicUrl): ?>
        <a href="<?= htmlspecialchars($publicUrl) ?>" target="_blank" rel="noopener" class="flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-4 text-sm font-semibold text-primary-800 shadow-sm transition hover:border-primary-300 hover:bg-primary-50">
            <i class="fa-solid fa-eye text-accent-600"></i>
            Preview on website
        </a>
        <?php endif; ?>
    </aside>
</div>

<?php if ($programId > 0): ?>
<section class="mt-8 overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm sm:mt-10">
    <div class="border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white px-4 py-4 sm:px-6">
        <h2 class="flex items-center gap-2 text-base font-semibold text-slate-900">
            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary-900 text-accent-400"><i class="fa-solid fa-photo-film text-sm"></i></span>
            Media library
        </h2>
        <p class="mt-1 text-xs text-slate-500 sm:text-sm">Uploads go to <code class="rounded bg-slate-100 px-1.5 py-0.5 font-mono text-[11px] text-slate-700">public/uploads/programs/<?= htmlspecialchars($slug ?: '…') ?>/</code></p>
    </div>

    <div class="grid gap-8 p-4 sm:p-6 lg:grid-cols-2 lg:gap-10">
    <div>
            <h3 class="text-sm font-bold text-slate-800">Images</h3>
            <p class="mt-1 text-xs text-slate-500 sm:text-sm">JPEG, PNG, WebP, or GIF — stored as optimized JPEG.</p>
            <div id="drop-images" role="button" tabindex="0" aria-label="Upload images" class="mt-3 flex min-h-[min(12rem,40vh)] cursor-pointer flex-col items-center justify-center gap-3 rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50/80 px-4 py-10 text-center transition hover:border-primary-400 hover:bg-primary-50/50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 active:scale-[0.99] sm:min-h-[11rem]">
                <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-primary-900 text-2xl text-accent-400 shadow-inner"><i class="fa-solid fa-cloud-arrow-up"></i></span>
                <span class="max-w-xs text-sm font-medium text-slate-700">Drop images here or <span class="text-primary-700 underline decoration-2 underline-offset-2">tap to browse</span></span>
                <span class="text-xs text-slate-500">Multiple files supported</span>
                <input type="file" id="file-images" class="sr-only" accept="image/*" multiple>
            </div>
        </div>
        <div>
            <h3 class="text-sm font-bold text-slate-800">Videos</h3>
            <p class="mt-1 text-xs text-slate-500 sm:text-sm">MP4 upload or paste a YouTube link.</p>
            <div id="drop-videos" role="button" tabindex="0" aria-label="Upload videos" class="mt-3 flex min-h-[min(11rem,36vh)] cursor-pointer flex-col items-center justify-center gap-3 rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50/80 px-4 py-8 text-center transition hover:border-primary-400 hover:bg-primary-50/50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 active:scale-[0.99] sm:min-h-[10rem]">
                <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-800 text-2xl text-white shadow-inner"><i class="fa-solid fa-clapperboard"></i></span>
                <span class="max-w-xs text-sm font-medium text-slate-700">Drop MP4 here or <span class="text-primary-700 underline decoration-2 underline-offset-2">tap to choose</span></span>
                <input type="file" id="file-videos" class="sr-only" accept="video/mp4,.mp4" multiple>
            </div>
            <form id="form-video-url" class="mt-5 space-y-2 rounded-xl border border-slate-100 bg-slate-50/80 p-4">
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-600">YouTube or video URL</label>
                <div class="flex flex-col gap-2 sm:flex-row sm:items-stretch">
                    <input type="url" name="external_url" class="input min-h-[44px] flex-1 rounded-xl border-slate-200 px-3 py-2.5 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500" placeholder="https://www.youtube.com/watch?v=…">
                    <button type="submit" class="inline-flex min-h-[44px] shrink-0 items-center justify-center rounded-xl bg-slate-800 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-accent-400 focus:ring-offset-2">Add link</button>
                </div>
        </form>
        </div>
    </div>

    <div class="border-t border-slate-100 px-4 py-5 sm:px-6 sm:py-6">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
    <div>
                <h3 class="text-sm font-bold text-slate-800">Gallery order</h3>
                <p class="text-xs text-slate-500 sm:text-sm">Drag <i class="fa-solid fa-grip-vertical text-slate-400"></i> to reorder. Saves automatically.</p>
            </div>
        </div>
        <ul id="media-sortable" class="mt-4 space-y-3">
            <?php foreach ($media as $m): ?>
            <?php
            $isVideo = ($m['media_type'] ?? '') === 'video';
            $thumb = '';
            if (!$isVideo && !empty($m['file_path'])) {
                $thumb = rtrim(APP_URL, '/') . '/' . ltrim($m['file_path'], '/');
            }
            ?>
            <li class="media-row group rounded-2xl border border-slate-200 bg-white p-3 shadow-sm transition hover:border-slate-300 hover:shadow-md sm:p-4" data-id="<?= (int) $m['id'] ?>">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-stretch">
                    <div class="flex shrink-0 items-center gap-2 sm:flex-col sm:items-center sm:pt-1">
                        <span class="drag-handle flex h-11 w-11 cursor-grab select-none items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-slate-500 active:cursor-grabbing hover:border-primary-300 hover:bg-primary-50 hover:text-primary-700" title="Drag to reorder"><i class="fa-solid fa-grip-vertical"></i></span>
                    </div>
                    <div class="relative h-40 w-full overflow-hidden rounded-xl bg-slate-100 sm:h-24 sm:w-36 sm:flex-shrink-0">
                    <?php if ($isVideo): ?>
                        <div class="flex h-full w-full flex-col items-center justify-center gap-1 bg-slate-900/90 text-white">
                            <i class="fa-solid fa-circle-play text-3xl text-accent-400"></i>
                            <span class="text-xs font-bold uppercase tracking-wide">Video</span>
                        </div>
                    <?php elseif ($thumb): ?>
                        <img src="<?= htmlspecialchars($thumb) ?>" alt="" class="h-full w-full object-cover" loading="lazy" width="144" height="96">
                    <?php else: ?>
                        <div class="flex h-full items-center justify-center text-xs text-slate-400">No preview</div>
                    <?php endif; ?>
                </div>
                    <div class="min-w-0 flex-1 space-y-3">
                        <form class="caption-form space-y-2" data-media-id="<?= (int) $m['id'] ?>">
                            <label class="text-xs font-medium text-slate-600">Caption</label>
                            <div class="flex flex-col gap-2 sm:flex-row sm:items-end">
                                <input type="text" name="caption" class="input min-h-[44px] flex-1 rounded-xl border-slate-200 px-3 py-2.5 text-sm shadow-sm" value="<?= htmlspecialchars($m['caption'] ?? '') ?>" placeholder="Optional label">
                                <button type="submit" class="inline-flex min-h-[44px] items-center justify-center rounded-xl border border-slate-200 bg-white px-4 text-sm font-semibold text-primary-800 hover:bg-slate-50">Save</button>
                            </div>
                        </form>
                        <div class="flex flex-wrap gap-2 border-t border-slate-100 pt-3">
                            <?php if (!$isVideo && !empty($m['file_path'])): ?>
                            <a href="<?= ADMIN_URL ?>?action=program_media_set_cover&id=<?= (int) $m['id'] ?>" class="inline-flex items-center gap-1.5 rounded-lg bg-accent-100 px-3 py-2 text-xs font-bold text-amber-950 hover:bg-accent-200"><i class="fa-regular fa-image"></i> Set as cover</a>
                            <?php endif; ?>
                            <a href="<?= ADMIN_URL ?>?action=program_media_delete&id=<?= (int) $m['id'] ?>" class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-800 hover:bg-red-100" onclick="return confirm('Delete this item?');"><i class="fa-regular fa-trash-can"></i> Delete</a>
                        </div>
                    </div>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php if (empty($media)): ?>
        <p class="mt-4 rounded-xl border border-dashed border-slate-200 bg-slate-50 px-4 py-6 text-center text-sm text-slate-500" id="media-empty-hint">No media yet. Use the zones above to add images or videos.</p>
        <?php endif; ?>
    </div>
</section>

<!-- Mobile sticky save (mirrors main form submit) -->
<div class="pointer-events-none fixed inset-x-0 bottom-0 z-30 p-4 pb-[max(1rem,env(safe-area-inset-bottom))] lg:hidden">
    <div class="pointer-events-auto flex justify-end">
        <button type="button" id="program-mobile-save" class="inline-flex items-center gap-2 rounded-full bg-primary-900 px-5 py-3 text-sm font-bold text-white shadow-lg ring-1 ring-black/10 hover:bg-black focus:outline-none focus:ring-2 focus:ring-accent-400 focus:ring-offset-2">
            <i class="fa-solid fa-floppy-disk text-accent-400"></i>
            Save
        </button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
(function () {
    var ADMIN_URL = <?= json_encode(ADMIN_URL) ?>;
    var ADMIN_API = <?= json_encode(ADMIN_INDEX_URL) ?>;
    var APP_URL_BASE = <?= json_encode(rtrim(APP_URL, '/')) ?>;
    var PROGRAM_ID = <?= (int) $programId ?>;

    var toastEl = document.getElementById('admin-toast');
    function showToast(msg, isError) {
        if (!toastEl) { if (isError) alert(msg); return; }
        toastEl.textContent = msg;
        toastEl.classList.remove('hidden', 'border-red-200', 'bg-red-50', 'text-red-900');
        if (isError) {
            toastEl.classList.add('border-red-200', 'bg-red-50', 'text-red-900');
        } else {
            toastEl.classList.add('border-slate-200', 'bg-white', 'text-slate-800');
        }
        toastEl.classList.remove('hidden');
        clearTimeout(showToast._t);
        showToast._t = setTimeout(function () { toastEl.classList.add('hidden'); }, 4200);
    }

    function parseJsonResponse(r) {
        return r.text().then(function (text) {
            var j;
            try {
                j = JSON.parse(text);
            } catch (e) {
                throw new Error(text ? text.slice(0, 160) : (r.status + ' ' + r.statusText));
            }
            if (!r.ok || !j || !j.ok) {
                throw new Error((j && j.error) ? j.error : (r.statusText || 'Request failed'));
            }
            return j;
        });
    }

    function clearMediaEmptyHint() {
        var hint = document.getElementById('media-empty-hint');
        if (hint) hint.remove();
    }

    function escAttr(s) {
        return String(s).replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    }

    function removePendingRow(li) {
        if (!li || !li.parentNode) return;
        if (li._previewObjectUrl) {
            try { URL.revokeObjectURL(li._previewObjectUrl); } catch (e0) {}
            li._previewObjectUrl = null;
        }
        li.parentNode.removeChild(li);
    }

    function appendPendingImageRow(file) {
        var list = document.getElementById('media-sortable');
        if (!list || !file) return null;
        clearMediaEmptyHint();
        var objUrl = URL.createObjectURL(file);
        var li = document.createElement('li');
        li.className = 'media-row group rounded-2xl border-2 border-dashed border-primary-400 bg-primary-50/50 p-3 sm:p-4';
        li.setAttribute('data-upload-pending', '1');
        li._previewObjectUrl = objUrl;
        li.innerHTML =
            '<div class="flex flex-col gap-3 sm:flex-row sm:items-stretch">' +
            '<div class="flex shrink-0 items-center gap-2 sm:flex-col sm:items-center sm:pt-1">' +
            '<span class="drag-handle flex h-11 w-11 cursor-not-allowed select-none items-center justify-center rounded-xl border border-slate-200 bg-white/80 text-slate-400" title="Uploading…"><i class="fa-solid fa-grip-vertical"></i></span>' +
            '</div>' +
            '<div class="relative h-40 w-full overflow-hidden rounded-xl bg-slate-900/5 sm:h-24 sm:w-36 sm:flex-shrink-0">' +
            '<img src="' + escAttr(objUrl) + '" alt="" class="h-full w-full object-cover opacity-90" width="144" height="96">' +
            '<div class="pointer-events-none absolute inset-0 flex flex-col items-center justify-center bg-slate-900/55 text-white">' +
            '<i class="fa-solid fa-cloud-arrow-up text-2xl animate-pulse"></i>' +
            '<span class="mt-2 max-w-[10rem] truncate px-2 text-center text-[10px] font-semibold uppercase tracking-wide">Uploading</span>' +
            '</div></div>' +
            '<div class="min-w-0 flex-1 flex flex-col justify-center py-1 text-xs text-slate-600">' +
            '<p class="font-medium text-slate-800">New image</p>' +
            '<p class="pending-iname mt-1 truncate font-mono text-[11px] text-slate-500"></p>' +
            '</div></div>';
        var nameP = li.querySelector('.pending-iname');
        if (nameP) nameP.textContent = file.name || 'image';
        list.appendChild(li);
        return li;
    }

    function appendPendingVideoRow(file) {
        var list = document.getElementById('media-sortable');
        if (!list || !file) return null;
        clearMediaEmptyHint();
        var li = document.createElement('li');
        li.className = 'media-row group rounded-2xl border-2 border-dashed border-slate-400 bg-slate-50 p-3 sm:p-4';
        li.setAttribute('data-upload-pending', '1');
        li.innerHTML =
            '<div class="flex flex-col gap-3 sm:flex-row sm:items-stretch">' +
            '<div class="flex shrink-0 items-center gap-2 sm:flex-col sm:items-center sm:pt-1">' +
            '<span class="drag-handle flex h-11 w-11 cursor-not-allowed select-none items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-400"><i class="fa-solid fa-grip-vertical"></i></span></div>' +
            '<div class="relative flex h-40 w-full items-center justify-center rounded-xl bg-slate-800 sm:h-24 sm:w-36 sm:flex-shrink-0">' +
            '<i class="fa-solid fa-spinner fa-spin text-3xl text-white"></i></div>' +
            '<div class="min-w-0 flex-1 flex flex-col justify-center py-1 text-xs text-slate-600">' +
            '<p class="font-medium text-slate-800">Uploading video</p><p class="pending-vname mt-1 truncate font-mono text-[11px] text-slate-500"></p></div></div>';
        var vn = li.querySelector('.pending-vname');
        if (vn) vn.textContent = file.name || 'video';
        list.appendChild(li);
        return li;
    }

    function appendImageGalleryRow(j) {
        var list = document.getElementById('media-sortable');
        if (!list || !j || !j.id || !j.url) return;
        clearMediaEmptyHint();
        var li = document.createElement('li');
        li.className = 'media-row group rounded-2xl border border-slate-200 bg-white p-3 shadow-sm transition hover:border-slate-300 hover:shadow-md sm:p-4';
        li.setAttribute('data-id', String(j.id));
        li.innerHTML =
            '<div class="flex flex-col gap-3 sm:flex-row sm:items-stretch">' +
            '<div class="flex shrink-0 items-center gap-2 sm:flex-col sm:items-center sm:pt-1">' +
            '<span class="drag-handle flex h-11 w-11 cursor-grab select-none items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-slate-500 active:cursor-grabbing hover:border-primary-300 hover:bg-primary-50 hover:text-primary-700" title="Drag to reorder"><i class="fa-solid fa-grip-vertical"></i></span>' +
            '</div>' +
            '<div class="relative h-40 w-full overflow-hidden rounded-xl bg-slate-100 sm:h-24 sm:w-36 sm:flex-shrink-0">' +
            '<img src="' + escAttr(j.url) + '" alt="" class="h-full w-full object-cover" loading="lazy" width="144" height="96">' +
            '</div>' +
            '<div class="min-w-0 flex-1 space-y-3">' +
            '<form class="caption-form space-y-2" data-media-id="' + escAttr(String(j.id)) + '">' +
            '<label class="text-xs font-medium text-slate-600">Caption</label>' +
            '<div class="flex flex-col gap-2 sm:flex-row sm:items-end">' +
            '<input type="text" name="caption" class="input min-h-[44px] flex-1 rounded-xl border border-slate-200 px-3 py-2.5 text-sm shadow-sm" value="" placeholder="Optional label">' +
            '<button type="submit" class="inline-flex min-h-[44px] items-center justify-center rounded-xl border border-slate-200 bg-white px-4 text-sm font-semibold text-primary-800 hover:bg-slate-50">Save</button>' +
            '</div></form>' +
            '<div class="flex flex-wrap gap-2 border-t border-slate-100 pt-3">' +
            '<a href="' + escAttr(ADMIN_URL + '?action=program_media_set_cover&id=' + j.id) + '" class="inline-flex items-center gap-1.5 rounded-lg bg-accent-100 px-3 py-2 text-xs font-bold text-amber-950 hover:bg-accent-200"><i class="fa-regular fa-image"></i> Set as cover</a>' +
            '<a href="' + escAttr(ADMIN_URL + '?action=program_media_delete&id=' + j.id) + '" class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-800 hover:bg-red-100" onclick="return confirm(\'Delete this item?\');"><i class="fa-regular fa-trash-can"></i> Delete</a>' +
            '</div></div></div>';
        list.appendChild(li);
    }

    function appendVideoFileGalleryRow(j) {
        var list = document.getElementById('media-sortable');
        if (!list || !j || !j.id) return;
        clearMediaEmptyHint();
        var li = document.createElement('li');
        li.className = 'media-row group rounded-2xl border border-slate-200 bg-white p-3 shadow-sm transition hover:border-slate-300 hover:shadow-md sm:p-4';
        li.setAttribute('data-id', String(j.id));
        li.innerHTML =
            '<div class="flex flex-col gap-3 sm:flex-row sm:items-stretch">' +
            '<div class="flex shrink-0 items-center gap-2 sm:flex-col sm:items-center sm:pt-1">' +
            '<span class="drag-handle flex h-11 w-11 cursor-grab select-none items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-slate-500 active:cursor-grabbing hover:border-primary-300 hover:bg-primary-50 hover:text-primary-700" title="Drag to reorder"><i class="fa-solid fa-grip-vertical"></i></span>' +
            '</div>' +
            '<div class="relative h-40 w-full overflow-hidden rounded-xl bg-slate-100 sm:h-24 sm:w-36 sm:flex-shrink-0">' +
            '<div class="flex h-full w-full flex-col items-center justify-center gap-1 bg-slate-900/90 text-white">' +
            '<i class="fa-solid fa-circle-play text-3xl text-accent-400"></i><span class="text-xs font-bold uppercase tracking-wide">Video</span></div></div>' +
            '<div class="min-w-0 flex-1 space-y-3">' +
            '<form class="caption-form space-y-2" data-media-id="' + escAttr(String(j.id)) + '">' +
            '<label class="text-xs font-medium text-slate-600">Caption</label>' +
            '<div class="flex flex-col gap-2 sm:flex-row sm:items-end">' +
            '<input type="text" name="caption" class="input min-h-[44px] flex-1 rounded-xl border border-slate-200 px-3 py-2.5 text-sm shadow-sm" value="" placeholder="Optional label">' +
            '<button type="submit" class="inline-flex min-h-[44px] items-center justify-center rounded-xl border border-slate-200 bg-white px-4 text-sm font-semibold text-primary-800 hover:bg-slate-50">Save</button>' +
            '</div></form>' +
            '<div class="flex flex-wrap gap-2 border-t border-slate-100 pt-3">' +
            '<a href="' + escAttr(ADMIN_URL + '?action=program_media_delete&id=' + j.id) + '" class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-800 hover:bg-red-100" onclick="return confirm(\'Delete this item?\');"><i class="fa-regular fa-trash-can"></i> Delete</a>' +
            '</div></div></div>';
        list.appendChild(li);
    }

    function appendExternalVideoRow(j) {
        var list = document.getElementById('media-sortable');
        if (!list || !j || !j.id || !j.external_url) return;
        clearMediaEmptyHint();
        var li = document.createElement('li');
        li.className = 'media-row group rounded-2xl border border-slate-200 bg-white p-3 shadow-sm transition hover:border-slate-300 hover:shadow-md sm:p-4';
        li.setAttribute('data-id', String(j.id));
        li.innerHTML =
            '<div class="flex flex-col gap-3 sm:flex-row sm:items-stretch">' +
            '<div class="flex shrink-0 items-center gap-2 sm:flex-col sm:items-center sm:pt-1">' +
            '<span class="drag-handle flex h-11 w-11 cursor-grab select-none items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-slate-500 active:cursor-grabbing hover:border-primary-300 hover:bg-primary-50 hover:text-primary-700" title="Drag to reorder"><i class="fa-solid fa-grip-vertical"></i></span>' +
            '</div>' +
            '<div class="relative h-40 w-full overflow-hidden rounded-xl bg-slate-100 sm:h-24 sm:w-36 sm:flex-shrink-0">' +
            '<div class="flex h-full w-full flex-col items-center justify-center gap-1 bg-slate-900/90 text-white">' +
            '<i class="fa-solid fa-circle-play text-3xl text-accent-400"></i><span class="text-xs font-bold uppercase tracking-wide">Video</span></div></div>' +
            '<div class="min-w-0 flex-1 space-y-3">' +
            '<p class="text-xs text-slate-500 break-all"><a href="' + escAttr(j.external_url) + '" target="_blank" rel="noopener" class="text-primary-700 hover:underline">' + escAttr(j.external_url) + '</a></p>' +
            '<form class="caption-form space-y-2" data-media-id="' + escAttr(String(j.id)) + '">' +
            '<label class="text-xs font-medium text-slate-600">Caption</label>' +
            '<div class="flex flex-col gap-2 sm:flex-row sm:items-end">' +
            '<input type="text" name="caption" class="input min-h-[44px] flex-1 rounded-xl border border-slate-200 px-3 py-2.5 text-sm shadow-sm" value="" placeholder="Optional label">' +
            '<button type="submit" class="inline-flex min-h-[44px] items-center justify-center rounded-xl border border-slate-200 bg-white px-4 text-sm font-semibold text-primary-800 hover:bg-slate-50">Save</button>' +
            '</div></form>' +
            '<div class="flex flex-wrap gap-2 border-t border-slate-100 pt-3">' +
            '<a href="' + escAttr(ADMIN_URL + '?action=program_media_delete&id=' + j.id) + '" class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-800 hover:bg-red-100" onclick="return confirm(\'Delete this item?\');"><i class="fa-regular fa-trash-can"></i> Delete</a>' +
            '</div></div></div>';
        list.appendChild(li);
    }

    var mainForm = document.getElementById('program-main-form');
    var mobileSave = document.getElementById('program-mobile-save');
    if (mainForm && mobileSave) {
        mobileSave.addEventListener('click', function () {
            if (mainForm.requestSubmit) mainForm.requestSubmit();
            else mainForm.submit();
        });
    }

    function uploadOneFile(action, file) {
        var fd = new FormData();
        fd.append('program_id', String(PROGRAM_ID));
        fd.append('file', file);
        var url = ADMIN_API + '?action=' + encodeURIComponent(action) + '&program_id=' + encodeURIComponent(String(PROGRAM_ID));
        return fetch(url, { method: 'POST', body: fd, credentials: 'same-origin' }).then(parseJsonResponse);
    }

    function uploadFiles(action, files, onEach) {
        if (!files || !files.length) return Promise.resolve();
        var p = Promise.resolve();
        var i;
        for (i = 0; i < files.length; i++) {
            (function (file) {
                p = p.then(function () {
                    var pending = null;
                    if (action === 'program_media_upload') {
                        pending = appendPendingImageRow(file);
                    } else if (action === 'program_video_upload') {
                        pending = appendPendingVideoRow(file);
                    }
                    return uploadOneFile(action, file).then(function (j) {
                        if (pending) removePendingRow(pending);
                        if (onEach) onEach(j, action);
                        return j;
                    }).catch(function (err) {
                        if (pending) removePendingRow(pending);
                        throw err;
                    });
                });
            })(files[i]);
        }
        return p;
    }

    function wireDrop(zoneId, inputId, action) {
        var zone = document.getElementById(zoneId);
        var input = document.getElementById(inputId);
        if (!zone || !input) return;
        function pick() { input.click(); }
        zone.addEventListener('click', pick);
        zone.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); pick(); }
        });
        ['dragenter', 'dragover'].forEach(function (ev) {
            zone.addEventListener(ev, function (e) { e.preventDefault(); e.stopPropagation(); zone.classList.add('border-primary-500', 'bg-primary-50', 'ring-2', 'ring-primary-200'); });
        });
        ['dragleave', 'drop'].forEach(function (ev) {
            zone.addEventListener(ev, function (e) { e.preventDefault(); e.stopPropagation(); zone.classList.remove('border-primary-500', 'bg-primary-50', 'ring-2', 'ring-primary-200'); });
        });
        function onDone(j, act) {
            if (act === 'program_media_upload') appendImageGalleryRow(j);
            else if (act === 'program_video_upload') appendVideoFileGalleryRow(j);
        }
        zone.addEventListener('drop', function (e) {
            var files = e.dataTransfer.files;
            showToast('Uploading…');
            uploadFiles(action, files, onDone).then(function () { showToast('Upload complete'); }).catch(function (err) { showToast(err.message || String(err), true); });
        });
        input.addEventListener('change', function () {
            showToast('Uploading…');
            uploadFiles(action, input.files, onDone).then(function () { showToast('Upload complete'); input.value = ''; }).catch(function (err) { showToast(err.message || String(err), true); });
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
            fetch(ADMIN_API + '?action=program_video_url_save&program_id=' + encodeURIComponent(String(PROGRAM_ID)), { method: 'POST', body: fd, credentials: 'same-origin' })
                .then(parseJsonResponse)
                .then(function (j) {
                    appendExternalVideoRow(j);
                    showToast('Video link added');
                    urlForm.reset();
                })
                .catch(function (err) { showToast(err.message || String(err), true); });
        });
    }

    var list = document.getElementById('media-sortable');
    if (list && typeof Sortable !== 'undefined') {
        Sortable.create(list, {
            animation: 180,
            handle: '.drag-handle',
            onEnd: function () {
                var ids = [];
                list.querySelectorAll('.media-row[data-id]').forEach(function (el) {
                    var nid = parseInt(el.getAttribute('data-id'), 10);
                    if (nid > 0) ids.push(nid);
                });
                fetch(ADMIN_API + '?action=program_media_reorder', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ ids: ids }),
                    credentials: 'same-origin'
                })
                    .then(parseJsonResponse)
                    .then(function () { showToast('Order saved'); })
                    .catch(function (err) { showToast(err.message || String(err), true); });
            }
        });
    }

    if (list) {
        list.addEventListener('submit', function (e) {
            var form = e.target && e.target.closest ? e.target.closest('.caption-form') : null;
            if (!form || !list.contains(form)) return;
            e.preventDefault();
            var id = form.getAttribute('data-media-id');
            var cap = form.querySelector('input[name="caption"]');
            var fd = new FormData();
            fd.append('id', id);
            fd.append('caption', cap ? cap.value : '');
            fetch(ADMIN_API + '?action=program_media_caption_save', { method: 'POST', body: fd, credentials: 'same-origin' })
                .then(parseJsonResponse)
                .then(function () { showToast('Caption saved'); })
                .catch(function (err) { showToast(err.message || String(err), true); });
        });
    }

    var coverTrigger = document.getElementById('program-cover-file-trigger');
    var coverInput = document.getElementById('program-cover-file');
    var coverPathField = document.getElementById('program-cover-image-input');
    var coverPreviewImg = document.getElementById('program-cover-preview-img');
    var coverPreviewEmpty = document.getElementById('program-cover-preview-empty');
    var coverUploading = document.getElementById('program-cover-uploading');
    if (coverTrigger && coverInput) {
        coverTrigger.addEventListener('click', function () { coverInput.click(); });
        coverInput.addEventListener('change', function () {
            var file = coverInput.files && coverInput.files[0];
            coverInput.value = '';
            if (!file) return;
            var localUrl = null;
            try {
                localUrl = URL.createObjectURL(file);
            } catch (e1) {}
            if (coverPreviewImg && localUrl) {
                coverPreviewImg.src = localUrl;
                coverPreviewImg.classList.remove('hidden');
            }
            if (coverPreviewEmpty) coverPreviewEmpty.classList.add('hidden');
            if (coverUploading) {
                coverUploading.classList.remove('hidden');
                coverUploading.classList.add('flex', 'flex-col');
            }
            var fd = new FormData();
            fd.append('program_id', String(PROGRAM_ID));
            fd.append('file', file);
            var url = ADMIN_API + '?action=program_cover_upload&program_id=' + encodeURIComponent(String(PROGRAM_ID));
            fetch(url, { method: 'POST', body: fd, credentials: 'same-origin' })
                .then(parseJsonResponse)
                .then(function (j) {
                    if (localUrl) {
                        try { URL.revokeObjectURL(localUrl); } catch (e2) {}
                    }
                    if (coverUploading) {
                        coverUploading.classList.add('hidden');
                        coverUploading.classList.remove('flex', 'flex-col');
                    }
                    if (coverPathField && j.file_path) coverPathField.value = j.file_path;
                    if (coverPreviewImg && j.url) {
                        coverPreviewImg.src = j.url;
                        coverPreviewImg.classList.remove('hidden');
                    }
                    if (coverPreviewEmpty) coverPreviewEmpty.classList.add('hidden');
                    showToast('Cover updated');
                })
                .catch(function (err) {
                    if (localUrl) {
                        try { URL.revokeObjectURL(localUrl); } catch (e3) {}
                    }
                    if (coverUploading) {
                        coverUploading.classList.add('hidden');
                        coverUploading.classList.remove('flex', 'flex-col');
                    }
                    var prevPath = coverPathField && coverPathField.value ? coverPathField.value.trim() : '';
                    if (coverPreviewImg) {
                        if (prevPath) {
                            coverPreviewImg.src = APP_URL_BASE + '/' + prevPath.replace(/^\//, '');
                            coverPreviewImg.classList.remove('hidden');
                        } else {
                            coverPreviewImg.classList.add('hidden');
                            coverPreviewImg.removeAttribute('src');
                        }
                    }
                    if (coverPreviewEmpty) {
                        if (prevPath) coverPreviewEmpty.classList.add('hidden');
                        else coverPreviewEmpty.classList.remove('hidden');
                    }
                    showToast(err.message || String(err), true);
                });
        });
    }
})();
</script>
<?php endif; ?>

<?php
$content = ob_get_clean();
$title = isset($program) ? 'Edit Program' : 'Add Program';
require __DIR__ . '/../layout.php';
?>
