<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title ?? 'Invoice' }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #2c3e50; padding-bottom: 15px; }
        .header h1 { margin: 0; color: #2c3e50; font-size: 24px; }
        .header p { margin: 5px 0; color: #666; }
        .invoice-info { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .info-block { margin-bottom: 15px; }
        .info-block strong { display: block; margin-bottom: 5px; color: #2c3e50; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #2c3e50; color: white; padding: 10px; text-align: left; }
        td { padding: 10px; border-bottom: 1px solid #ddd; }
        .total { text-align: right; margin-top: 20px; font-size: 18px; font-weight: bold; color: #2c3e50; }
        .footer { text-align: center; margin-top: 40px; color: #999; font-size: 12px; border-top: 1px solid #ddd; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>EasyDigiPay</h1>
        <p>Investment Receipt</p>
    </div>

    <div class="info-block">
        <strong>Invoice Number:</strong> {{ $invoice_number ?? 'N/A' }}
    </div>
    <div class="info-block">
        <strong>Date:</strong> {{ $date ?? date('m/d/Y') }}
    </div>

    @if(isset($investments))
    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Investment #{{ $investments->id ?? '' }}</td>
                <td>₹{{ number_format($investments->amount ?? 0, 2) }}</td>
                <td>{{ $investments->status == 1 ? 'Active' : 'Inactive' }}</td>
                <td>{{ $investments->created_at ?? 'N/A' }}</td>
            </tr>
        </tbody>
    </table>

    <div class="total">
        Total: ₹{{ number_format($investments->amount ?? 0, 2) }}
    </div>
    @endif

    <div class="footer">
        <p>Thank you for your investment with EasyDigiPay</p>
        <p>This is a computer-generated document and does not require a signature.</p>
    </div>
</body>
</html>
