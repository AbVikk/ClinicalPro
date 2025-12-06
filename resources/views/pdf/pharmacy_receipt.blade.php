<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Courier New', monospace; font-size: 10px; margin: 0; padding: 5px; }
        .header { text-align: center; margin-bottom: 10px; }
        .header h2 { margin: 0; font-size: 14px; }
        .divider { border-top: 1px dashed #000; margin: 5px 0; }
        .item-row { display: flex; justify-content: space-between; }
        .item-name { width: 60%; }
        .item-qty { width: 10%; text-align: center; }
        .item-price { width: 30%; text-align: right; }
        .totals { text-align: right; margin-top: 10px; }
        .footer { text-align: center; margin-top: 15px; font-size: 9px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>CLINICAL PRO PHARMACY</h2>
        <p>123 Alagbaka Estate, Akure</p>
        <p>Tel: 0800-CLINIC</p>
        <p>Date: {{ $order->created_at->format('d/m/Y H:i') }}</p>
        <p>Rcpt #: {{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
    </div>

    <div class="divider"></div>

    <table width="100%">
        @foreach($order->items as $item)
        <tr>
            <td width="55%">{{ substr($item->drug->name, 0, 15) }}</td>
            <td width="15%">x{{ $item->quantity }}</td>
            <td width="30%" align="right">{{ number_format($item->total_price, 0) }}</td>
        </tr>
        @endforeach
    </table>

    <div class="divider"></div>

    <div class="totals">
        <strong>TOTAL: ₦{{ number_format($order->total_amount, 2) }}</strong><br>
        <small>Paid via: {{ ucfirst(str_replace('_', ' ', $order->payment->method ?? 'Cash')) }}</small>
    </div>

    <div class="footer">
        <p>Served by: {{ $order->pharmacist->name }}</p>
        <p>Get well soon!</p>
    </div>
</body>
</html>