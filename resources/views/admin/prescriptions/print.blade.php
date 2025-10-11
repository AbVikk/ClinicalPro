<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Print Prescription">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>:: Clinical Pro :: Print Prescription</title>
<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">

<style>
    body {
        font-family: Arial, sans-serif;
        font-size: 14px;
        line-height: 1.4;
        color: #333;
        margin: 0;
        padding: 20px;
    }
    
    .prescription-header {
        text-align: center;
        border-bottom: 2px solid #333;
        padding-bottom: 15px;
        margin-bottom: 20px;
    }
    
    .prescription-header h1 {
        margin: 0 0 10px 0;
        font-size: 24px;
        color: #333;
    }
    
    .prescription-header p {
        margin: 0;
        font-size: 16px;
    }
    
    .prescription-info {
        margin-bottom: 20px;
    }
    
    .prescription-info .row {
        margin-bottom: 10px;
    }
    
    .prescription-info .label {
        font-weight: bold;
        width: 150px;
        display: inline-block;
    }
    
    .medications-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    
    .medications-table th,
    .medications-table td {
        border: 1px solid #333;
        padding: 8px;
        text-align: left;
    }
    
    .medications-table th {
        background-color: #f2f2f2;
        font-weight: bold;
    }
    
    .notes-section {
        margin-bottom: 20px;
    }
    
    .signature-section {
        margin-top: 40px;
        text-align: right;
    }
    
    .signature-line {
        display: inline-block;
        width: 200px;
        border-top: 1px solid #333;
        margin-top: 40px;
        padding-top: 5px;
    }
    
    .print-only {
        display: block;
    }
    
    .no-print {
        display: none;
    }
    
    @media print {
        .no-print {
            display: none !important;
        }
        
        body {
            padding: 10px;
            font-size: 12px;
        }
        
        .signature-line {
            margin-top: 20px;
        }
    }
    
    @media screen and (max-width: 768px) {
        .prescription-info .label {
            width: 100%;
            display: block;
            margin-bottom: 5px;
        }
    }
</style>
</head>
<body>
    <div class="no-print" style="text-align: right; margin-bottom: 20px;">
        <button class="btn btn-primary" onclick="window.print()">Print Prescription</button>
        <a href="{{ route('admin.prescriptions.show', $prescription->id) }}" class="btn btn-secondary">Back to Prescription</a>
    </div>
    
    <div class="prescription-header">
        <h1>PRESCRIPTION</h1>
        <p>Clinical Pro Medical System</p>
        <p>{{ date('F d, Y') }}</p>
    </div>
    
    <div class="prescription-info">
        <div class="row">
            <span class="label">Patient:</span>
            <span>{{ $prescription->patient->name ?? 'N/A' }}</span>
        </div>
        <div class="row">
            <span class="label">Patient ID:</span>
            <span>{{ $prescription->patient->user_id ?? 'N/A' }}</span>
        </div>
        <div class="row">
            <span class="label">Date of Birth:</span>
            <span>{{ $prescription->patient->date_of_birth ? $prescription->patient->date_of_birth->format('F d, Y') : 'N/A' }}</span>
        </div>
        <div class="row">
            <span class="label">Prescribed By:</span>
            <span>{{ $prescription->doctor->name ?? 'N/A' }}</span>
        </div>
        <div class="row">
            <span class="label">Prescription ID:</span>
            <span>{{ $prescription->id }}</span>
        </div>
        <div class="row">
            <span class="label">Issue Date:</span>
            <span>{{ $prescription->created_at->format('F d, Y') }}</span>
        </div>
        <div class="row">
            <span class="label">Expiry Date:</span>
            <span>{{ $prescription->created_at->addMonths(3)->format('F d, Y') }}</span>
        </div>
        <div class="row">
            <span class="label">Diagnosis:</span>
            <span>{{ $prescription->notes ?? 'N/A' }}</span>
        </div>
        <div class="row">
            <span class="label">Refills Allowed:</span>
            <span>{{ $prescription->refills_allowed ?? 0 }}</span>
        </div>
    </div>
    
    <h3>Medications</h3>
    <table class="medications-table">
        <thead>
            <tr>
                <th>Medication</th>
                <th>Dosage</th>
                <th>Frequency</th>
                <th>Duration</th>
                <th>Instructions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($prescription->items as $item)
                @php
                    $dosageInstructions = json_decode($item->dosage_instructions, true);
                @endphp
                <tr>
                    <td>{{ $item->drug->name ?? 'N/A' }}</td>
                    <td>{{ $dosageInstructions['dosage'] ?? 'N/A' }}</td>
                    <td>{{ $dosageInstructions['frequency'] ?? 'N/A' }}</td>
                    <td>{{ $dosageInstructions['duration'] ?? 'N/A' }}</td>
                    <td>{{ $dosageInstructions['instructions'] ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No medications found for this prescription.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="notes-section">
        <h3>Notes</h3>
        <p>{{ $prescription->notes ?? 'No additional notes provided.' }}</p>
    </div>
    
    <div class="signature-section">
        <div class="signature-line">
            Doctor's Signature
        </div>
    </div>
    
    <div class="no-print" style="text-align: right; margin-top: 20px;">
        <button class="btn btn-primary" onclick="window.print()">Print Prescription</button>
        <a href="{{ route('admin.prescriptions.show', $prescription->id) }}" class="btn btn-secondary">Back to Prescription</a>
    </div>
    
    <script>
        // Auto-print when page loads (optional)
        // Uncomment the line below if you want to auto-print when the page loads
        // window.onload = function() {
        //     window.print();
        // };
        
        // Handle print event
        window.addEventListener('beforeprint', function() {
            // Any actions before printing
        });
        
        window.addEventListener('afterprint', function() {
            // Any actions after printing
        });
    </script>
</body>
</html>