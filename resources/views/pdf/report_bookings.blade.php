<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Booking NginepYuk</title>
<style>
    * { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; }
    body { margin: 20px; color: #1e293b; }
    h1 { font-size: 18px; color: #1d4ed8; margin-bottom: 4px; }
    .subtitle { color: #64748b; margin-bottom: 20px; font-size: 11px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th { background: #1d4ed8; color: white; padding: 8px 10px; text-align: left; font-size: 10px; }
    td { padding: 7px 10px; border-bottom: 1px solid #e2e8f0; }
    tr:nth-child(even) td { background: #f8fafc; }
    .total { font-weight: bold; color: #1d4ed8; }
    .footer { margin-top: 20px; font-size: 10px; color: #94a3b8; text-align: center; }
    .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 9px; font-weight: bold; }
    .badge-confirmed { background: #d1fae5; color: #065f46; }
    .badge-pending { background: #fef3c7; color: #92400e; }
    .badge-cancelled { background: #fee2e2; color: #991b1b; }
    .badge-other { background: #f1f5f9; color: #475569; }
</style>
</head>
<body>
<h1>🏨 NginepYuk — Laporan Booking</h1>
<p class="subtitle">Dicetak: {{ now()->format('d M Y H:i') }} • Total: {{ $bookings->count() }} pesanan</p>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kode Booking</th>
            <th>Pemesan</th>
            <th>Properti</th>
            <th>Check-in</th>
            <th>Check-out</th>
            <th>Malam</th>
            <th>Total</th>
            <th>Metode</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @php $grandTotal = 0; @endphp
        @foreach($bookings as $i => $b)
        @php if(in_array($b->status,['confirmed','completed'])) $grandTotal += $b->total_amount; @endphp
        <tr>
            <td>{{ $i+1 }}</td>
            <td style="font-family:monospace;font-size:9px;">{{ $b->booking_code }}</td>
            <td>{{ $b->guest_name }}</td>
            <td>{{ Str::limit($b->property->name??'-', 20) }}</td>
            <td>{{ $b->checkin_date->format('d/m/Y') }}</td>
            <td>{{ $b->checkout_date->format('d/m/Y') }}</td>
            <td style="text-align:center">{{ $b->nights }}</td>
            <td class="total">Rp {{ number_format($b->total_amount,0,',','.') }}</td>
            <td>{{ $b->payment_method === 'midtrans' ? 'Gateway' : 'Transfer' }}</td>
            <td>
                @php $bc = in_array($b->status,['confirmed','completed'])?'confirmed':(in_array($b->status,['cancelled','expired'])?'cancelled':(in_array($b->status,['pending','waiting_payment'])?'pending':'other')); @endphp
                <span class="badge badge-{{ $bc }}">{{ $b->status_label }}</span>
            </td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr style="background:#eff6ff">
            <td colspan="7" style="font-weight:bold;text-align:right;padding:10px">Total Revenue (Confirmed/Completed):</td>
            <td style="font-weight:900;color:#1d4ed8;font-size:13px" colspan="3">Rp {{ number_format($grandTotal,0,',','.') }}</td>
        </tr>
    </tfoot>
</table>

<div class="footer">
    NginepYuk — Platform Reservasi Terpercaya | noreply@arifsiddikm.com
</div>
</body>
</html>
