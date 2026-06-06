<?php ob_start(); ?>

<div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
    <div>
        <p class="text-xs font-semibold tracking-widest uppercase text-primary-600">Campus life</p>
        <h1 class="mt-2 text-2xl font-bold tracking-tight text-gray-900">Events</h1>
        <p class="mt-1 text-sm text-gray-500">Open days, speech and prize giving, sports, alumni gatherings — list anything happening at Kikam.</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="<?= ADMIN_URL ?>?action=event_create" class="inline-flex items-center gap-2 rounded-lg bg-primary-900 px-4 py-2 text-sm font-medium text-white hover:bg-black">
            <i class="fa-solid fa-plus text-xs"></i>
            New event
        </a>
        <a href="<?= APP_URL ?>?url=events" target="_blank" class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50">
            <i class="fa-solid fa-up-right-from-square text-[11px]"></i>
            View page
        </a>
    </div>
</div>

<div class="card overflow-hidden">
    <div class="flex items-center justify-between border-b border-gray-100 px-4 py-3 bg-gray-50">
        <p class="text-sm font-medium text-gray-800 flex items-center gap-2">
            <i class="fa-regular fa-calendar text-primary-600"></i>
            <span>All events</span>
        </p>
        <span class="rounded-full bg-white px-3 py-0.5 text-[11px] font-medium text-gray-700 border border-gray-200"><?= count($events ?? []) ?> total</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr class="border-b border-gray-200">
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Event</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Date</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Location</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Status</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (empty($events)): ?>
                <tr>
                    <td colspan="5" class="py-10 text-center text-sm text-gray-500">No events yet. Create the first one to get started.</td>
                </tr>
                <?php else: ?>
                    <?php foreach ($events as $e):
                        $cover = !empty($e['cover_image']) ? rtrim(APP_URL, '/') . '/' . ltrim($e['cover_image'], '/') : '';
                        $ts = !empty($e['event_date']) ? strtotime($e['event_date']) : null;
                    ?>
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-14 items-center justify-center overflow-hidden rounded bg-primary-50 text-xs font-semibold text-primary-700">
                                    <?php if ($cover): ?>
                                        <img src="<?= htmlspecialchars($cover) ?>" alt="" class="h-full w-full object-cover">
                                    <?php else: ?>
                                        <i class="fa-regular fa-calendar"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-medium text-gray-900"><?= htmlspecialchars($e['title']) ?></span>
                                    <?php if (!empty($e['slug'])): ?>
                                        <span class="font-mono text-[11px] text-gray-500">/event/<?= htmlspecialchars($e['slug']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-gray-600 text-xs">
                            <?php if ($ts): ?>
                                <?= htmlspecialchars(date('M j, Y', $ts)) ?>
                                <?php if (date('G', $ts) !== '0' || date('i', $ts) !== '00'): ?>
                                    <span class="block text-[11px] text-gray-400"><?= htmlspecialchars(date('g:i a', $ts)) ?></span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-gray-400">No date set</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 px-4 text-gray-600 text-xs"><?= htmlspecialchars($e['location'] ?? '') ?></td>
                        <td class="py-3 px-4 text-xs">
                            <?php if (!empty($e['is_published'])): ?>
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2 py-0.5 text-[11px] font-semibold text-emerald-800">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                    Published
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-semibold text-slate-700">
                                    Draft
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <div class="inline-flex items-center gap-2">
                                <?php if (!empty($e['slug']) && !empty($e['is_published'])): ?>
                                    <a href="<?= APP_URL ?>?url=event/<?= urlencode($e['slug']) ?>" target="_blank" class="text-xs font-medium text-gray-500 hover:text-gray-700">Preview</a>
                                    <span class="text-gray-300">|</span>
                                <?php endif; ?>
                                <a href="<?= ADMIN_URL ?>?action=event_edit&id=<?= (int) $e['id'] ?>" class="text-xs font-medium text-primary-600 hover:text-primary-800">Edit</a>
                                <button type="button" onclick="if(confirm('Delete this event?')) { window.location='<?= ADMIN_URL ?>?action=event_delete&id=<?= (int) $e['id'] ?>'; }" class="text-xs font-medium text-red-600 hover:text-red-800">Delete</button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = 'Events';
require __DIR__ . '/../layout.php';
?>
