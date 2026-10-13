<div class="table-responsive">
    <table class="table table-striped table-bordered text-center mb-0">
        <thead class="bg-primary-transparent">
            <tr>
                <th>{{ __('home.record_number') }}</th>
                <th>{{ __('home.journal_entry_date') }}</th>
                <th>{{ __('home.journal_general_statement') }}</th>
                <th>{{ app()->getLocale() == 'ar' ? 'المصدر' : 'Source' }}</th>
                <th>{{ __('home.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $row)
            <tr>
                <td class="font-weight-bold text-dark">{{ $row->id }}</td>
                <td>{{ $row->date }}</td>
                <td class="text-right">{{ Str::limit($row->general_notes, 50) }}</td>
                <td>
                    @if($row->is_auto)
                        <span class="badge badge-warning"><i class="fa fa-lock"></i> {{ $row->source_label }}</span>
                    @else
                        <span class="badge badge-light">{{ app()->getLocale() == 'ar' ? 'قيد يدوي' : 'Manual' }}</span>
                    @endif
                </td>
                <td>
                    @if($row->is_auto)
                        <button class="btn btn-sm btn-secondary-transparent" onclick="editFromTable({{ $row->id }})">
                            <i class="fa fa-eye"></i> {{ app()->getLocale() == 'ar' ? 'عرض' : 'View' }}
                        </button>
                    @else
                        <button class="btn btn-sm btn-info-transparent" onclick="editFromTable({{ $row->id }})">
                            <i class="fa fa-edit"></i> {{ __('home.journal_update') }}
                        </button>
                    @endif
                    <button class="btn btn-sm btn-success-transparent" onclick="printFromTable({{ $row->id }})">
                        <i class="fa fa-print"></i>
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5">{{ __('home.no_data') }}</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-center mt-3">
    {!! $records->links() !!}
</div>

<script>
    // دالة الطباعة السريعة من الجدول
    function printFromTable(id) {
        let form = $(`<form action="{{ url('print_daily_record') }}" method="POST" target="_blank">
            @csrf<input type="hidden" name="record_id_print" value="${id}"></form>`);
        $('body').append(form); form.submit(); form.remove();
    }
</script>