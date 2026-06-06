<?php
ob_start();
$isEdit = !empty($member);
$photo = $isEdit && !empty($member['photo_path']) ? rtrim(APP_URL, '/') . '/' . ltrim($member['photo_path'], '/') : '';
?>

<div class="mb-6 flex items-center gap-3">
    <a href="<?= ADMIN_URL ?>?action=alumni" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 hover:text-slate-900">
        <i class="fa-solid fa-arrow-left text-sm"></i>
    </a>
    <div>
        <p class="text-xs font-semibold tracking-widest uppercase text-primary-600">Community</p>
        <h1 class="mt-1 text-2xl font-bold tracking-tight text-gray-900"><?= $isEdit ? 'Edit alumnus' : 'Add alumnus' ?></h1>
    </div>
</div>

<form action="<?= ADMIN_URL ?>?action=alumni_save" method="POST" enctype="multipart/form-data" class="space-y-6">
    <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?= (int) $member['id'] ?>">
    <?php endif; ?>

    <div class="card space-y-5">
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <div>
                <label class="label" for="alumni-name">Full name <span class="text-red-500">*</span></label>
                <input id="alumni-name" name="name" type="text" required value="<?= htmlspecialchars($member['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="input rounded-lg px-3 py-2.5 text-sm" placeholder="e.g. Kwame Mensah">
            </div>
            <div>
                <label class="label" for="alumni-role">Current role / occupation</label>
                <input id="alumni-role" name="current_role" type="text" value="<?= htmlspecialchars($member['current_role'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="input rounded-lg px-3 py-2.5 text-sm" placeholder="e.g. Senior Electrician, ECG">
            </div>
            <div>
                <label class="label" for="alumni-program">Program at KTI</label>
                <input id="alumni-program" name="program" type="text" value="<?= htmlspecialchars($member['program'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="input rounded-lg px-3 py-2.5 text-sm" placeholder="e.g. Electrical Engineering">
            </div>
            <div>
                <label class="label" for="alumni-year">Year of completion</label>
                <input id="alumni-year" name="graduation_year" type="text" value="<?= htmlspecialchars($member['graduation_year'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="input rounded-lg px-3 py-2.5 text-sm" placeholder="e.g. 2012">
            </div>
            <div>
                <label class="label" for="alumni-location">Current location</label>
                <input id="alumni-location" name="location" type="text" value="<?= htmlspecialchars($member['location'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="input rounded-lg px-3 py-2.5 text-sm" placeholder="e.g. Accra, Ghana">
            </div>
            <div>
                <label class="label" for="alumni-sort">Display order</label>
                <input id="alumni-sort" name="sort_order" type="number" value="<?= (int) ($member['sort_order'] ?? 0) ?>" class="input rounded-lg px-3 py-2.5 text-sm" placeholder="0">
                <p class="mt-1 text-xs text-slate-500">Lower numbers appear first.</p>
            </div>
        </div>

        <div>
            <label class="label" for="alumni-quote">Short quote / testimonial</label>
            <input id="alumni-quote" name="quote" type="text" value="<?= htmlspecialchars($member['quote'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="input rounded-lg px-3 py-2.5 text-sm" placeholder="One sentence about Kikam or your career">
        </div>

        <div>
            <label class="label" for="alumni-bio">Story / bio</label>
            <textarea id="alumni-bio" name="bio" rows="5" class="input rounded-lg px-3 py-2.5 text-sm" placeholder="A few sentences about their journey from Kikam to today."><?= htmlspecialchars($member['bio'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>

        <div class="flex items-center gap-2">
            <input id="alumni-featured" name="is_featured" type="checkbox" value="1" <?= !empty($member['is_featured']) ? 'checked' : '' ?> class="h-4 w-4 rounded border-slate-300 text-primary-600 focus:ring-primary-500">
            <label for="alumni-featured" class="text-sm text-slate-700">Feature this profile (shown first on the Old Students page)</label>
        </div>
    </div>

    <div class="card">
        <h2 class="text-sm font-semibold text-slate-900">Photo</h2>
        <p class="mt-1 text-xs text-slate-500">JPG, PNG, GIF or WebP — we automatically convert and compress to under <?= (int) (PROGRAM_IMAGE_MAX_BYTES / 1000) ?> KB.</p>

        <div class="mt-4 flex flex-col gap-4 sm:flex-row sm:items-center">
            <div class="flex h-28 w-28 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-slate-100 ring-1 ring-slate-200" id="alumni-photo-preview-wrap">
                <?php if ($photo): ?>
                    <img id="alumni-photo-preview" src="<?= htmlspecialchars($photo) ?>" alt="" class="h-full w-full object-cover">
                <?php else: ?>
                    <span id="alumni-photo-preview-empty" class="text-xs text-slate-500">No photo yet</span>
                <?php endif; ?>
            </div>
            <div class="flex-1 space-y-2">
                <label class="inline-flex cursor-pointer items-center gap-2 rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-medium text-slate-800 hover:bg-slate-50">
                    <i class="fa-solid fa-upload text-xs text-slate-500"></i>
                    <span>Choose photo</span>
                    <input id="alumni-photo-input" name="photo" type="file" accept="image/*" class="hidden">
                </label>
                <p id="alumni-photo-filename" class="text-xs text-slate-500"><?= $photo ? 'Current photo will be kept until you replace it.' : 'No file chosen' ?></p>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-end gap-2">
        <a href="<?= ADMIN_URL ?>?action=alumni" class="btn btn-secondary px-4 py-2">Cancel</a>
        <button type="submit" class="btn btn-primary px-5 py-2"><?= $isEdit ? 'Save changes' : 'Add alumnus' ?></button>
    </div>
</form>

<script>
(function () {
    var input = document.getElementById('alumni-photo-input');
    var fileLabel = document.getElementById('alumni-photo-filename');
    var wrap = document.getElementById('alumni-photo-preview-wrap');
    if (!input) return;

    input.addEventListener('change', function () {
        var file = input.files && input.files[0];
        if (!file) {
            if (fileLabel) fileLabel.textContent = 'No file chosen';
            return;
        }
        if (fileLabel) fileLabel.textContent = file.name + ' · ' + Math.round(file.size / 1024) + ' KB';

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
})();
</script>

<?php
$content = ob_get_clean();
$title = $isEdit ? 'Edit Alumnus' : 'Add Alumnus';
require __DIR__ . '/../layout.php';
?>
