<!DOCTYPE html>
<html>
<head>
    <title>Notifikasi Stok Kritis</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Peringatan Stok Kritis</h2>
    <p>Halo, beberapa item di inventaris telah mencapai batas stok minimum. Mohon segera lakukan pemesanan ulang.</p>

    <table>
        <thead>
            <tr>
                <th>Kode Produk</th>
                <th>Nama Produk</th>
                <th>Sisa Stok</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lowStockItems as $item)
                <tr>
                    <td>{{ $item->product_id }}</td>
                    <td>{{ $item->sub_part_name }}</td>
                    <td>{{ $item->quantity_available }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p>Terima kasih.</p>
</body>
</html>