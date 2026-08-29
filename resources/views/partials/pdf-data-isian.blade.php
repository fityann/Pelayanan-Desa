@php
    $formFieldsPdf = (array) ($surat->jenisSurat->form_fields ?? []);
    $dataIsianPdf = (array) ($surat->data_isian ?? []);
    $ciRows = array_filter($dataIsianPdf, fn($v) => $v !== null && $v !== '');
@endphp
@if (count($ciRows) > 0)
    <tr>
        <td colspan="3" style="padding-top: 8px; padding-bottom: 4px; font-weight: bold; text-decoration: underline;">Form Isian Tambahan</td>
    </tr>
    @foreach ($formFieldsPdf as $field)
        @if (isset($field['name']) && array_key_exists($field['name'], $ciRows))
            <tr>
                <td class="label">{{ $field['label'] ?? ucwords(str_replace('_', ' ', $field['name'])) }}</td>
                <td class="colon">:</td>
                <td>{{ $ciRows[$field['name']] }}</td>
            </tr>
        @endif
    @endforeach
    @foreach ($ciRows as $key => $value)
        @unless (collect($formFieldsPdf)->contains(fn($f) => ($f['name'] ?? null) === $key))
            <tr>
                <td class="label">{{ ucwords(str_replace('_', ' ', $key)) }}</td>
                <td class="colon">:</td>
                <td>{{ $value }}</td>
            </tr>
        @endunless
    @endforeach
@endif