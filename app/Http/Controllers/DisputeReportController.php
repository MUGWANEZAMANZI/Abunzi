<?php

namespace App\Http\Controllers;

use App\Models\Dispute;
use Barryvdh\DomPDF\Facade\Pdf;

class DisputeReportController extends Controller
{
    public function download(Dispute $dispute)
    {
        $dispute->load([
            'citizen',
            'assignments.justice',
            'report',
        ]);

        $report = $dispute->report;

        if (!$report) {
            abort(404, 'Report not found for this dispute.');
        }

        $pdf = Pdf::loadView('pdf.dispute-report', compact('dispute', 'report'));

        return $pdf->download('Dispute_Report_' . $dispute->id . '.pdf');
    }
}
