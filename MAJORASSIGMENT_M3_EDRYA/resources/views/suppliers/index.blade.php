cat > resources/views/suppliers/index.blade.php <<'BLADE'
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publisher & Supplier</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f3f4f6; margin: 0; padding: 30px; color: #1f2937; }
        .container { max-width: 1100px; margin: auto; background: white; padding: 25px; border-radius: 12px; }
        h1 { margin-top: 0; }
        .btn { display: inline-block; padding: 10px 15px; background: #2563eb; color: white; text-decoration: none; border-radius: 6px; border: 0; cursor: pointer; }
        .success { background: #dcfce7; padding: 12px; margin: 15px 0; border-radius: 6px; color: #166534; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #e5e7eb; padding: 12px; text-align: left; }
        th { background: #eff6ff; }
        .actions { display: flex; gap: 8px; }
        .edit { background: #d97706; }
        .delete { background: #dc2626; }
        .empty { text-align: center; padding: 25px; color: #6b7280; }
    </style>
</head>
<body>
<div class="container">
    <h1>Publisher & Supplier Management</h1>
    <p>Kelola data publisher dan supplier perpustakaan.</p>

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <a class="btn" href="{{ route('suppliers.create') }}">+ Tambah Supplier</a>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama</th>
                <th>Kontak Penjualan</th>
                <th>Alamat</th>
                <th>Wilayah</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($suppliers as $supplier)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $supplier->name }}</td>
                    <td>{{ $supplier->sales_contact ?: '-' }}</td>
                    <td>{{ $supplier->address ?: '-' }}</td>
                    <td>{{ $supplier->region ?: '-' }}</td>
                    <td>
                        <div class="actions">
                            <a class="btn edit" href="{{ route('suppliers.edit', $supplier) }}">Edit</a>
                            <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn delete" type="submit">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="empty">Belum ada data supplier. Klik Tambah Supplier untuk memulai.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
</body>
</html>
