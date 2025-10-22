<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="HTML Debug">

<title>ClinicalPro || HTML Debug</title>
<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">

<!-- Custom Css -->
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">
</head>
<body class="theme-cyan">
@include('doctor.sidemenu')

<section class="content">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <h2><i class="zmdi zmdi-code"></i> <span>HTML Debug</span></h2>
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="zmdi zmdi-home"></i> Home</a></li>
                    <li class="breadcrumb-item active">HTML Debug</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>HTML</strong> Structure Debug</h2>
                    </div>
                    <div class="body">
                        <h4>Link HTML Structure</h4>
                        
                        <div class="debug-section">
                            <h5>Test Links with Different Structures</h5>
                            
                            <!-- Standard link -->
                            <div class="test-item mb-3">
                                <p><strong>Standard Link:</strong></p>
                                <a href="{{ url('/doctor/dashboard') }}" id="standard-link">Standard Dashboard Link</a>
                                <div class="mt-2">
                                    <button class="btn btn-sm btn-info" onclick="inspectElement('standard-link')">Inspect</button>
                                </div>
                            </div>
                            
                            <!-- Button link -->
                            <div class="test-item mb-3">
                                <p><strong>Button Link:</strong></p>
                                <a href="{{ url('/doctor/appointments') }}" class="btn btn-primary" id="button-link">Button Appointments Link</a>
                                <div class="mt-2">
                                    <button class="btn btn-sm btn-info" onclick="inspectElement('button-link')">Inspect</button>
                                </div>
                            </div>
                            
                            <!-- Link with icon -->
                            <div class="test-item mb-3">
                                <p><strong>Link with Icon:</strong></p>
                                <a href="{{ url('/doctor/requests') }}" class="btn btn-success" id="icon-link">
                                    <i class="zmdi zmdi-calendar"></i> Icon Requests Link
                                </a>
                                <div class="mt-2">
                                    <button class="btn btn-sm btn-info" onclick="inspectElement('icon-link')">Inspect</button>
                                </div>
                            </div>
                            
                            <!-- External link -->
                            <div class="test-item mb-3">
                                <p><strong>External Link:</strong></p>
                                <a href="https://google.com" class="btn btn-warning" id="external-link" target="_blank">External Google Link</a>
                                <div class="mt-2">
                                    <button class="btn btn-sm btn-info" onclick="inspectElement('external-link')">Inspect</button>
                                </div>
                            </div>
                            
                            <!-- Patient profile link (if available) -->
                            @if(isset($samplePatient) && $samplePatient)
                            <div class="test-item mb-3">
                                <p><strong>Patient Profile Link:</strong></p>
                                <a href="{{ route('doctor.patient.show', $samplePatient->id) }}" class="btn btn-danger doctor-profile-link" id="patient-link">
                                    <i class="zmdi zmdi-account"></i> Patient Profile Link
                                </a>
                                <div class="mt-2">
                                    <button class="btn btn-sm btn-info" onclick="inspectElement('patient-link')">Inspect</button>
                                </div>
                                <div class="alert alert-info mt-2">
                                    <p><strong>Patient Debug Info:</strong></p>
                                    <p>Patient ID: {{ $samplePatient->id }}</p>
                                    <p>Route: {{ route('doctor.patient.show', $samplePatient->id) }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        <hr>
                        
                        <h4>Debug Information</h4>
                        <div id="debug-info">
                            <p>Click "Inspect" buttons to see HTML structure of each link.</p>
                            <div id="inspection-result" class="mt-3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Jquery Core Js -->
<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/js/doctor-links-fix.js') }}"></script><!-- Doctor Links Fix -->

<script>
function inspectElement(elementId) {
    var element = document.getElementById(elementId);
    if (!element) {
        document.getElementById('inspection-result').innerHTML = '<div class="alert alert-danger">Element not found: ' + elementId + '</div>';
        return;
    }
    
    // Get the HTML structure
    var outerHTML = element.outerHTML;
    
    // Check if it's actually an anchor tag
    var isAnchor = element.tagName.toLowerCase() === 'a';
    
    // Check href attribute
    var href = element.getAttribute('href');
    var hasHref = !!href;
    
    // Check if it has pointer cursor
    var computedStyle = window.getComputedStyle(element);
    var cursor = computedStyle.cursor;
    
    // Check if it's disabled
    var isDisabled = element.hasAttribute('disabled') || computedStyle.pointerEvents === 'none';
    
    var result = `
        <div class="alert alert-info">
            <h5>Inspection Result for: ${elementId}</h5>
            <p><strong>Tag Name:</strong> ${element.tagName}</p>
            <p><strong>Is Anchor Tag:</strong> ${isAnchor ? 'Yes' : 'No'}</p>
            <p><strong>Has href:</strong> ${hasHref ? 'Yes (' + href + ')' : 'No'}</p>
            <p><strong>Cursor Style:</strong> ${cursor}</p>
            <p><strong>Pointer Events:</strong> ${computedStyle.pointerEvents}</p>
            <p><strong>Is Disabled:</strong> ${isDisabled ? 'Yes' : 'No'}</p>
            <p><strong>Outer HTML:</strong></p>
            <pre>${escapeHtml(outerHTML)}</pre>
        </div>
    `;
    
    document.getElementById('inspection-result').innerHTML = result;
}

function escapeHtml(text) {
    var map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    
    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}

$(document).ready(function() {
    // Add click handlers to see what happens
    $('a').on('click', function(e) {
        console.log('Link clicked:', this.id, this.href);
        // Uncomment to prevent navigation for testing
        // e.preventDefault();
    });
    
    // Add hover handlers to see what happens
    $('a').on('mouseover', function(e) {
        console.log('Link hovered:', this.id, this.href);
    });
});
</script>
</body>
</html>