@extends('layouts.app')

@section('content')

@php
$profile = $data['user']->patientProfile;
$medicalData = $profile?->medicalData;
@endphp

<div class="page-container">

    {{-- 1. TOP PROFILE BANNER --}}
    <div class="profile-banner">
        <div class="banner-left">
            <img src="{{ $profile?->getFirstMediaUrl('profile_picture') }}" class="patient-avatar">
            <div class="patient-headline">
                <h1>{{ $data['user']->full_name }}</h1>
                <div class="meta-badges">
                    <span class="meta-tag"><span class="tag-sec">Age:</span> {{ $profile?->age ?? ' ' }}</span>
                </div>
            </div>
        </div>

        <div class="banner-right">
            <div class="brand-block">
                <img src="{{ asset('images/logo.png') }}" alt="Docify Logo" class="brand-logo">
                <div class="brand-text-group">
                    <span class="brand-title">Docify</span>
                    <span class="brand-subtitle">Health Management</span>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. TOP VITALS GRID --}}
    <div class="top-vitals-grid">
        <div class="vital-cell" style="border-top-color: #DC2626;">
            <div class="vital-cell-header"><span>Blood Type</span></div>
            <div class="vital-cell-value">{{ $medicalData?->blood_type ?? 'N/A' }}</div>
        </div>

        <div class="vital-cell" style="border-top-color: #2563EB;">
            <div class="vital-cell-header"><span>Height</span></div>
            <div class="vital-cell-value">{{ $medicalData?->height ?? ' ' }} <span style="font-size:16px; font-weight:500; color:#64748B;">cm</span></div>
        </div>

        <div class="vital-cell" style="border-top-color: #149242;">
            <div class="vital-cell-header"><span>Weight</span></div>
            <div class="vital-cell-value">{{ $medicalData?->weight ?? ' ' }} <span style="font-size:16px; font-weight:500; color:#64748B;">kg</span></div>
        </div>

        <div class="card side-card-chronic">
            <div>
                <h2 class="card-title-main" style="font-size:15px; margin-bottom: 12px;">Chronic Conditions</h2>
                <div class="pill-list">
                    @forelse ($profile?->chronicConditions ?? [] as $condition)
                    <span class="pill-item"> {{ $condition->name }}</span>
                    @empty
                    <p style="color:#94A3B8; font-size:13px;">No chronic conditions recorded.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- 3. MIDDLE DASHBOARD LAYOUT --}}
    <div class="middle-dashboard-grid">

        <div class="card">
            <h2 class="card-title-main" style="margin-bottom: 20px;font-size:16px;">Recent Prescriptions</h2>

            <div class="expandable-content is-collapsed" id="prescContainer">
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    @forelse ($data['prescriptions'] as $index => $prescription)

                    <div class="prescription-block {{ $index > 0 ? 'hide-on-collapse' : '' }}">
                        <div class="presc-header">
                            <div>
                                <div class="doc-name">Dr. {{ $prescription->doctor?->full_name }}</div>
                                <div class="doc-spec">{{ $prescription->doctor?->doctorProfile?->specialization?->name ?? 'Specialist' }}</div>
                            </div>
                            <div class="presc-date-badge">
                                <div>{{ $prescription->created_at?->format('Y-m-d') }}</div>
                            </div>
                        </div>

                        <div class="diag-box">
                            <div class="diag-label">Diagnosis</div>
                            <div class="diag-text">{{ $prescription->diagnosis }}</div>
                        </div>

                        <div class="med-bullets">
                            @foreach ($prescription->items as $item)
                            <div class="bullet-item">
                                <span style="color:#2563EB;">•</span>
                                <strong>{{ $item->medication?->name }}</strong>
                                <span style="color:#64748B; font-size:13px;">  {{ $item->dosage }} ({{ $item->frequency }} times)</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @empty
                    <p style="color:#94A3B8; font-size:14px;">No medical prescriptions found.</p>
                    @endforelse
                </div>
            </div>

            @if(count($data['prescriptions']) > 1)
            <button class="toggle-expand-btn" onclick="toggleCard('prescContainer', this)">
                <span class="btn-text">Show More</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
            </button>
            @endif
        </div>

        <div class="right-sidebar-column">

            <div class="card side-card-allergies">
                <h2 class="card-title-main" style="font-size:16px; margin-bottom: 20px;">Allergies</h2>
                <div>
                    @forelse ($profile?->allergies ?? [] as $index => $allergy)
                    <div class="allergy-row">
                        <span style="font-size:14px; font-weight:600; color:#991B1B;">{{ $allergy->name }}</span>
                    </div>
                    @empty
                    <p style="color:#94A3B8; font-size:13px;">No allergies recorded.</p>
                    @endforelse
                </div>
            </div>

            <div class="card">
                <h2 class="card-title-main" style="font-size:16px;">Current Medications</h2>
                
                <div class="expandable-content is-collapsed" id="medsContainer">
                    <div style="display:flex; flex-direction:column;">
                        @forelse ($data['medications'] as $index => $med)
                        
                        <div class="side-med-row {{ $index > 0 ? 'hide-on-collapse' : '' }}">
                            <div>
                                <div style="font-size:14px; font-weight:700; color:#1E293B;">{{ $med->medication?->name ?? 'Medication' }}</div>
                                <span style="font-size:12px; color:#64748B;">Dosage: {{ $med->dosage }}</span>
                                <div style="font-size:11px; color:#94A3B8; margin-top:2px;">Start Date: {{ $med->created_at?->format('Y-m-d H:i:s') }}</div>
                            </div>
                        </div>
                        @empty
                        <p style="color:#94A3B8; font-size:13px;">No active medications.</p>
                        @endforelse
                    </div>
                </div>

                @if(count($data['medications']) > 1)
                <button class="toggle-expand-btn" onclick="toggleCard('medsContainer', this)">
                    <span class="btn-text">Show More</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>
                @endif
            </div>

        </div>
    </div>

    {{-- 4. DIAGNOSTIC REPORTS --}}
    <div class="card full-width-reports">
        <h2 class="card-title-main" style="margin-bottom: 20px;font-size:16px;">Reports</h2>
        <div class="reports-grid-4">
            @forelse($data['reports'] as $report)
            <div class="report-mini-card">
                <div style="display:flex; align-items:center; gap:12px;">
                    <div>
                        <div style="font-size:13px; font-weight:600; color:#1E293B; max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $report->name }}</div>
                        <span style="font-size:11px; color:#94A3B8;">{{ round($report->size / 1024 / 1024, 2) }} MB • {{ $report->created_at?->format('M d, Y') ?? 'Recent' }}</span>
                    </div>
                </div>
                <a href="{{ $report->getFullUrl() }}" target="_blank" class="view-link">View</a>
            </div>
            @empty
            <p style="color:#94A3B8; font-size:14px; grid-column: 1/-1;">No reports found.</p>
            @endforelse
        </div>
    </div>

</div>

<script>
    function toggleCard(containerId, button) {
        const container = document.getElementById(containerId);
        const btnText = button.querySelector('.btn-text');

        container.classList.toggle('is-collapsed');
        button.classList.toggle('is-active');

        if (!container.classList.contains('is-collapsed')) {
            btnText.textContent = 'Show Less';
        } else {
            btnText.textContent = 'Show More';
            container.scrollIntoView({
                behavior: 'smooth',
                block: 'nearest'
            });
        }
    }
</script>
@endsection