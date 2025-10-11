@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Vite Test</div>

                <div class="card-body">
                    <h1>Vite Manifest Test</h1>
                    <p>If you can see this page without Vite errors, the manifest issue has been resolved.</p>
                    
                    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
                        <p>Manifest file detected.</p>
                    @else
                        <p>No manifest file found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection