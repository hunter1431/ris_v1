<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111; }
        h1 { font-size: 16px; text-align: center; margin-bottom: 4px; }
        .sub { text-align: center; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #111; padding: 5px; vertical-align: top; }
        .meta td { border: 0; padding: 3px; }
        .sign td { height: 82px; text-align: center; vertical-align: bottom; }
        .signature { max-height: 38px; max-width: 130px; display: block; margin: 0 auto 4px; }
        .qr { margin-top: 12px; text-align: right; font-size: 9px; }
        .qr img { height: 90px; width: 90px; }
    </style>
</head>
<body>
    <h1>REQUISITION AND ISSUE SLIP</h1>
    <div class="sub">Official RIS Form</div>
    <table class="meta">
        <tr><td><strong>Entity Name:</strong> {{ $ris->entity_name }}</td><td><strong>Fund Cluster:</strong> {{ $ris->fund_cluster }}</td><td><strong>RIS No:</strong> {{ $ris->ris_no }}</td></tr>
        <tr><td><strong>Division:</strong> {{ $ris->division->name }}</td><td><strong>Office:</strong> {{ $ris->office }}</td><td><strong>Responsibility Center Code:</strong> {{ $ris->responsibility_center_code }}</td></tr>
        <tr><td colspan="3"><strong>Purpose:</strong> {{ $ris->purpose }}</td></tr>
    </table>
    <table>
        <thead><tr><th>Stock No.</th><th>Unit</th><th>Description</th><th>Qty Requested</th><th>Qty Issued</th><th>Remarks</th></tr></thead>
        <tbody>
            @foreach ($ris->details as $detail)
                <tr>
                    <td>{{ $detail->stock_no }}</td>
                    <td>{{ $detail->unit }}</td>
                    <td>{{ $detail->description }}</td>
                    <td>{{ $detail->qty_requested }}</td>
                    <td>{{ $detail->qty_issued }}</td>
                    <td>{{ $detail->remarks }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <table class="sign">
        <tr>
            <td>
                @if ($signatures['requester'] ?? null)<img class="signature" src="{{ $signatures['requester'] }}">@endif
                Requested by<br>{{ $ris->requestedBy?->name }}
            </td>
            <td>
                @if ($signatures['approver'] ?? null)<img class="signature" src="{{ $signatures['approver'] }}">@endif
                Approved by<br>{{ $ris->approvedBy?->name }}
            </td>
            <td>
                @if ($signatures['issuer'] ?? null)<img class="signature" src="{{ $signatures['issuer'] }}">@endif
                Issued by<br>{{ $ris->issuedBy?->name }}
            </td>
            <td>
                @if ($signatures['receiver'] ?? null)<img class="signature" src="{{ $signatures['receiver'] }}">@endif
                Received by<br>{{ $ris->receivedBy?->name }}
            </td>
        </tr>
    </table>
    <div class="qr">
        <img src="{{ $qrCode }}" alt="QR verification code">
        <div>Verify: {{ $verificationUrl }}</div>
    </div>
</body>
</html>
