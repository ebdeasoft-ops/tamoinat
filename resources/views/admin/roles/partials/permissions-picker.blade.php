{{--
    اختيار الصلاحيات مقسمة حسب أقسام البرنامج (config/permission_groups.php)
    المتغيرات: $permission (كل الصلاحيات) ، $checked (أرقام الصلاحيات المحددة)
--}}
@php
    $isAr    = \App\Support\PermissionCatalog::isArabic();
    $groups  = \App\Support\PermissionCatalog::groups($permission);
    $checked = array_map('intval', (array) old('permission', $checked ?? []));
    $t = fn ($ar, $en) => $isAr ? $ar : $en;
@endphp

<style>
    .perm-toolbar { display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:12px; margin: 10px 0 16px; }
    .perm-toolbar h5 { margin:0; font-weight:700; color:#1e293b; }
    .perm-toolbar .perm-count { font-size:12px; color:#64748b; font-weight:500; margin-inline-start:6px; }
    .perm-toolbar .perm-links a { font-size:12px; color:#2563eb; cursor:pointer; margin-inline-start:12px; text-decoration:none; }
    .perm-toolbar .perm-links a:hover { text-decoration:underline; }
    .perm-search { max-width:320px; border-radius:8px; border:1px solid #e2e8f0; height:38px; font-size:13px; }
    .perm-grid { display:grid; grid-template-columns:repeat(2, minmax(0,1fr)); gap:16px; }
    @media (max-width: 991px) { .perm-grid { grid-template-columns:1fr; } }
    .perm-card { border:1px solid #e5e7eb; border-radius:10px; background:#fff; padding:14px 16px; }
    .perm-card-head { display:flex; align-items:center; justify-content:space-between; margin-bottom:8px; }
    .perm-card-head .perm-title { font-weight:700; font-size:14px; color:#0f172a; display:flex; align-items:center; gap:8px; }
    .perm-card-head .perm-title i { color:#64748b; font-size:13px; }
    .perm-card-head .perm-title small { color:#94a3b8; font-weight:500; }
    .perm-card-head .perm-select-all { font-size:11px; color:#94a3b8; cursor:pointer; user-select:none; }
    .perm-card-head .perm-select-all:hover { color:#2563eb; }
    .perm-item { display:flex; align-items:center; gap:8px; padding:4px 0; margin:0; cursor:pointer; font-size:13px; color:#334155; font-weight:400; }
    .perm-item input { width:15px; height:15px; margin:0; cursor:pointer; accent-color:#2563eb; flex-shrink:0; }
    .perm-item.is-main { font-weight:700; color:#0f172a; }
    .perm-item.is-main .perm-tag { font-size:10px; font-weight:500; color:#64748b; background:#f1f5f9; border-radius:4px; padding:1px 6px; }
    .perm-empty { display:none; text-align:center; color:#94a3b8; padding:30px 0; }
</style>

<div id="perm-picker">
    <div class="perm-toolbar">
        <h5>{{ $t('الصلاحيات', 'Permissions') }} <span class="perm-count" id="perm-count"></span></h5>
        <input type="text" id="perm-search" class="form-control perm-search" placeholder="{{ $t('ابحث عن صلاحية...', 'Search permissions...') }}">
        <div class="perm-links">
            <a id="perm-all">{{ $t('تحديد الكل', 'Select all') }}</a>
            <a id="perm-none">{{ $t('إلغاء تحديد الكل', 'Clear all') }}</a>
        </div>
    </div>

    <div class="perm-grid">
        @foreach ($groups as $group)
            <div class="perm-card" data-group="{{ $group['key'] }}">
                <div class="perm-card-head">
                    <span class="perm-title"><i class="fa {{ $group['icon'] }}"></i> {{ $group['title'] }}
                        <small class="perm-card-count"></small></span>
                    <span class="perm-select-all">{{ $t('تحديد الكل', 'Select all') }}</span>
                </div>
                @foreach ($group['items'] as $item)
                    @php $p = $item['permission']; @endphp
                    <label class="perm-item {{ $item['main'] ? 'is-main' : '' }}">
                        <input type="checkbox" name="permission[]" value="{{ $p->id }}"
                               data-requires="{{ implode(',', $item['requires']) }}"
                               {{ in_array((int) $p->id, $checked, true) ? 'checked' : '' }}>
                        <span class="perm-label">{{ $item['label'] }}</span>
                        @if ($item['main'])
                            <span class="perm-tag">{{ $t('إظهار القسم', 'Show section') }}</span>
                        @endif
                    </label>
                @endforeach
            </div>
        @endforeach
    </div>
    <div class="perm-empty" id="perm-empty">{{ $t('لا توجد نتائج', 'No results') }}</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var root = document.getElementById('perm-picker');
    if (!root) return;
    var boxes = Array.prototype.slice.call(root.querySelectorAll('input[name="permission[]"]'));
    var byId = {};
    boxes.forEach(function (b) { byId[b.value] = b; });
    var reqs = function (b) { return (b.dataset.requires || '').split(',').filter(Boolean); };
    var SELECT_ALL = @json($t('تحديد الكل', 'Select all'));
    var CLEAR_ALL  = @json($t('إلغاء التحديد', 'Clear'));

    function refresh() {
        var total = boxes.length, on = boxes.filter(function (b) { return b.checked; }).length;
        document.getElementById('perm-count').textContent = '(' + on + ' / ' + total + ')';
        root.querySelectorAll('.perm-card').forEach(function (card) {
            var cb = card.querySelectorAll('input[type=checkbox]');
            var n = Array.prototype.filter.call(cb, function (b) { return b.checked; }).length;
            card.querySelector('.perm-card-count').textContent = n ? '(' + n + '/' + cb.length + ')' : '';
            card.querySelector('.perm-select-all').textContent = (n === cb.length) ? CLEAR_ALL : SELECT_ALL;
        });
    }

    // اختيار صلاحية يحدد صلاحية إظهار قسمها، وإلغاء صلاحية إظهار القسم يلغي صلاحياته
    function check(b) {
        b.checked = true;
        reqs(b).forEach(function (id) { if (byId[id] && !byId[id].checked) check(byId[id]); });
    }
    function uncheck(b) {
        b.checked = false;
        boxes.forEach(function (o) { if (o.checked && reqs(o).indexOf(b.value) !== -1) uncheck(o); });
    }

    boxes.forEach(function (b) {
        b.addEventListener('change', function () { b.checked ? check(b) : uncheck(b); refresh(); });
    });

    root.querySelectorAll('.perm-select-all').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var cb = Array.prototype.slice.call(btn.closest('.perm-card').querySelectorAll('input[type=checkbox]'));
            var all = cb.every(function (b) { return b.checked; });
            cb.forEach(function (b) { all ? uncheck(b) : check(b); });
            refresh();
        });
    });

    document.getElementById('perm-all').addEventListener('click', function () { boxes.forEach(function (b) { b.checked = true; }); refresh(); });
    document.getElementById('perm-none').addEventListener('click', function () { boxes.forEach(function (b) { b.checked = false; }); refresh(); });

    document.getElementById('perm-search').addEventListener('input', function () {
        var q = this.value.trim().toLowerCase(), any = false;
        root.querySelectorAll('.perm-card').forEach(function (card) {
            var titleHit = card.querySelector('.perm-title').textContent.toLowerCase().indexOf(q) !== -1;
            var visible = 0;
            card.querySelectorAll('.perm-item').forEach(function (it) {
                var show = !q || titleHit || it.textContent.toLowerCase().indexOf(q) !== -1;
                it.style.display = show ? '' : 'none';
                if (show) visible++;
            });
            card.style.display = visible ? '' : 'none';
            if (visible) any = true;
        });
        document.getElementById('perm-empty').style.display = any ? 'none' : 'block';
    });

    refresh();
});
</script>
