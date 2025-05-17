<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('report.title') }} - {{ $dispute->title }}</title>
    <style>
        @page { margin: 30px 40px; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 13px;
            color: #333;
            line-height: 1.6;
            background: #f8f9fa;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            background: linear-gradient(to right, #005baa, #00c6fb);
            color: white;
            padding: 20px 10px;
            border-radius: 8px;
        }
        h1, h2 {
            margin: 0;
            font-weight: bold;
        }
        h1 {
            font-size: 24px;
            text-transform: uppercase;
        }
        h2 {
            font-size: 18px;
            margin-top: 15px;
        }
        .label {
            font-weight: bold;
            color: #005baa;
        }
        .content {
            margin-left: 10px;
            margin-bottom: 12px;
            padding: 10px;
            background: white;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .section {
            margin-bottom: 14px;
        }
        .signature-line {
            margin-top: 50px;
            border-top: 1px solid #333;
            width: 70%;
            margin-left: auto;
            margin-right: auto;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 11px;
            color: #666;
        }
        .bg-highlight {
            background: #d4edda;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>{{ __('report.republic') }}</h1>
        <div><strong>{{ __('report.ministry') }}</strong></div>
    </div>

    <h2>{{ __('report.report_title') }}</h2>

    <div class="section"><span class="label">{{ __('report.issue_title') }}</span><div class="content">{{ $dispute->title }}</div></div>
    <div class="section"><span class="label">{{ __('report.raiser') }}</span><div class="content">{{ $dispute->citizen->name ?? 'N/A' }}</div></div>
    <div class="section"><span class="label">{{ __('report.accused') }}</span><div class="content">{{ $dispute->offender_name }}</div></div>
    <div class="section"><span class="label">{{ __('report.location') }}</span><div class="content">{{ $dispute->location_name }}</div></div>
    <div class="section"><span class="label">{{ __('report.meeting_datetime') }}</span><div class="content">{{ \Carbon\Carbon::parse($report->ended_at)->format('Y-m-d H:i') }}</div></div>
    <div class="section"><span class="label">{{ __('report.raiser_wish') }}</span><div class="content">{{ $report->victim_resolution }}</div></div>
    <div class="section"><span class="label">{{ __('report.accused_wish') }}</span><div class="content">{{ $report->offender_resolution }}</div></div>
    <div class="section"><span class="label">{{ __('report.witnesses') }}</span><div class="content">{{ $report->witnesses }}</div></div>
    <div class="section"><span class="label">{{ __('report.attendees') }}</span><div class="content">{{ $report->attendees }}</div></div>
    <div class="section"><span class="label">{{ __('report.final_resolution') }}</span><div class="content bg-highlight">{{ $report->justice_resolution }}</div></div>
    <div class="section"><span class="label">{{ __('report.report_date') }}</span><div class="content">{{ now()->format('Y-m-d H:i') }}</div></div>

    @php
        $justiceUsers = $dispute->assignments->pluck('justice')->filter();
        $leadJustice = $justiceUsers->first();
    @endphp

    @if($justiceUsers->count())
        <div class="section">
            <span class="label">{{ __('report.prepared_by') }}</span>
            <div class="content">
                @foreach($justiceUsers as $user)
                    <span class="font-bold mr-2">{{ $user->name }}</span>@if(!$loop->last), @endif
                @endforeach
            </div>
        </div>
    @endif

    <div class="mt-12" style="display: flex; justify-content: space-between;">
        <div style="width: 45%; text-align: center;">
            <p class="mb-10">{{ __('report.preparer') }}</p>
            <div class="signature-line"></div>
            @if($leadJustice)
                <p class="font-bold mt-2">{{ $leadJustice->name }}</p>
            @endif
        </div>
        <div style="width: 45%; text-align: center;">
            <p class="mb-10">{{ __('report.committee_lead') }}</p>
            <div class="signature-line"></div>
            @if($leadJustice)
                <p class="font-bold mt-2">{{ $leadJustice->name }}</p>
            @endif
        </div>
    </div>

    <div class="footer">
        © {{ now()->year }} - {{ __('report.disclaimer') }}
        <br><strong>{{ __('report.software_notice') }}</strong>
    </div>

</body>
</html>
