<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Doctor prescription details">

<title>ClinicalPro || Prescription Details</title>
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">

<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">

<style>
    /* ON-SCREEN STYLING */
    .prescription-paper {
        background: #fff;
        padding: 40px;
        border-radius: 0;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        position: relative;
        border-top: 5px solid #007bff; /* Medical Blue Brand Color */
    }

    .rx-header {
        border-bottom: 2px solid #f1f1f1;
        padding-bottom: 20px;
        margin-bottom: 30px;
    }
    
    .hospital-brand h2 {
        color: #007bff;
        font-weight: 700;
        margin-bottom: 5px;
    }
    
    .rx-symbol {
        font-family: 'Georgia', serif;
        font-size: 40px;
        font-weight: bold;
        color: #007bff;
        margin: 20px 0 10px 0;
    }

    .info-box {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 15px;
        border-left: 3px solid #007bff;
    }
    
    .info-box h6 {
        font-weight: bold;
        text-transform: uppercase;
        font-size: 12px;
        color: #666;
        margin-bottom: 10px;
    }

    .table-meds thead th {
        background-color: #007bff;
        color: #fff;
        border: none;
    }
    
    .signature-area {
        margin-top: 60px;
        text-align: right;
    }
    .signature-line {
        display: inline-block;
        border-top: 1px solid #333;
        width: 250px;
        margin-bottom: 5px;
    }

    /* PRINT ONLY STYLING - Hides Sidebars/Buttons when printing */
    @media print {
        body * {
            visibility: hidden;
        }
        .page-loader-wrapper, .navbar, #leftsidebar, .breadcrumb, .block-header, .btn, .alert {
            display: none !important;
        }
        #printable-area, #printable-area * {
            visibility: visible;
        }
        #printable-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 0;
            box-shadow: none;
            border: none;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
    }
</style>
</head>
<body class="theme-cyan">

<div class="page-loader-wrapper">
    <div class="loader">
        <div class="m-t-30"><img class="zmdi-hc-spin" src="{{ asset('assets/images/logo.svg') }}" width="48" height="48" alt="Clinical Pro"></div>
        <p>Please wait...</p>
    </div>
</div>

@include('doctor.sidemenu')

<section class="content">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 d-flex justify-content-between align-items-center">
                <h2 class="m-0"><i class="zmdi zmdi-print"></i> <span>Prescription View</span></h2>
                <ul class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="zmdi zmdi-home"></i> Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('doctor.prescriptions') }}">Prescriptions</a></li>
                    <li class="breadcrumb-item active">View</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
        @endif

        <div class="row mb-3">
            <div class="col-12 text-right">
                <a href="{{ route('doctor.prescriptions') }}" class="btn btn-secondary btn-round"><i class="zmdi zmdi-arrow-left"></i> Back</a>
                <button class="btn btn-primary btn-round" onclick="window.print()"><i class="zmdi zmdi-print"></i> Print Paper</button>
                
                @if(Route::has('doctor.prescriptions.print'))
                <a href="{{ route('doctor.prescriptions.print', $prescription->id) }}" class="btn btn-info btn-round"><i class="zmdi zmdi-download"></i> Download PDF</a>
                @endif
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-lg-10 offset-lg-1">
                
                <div class="card prescription-paper" id="printable-area">
                    
                    <div class="rx-header d-flex justify-content-between align-items-start">
                        <div class="hospital-brand">
                            <h2><img src="{{ asset('assets/images/logo.svg') }}" width="30" alt=""> Clinical Pro</h2>
                            <p class="text-muted m-0">123 Alagbaka Estate, Akure, Ondo State</p>
                            <p class="text-muted m-0">Phone: +234 800 CLINICAL | Email: help@clinicalpro.com</p>
                        </div>
                        <div class="prescription-meta text-right">
                            <h5 class="m-0">PRESCRIPTION</h5>
                            <p class="m-0"><strong>ID:</strong> #{{ $prescription->id }}</p>
                            <p class="m-0"><strong>Date:</strong> {{ $prescription->created_at->format('d M, Y') }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-box">
                                <h6>Patient Details</h6>
                                <p class="m-0"><strong>Name:</strong> {{ $prescription->patient->name ?? 'N/A' }}</p>
                                <p class="m-0"><strong>ID:</strong> {{ $prescription->patient->user_id ?? 'N/A' }}</p>
                                <p class="m-0"><strong>Age/Gender:</strong> {{ $prescription->patient->age_gender ?? 'N/A' }}</p>
                                <p class="m-0"><strong>Phone:</strong> {{ $prescription->patient->phone ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <h6>Prescribed By</h6>
                                <p class="m-0"><strong>Dr. {{ $prescription->doctor->name ?? 'N/A' }}</strong></p>
                                <p class="m-0">{{ $prescription->doctor->doctorProfile->specialization ?? 'General Practitioner' }}</p>
                                <p class="m-0"><strong>Email:</strong> {{ $prescription->doctor->email ?? 'N/A' }}</p>
                                @if($prescription->consultation)
                                <p class="m-0"><small>Consultation Type: {{ ucfirst($prescription->consultation->delivery_channel ?? 'Visit') }}</small></p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="rx-symbol">Rx</div>

                    <div class="body">
                        <div class="table-responsive">
                            <table class="table m-b-0">
                                <thead>
                                    <tr>
                                        <th>Medication</th>
                                        <th>Dosage</th>
                                        <th>Type</th>
                                        <th>Duration</th>
                                        <th>Use Pattern</th>
                                        <th>Instructions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($prescription->items as $item)
                                    <tr>
                                        <td>
                                            <strong>{{ $item->drug->name ?? $item->medication_name ?? 'N/A' }}</strong>
                                        </td>
                                        <td>{{ $item->dosage ?? '-' }}</td>
                                        <td>{{ $item->type ?? '-' }}</td>
                                        <td>{{ $item->duration ?? '-' }}</td>
                                        <td>{{ $item->use_pattern ?? '-' }}</td>
                                        <td>{{ $item->instructions ?? 'As directed' }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No medications prescribed</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if($prescription->notes)
                    <div class="mt-4">
                        <h6><strong>Doctor's Clinical Notes:</strong></h6>
                        <p class="text-muted" style="border: 1px dashed #ddd; padding: 10px;">
                            {{ $prescription->notes }}
                        </p>
                    </div>
                    @endif

                    <div class="signature-area">
                        <div class="signature-line"></div>
                        <p class="m-0"><strong>Dr. {{ $prescription->doctor->name }}</strong></p>
                        <small>Signature & Stamp</small>
                    </div>

                    <div class="text-center mt-5 pt-4 border-top">
                        <small class="text-muted">
                            This is a computer-generated document from Clinical Pro System.<br>
                            Generated on {{ now()->format('l, F j, Y') }}
                        </small>
                    </div>

                </div> </div>
        </div>
    </div>
</section>

<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script> 
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script> 
<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>
</body>
</html>