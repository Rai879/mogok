{{-- resources/views/partials/parts_table_rows.blade.php --}}
@forelse ($parts as $part)
    <tr>
        <td>{{ $part->name }}</td>
        <td>{{ $part->part_number }}</td>
        <td>
            @if ($part->image)
                <img src="{{ asset('storage/' . $part->image) }}" alt="[Image of Sparepart]" class="img-thumbnail" style="max-width: 50px; height: auto; border-radius: 3px;">
            @else
                Tidak Ada Gambar
            @endif
        </td>
        <td>{{ $part->category->name ?? '-' }}</td>
        <td>Rp {{ number_format($part->price, 0, ',', '.') }}</td>
        <td>{{ $part->stock_quantity }}</td>
        <td>{{ $part->minimum_stock }}</td>
        <td>{{ $part->condition }}</td>
        <td>{{ $part->brand ?? '-' }}</td>
        <td><span class="badge {{ $part->is_active ? 'bg-success' : 'bg-secondary' }}">
                {{ $part->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
        <td>
            <button class="btn btn-sm btn-warning edit-btn"
                data-id="{{ $part->id }}"
                data-name="{{ $part->name }}"
                data-part_number="{{ $part->part_number }}"
                data-category_id="{{ $part->category_id }}"
                data-price="{{ $part->price }}"
                data-stock_quantity="{{ $part->stock_quantity }}"
                data-minimum_stock="{{ $part->minimum_stock }}"
                data-condition="{{ $part->condition }}"
                data-brand="{{ $part->brand }}"
                data-description="{{ $part->description }}"
                data-specifications="{{ $part->specifications }}" {{-- Pass as is, JS will parse --}}
                data-notes="{{ $part->notes }}"
                data-is_active="{{ $part->is_active }}"
                data-image="{{ $part->image }}"> Edit </button> {{-- Tambahkan data-image --}}

            <button type="button" class="btn btn-sm btn-danger delete-btn"
                data-id="{{ $part->id }}"
                data-name="{{ $part->name }}">Hapus</button>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="11" class="text-center">Tidak ada data ditemukan</td> {{-- Sesuaikan colspan --}}
    </tr>
@endforelse
