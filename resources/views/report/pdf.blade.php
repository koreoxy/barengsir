<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan_Vendor_POS_{{ $startDate->format('Ymd') }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #334155;
            line-height: 1.5;
            margin: 0;
            padding: 40px;
            font-size: 12px;
            background-color: #ffffff;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #e2e8f0;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header-title h1 {
            margin: 0;
            font-size: 20px;
            font-weight: 800;
            color: #1e293b;
            letter-spacing: -0.5px;
        }

        .header-title p {
            margin: 5px 0 0 0;
            font-size: 11px;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
        }

        .header-meta {
            text-align: right;
        }

        .header-meta p {
            margin: 0 0 5px 0;
            font-size: 11px;
            color: #475569;
            font-weight: 500;
        }

        .header-meta span {
            font-weight: 700;
            color: #2563eb;
        }

        .section-title {
            font-size: 11px;
            font-weight: 800;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 30px;
            margin-bottom: 12px;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 6px;
        }

        /* Metric Grid */
        .metrics-grid {
            display: grid;
            grid-template-cols: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .metric-card {
            background-color: #f8fafc;
            border: 1px solid #f1f5f9;
            border-radius: 12px;
            padding: 15px;
        }

        .metric-card p {
            margin: 0 0 5px 0;
            font-size: 9px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .metric-card h3 {
            margin: 0;
            font-size: 16px;
            font-weight: 800;
            color: #1e293b;
        }

        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        th {
            background-color: #f8fafc;
            color: #64748b;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 10px 12px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        td {
            padding: 10px 12px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
            font-size: 11px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: 700;
        }

        .font-black {
            font-weight: 900;
        }

        .text-emerald {
            color: #16a34a;
        }

        .text-rose {
            color: #dc2626;
        }

        .bg-gray-subtle {
            background-color: #f8fafc;
        }

        /* Custom print optimization styles */
        @media print {
            body {
                padding: 0;
                font-size: 10px;
            }

            .metric-card {
                padding: 10px;
            }

            .metric-card h3 {
                font-size: 14px;
            }

            td, th {
                padding: 8px 10px;
            }

            /* Prevent page break inside table rows */
            tr {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <div class="header">
        <div class="header-title">
            <h1>Laporan Kinerja Bisnis</h1>
            <p>Sistem POS POS_Central</p>
        </div>
        <div class="header-meta">
            <p>Periode: <span>{{ $startDate->translatedFormat('d M Y') }} - {{ $endDate->translatedFormat('d M Y') }}</span></p>
            <p>Tanggal Cetak: {{ now()->translatedFormat('d M Y, H:i') }}</p>
        </div>
    </div>

    <!-- Summary Metrics Grid -->
    <div class="metrics-grid">
        <div class="metric-card">
            <p>Total Pendapatan Kotor</p>
            <h3>Rp {{ number_format($finance['gross_revenue'], 0, ',', '.') }}</h3>
        </div>
        <div class="metric-card">
            <p>Volume Penjualan</p>
            <h3>{{ number_format($metrics['total_transactions']) }} Trx</h3>
        </div>
        <div class="metric-card">
            <p>Laba Bersih (Net Profit)</p>
            <h3 class="text-emerald">Rp {{ number_format($finance['net_profit'], 0, ',', '.') }}</h3>
        </div>
    </div>

    <!-- P&L Statement Section -->
    <div class="section-title">Laporan Laba Rugi terperinci (Profit & Loss)</div>
    <table>
        <thead>
            <tr>
                <th style="width: 70%;">Komponen Keuangan</th>
                <th style="width: 30%;" class="text-right">Jumlah (Rupiah)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1. Pendapatan Penjualan Kotor (Gross Revenue)</td>
                <td class="text-right font-bold">Rp {{ number_format($finance['gross_revenue'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="padding-left: 20px; color: #64748b;">Harga Pokok Penjualan (HPP / COGS) - Modal Barang</td>
                <td class="text-right text-rose">- Rp {{ number_format($finance['cogs'], 0, ',', '.') }}</td>
            </tr>
            <tr class="bg-gray-subtle">
                <td class="font-bold">Laba Kotor (Gross Profit)</td>
                <td class="text-right font-black" style="color: #2563eb;">Rp {{ number_format($finance['gross_profit'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>2. Total Pengeluaran Operasional (OPEX)</td>
                <td class="text-right text-rose">- Rp {{ number_format($finance['opex'], 0, ',', '.') }}</td>
            </tr>
            <tr class="bg-gray-subtle" style="border-top: 2px solid #cbd5e1; border-bottom: 2px solid #cbd5e1;">
                <td class="font-bold uppercase" style="font-size: 11px;">Laba Bersih (Net Profit)</td>
                <td class="text-right font-black text-emerald" style="font-size: 12px;">Rp {{ number_format($finance['net_profit'], 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Best Selling Products Section -->
    <div class="section-title">Analisis Performa Produk Terlaris</div>
    <table>
        <thead>
            <tr>
                <th>SKU</th>
                <th>Nama Produk</th>
                <th class="text-right">Qty Terjual</th>
                <th class="text-right">Omset Kotor</th>
                <th class="text-right">Kontribusi Laba</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products['performance']->take(15) as $prod)
            <tr>
                <td class="font-bold" style="font-family: monospace;">{{ $prod->sku }}</td>
                <td>{{ $prod->name }}</td>
                <td class="text-right">{{ number_format($prod->total_sold) }} pcs</td>
                <td class="text-right">Rp {{ number_format($prod->gross_sales, 0, ',', '.') }}</td>
                <td class="text-right font-bold text-emerald">Rp {{ number_format($prod->total_profit, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center" style="color: #94a3b8;">Belum ada riwayat transaksi penjualan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Auto launch Print -->
    <script>
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 300);
        });
    </script>
</body>
</html>
