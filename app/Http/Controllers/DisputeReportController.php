<?php

namespace App\Http\Controllers;

use App\Models\Dispute;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class DisputeReportController extends Controller
{
   
    public function download(Dispute $dispute)
    {
        $dispute->load([
            'citizen',
            'assignment.justice',
            'report',
        ]);


        $id = $dispute->chief;
        Log::info('Chief id is:', [$id]);

        $chiefName = User::findOrFail($id)->name;
        Log::info('Chief name is:', [$chiefName]);


        $report = $dispute->report;

        if (!$report) {
            abort(404, 'Report not found for this dispute.');
        }

        $pdf = Pdf::loadView('pdf.dispute-report', compact('dispute', 'report', 'chiefName'))
            ->setPaper('A4', 'portrait')
            ->setOptions(['defaultFont' => 'sans-serif']);

        return $pdf->download('Dispute_Report_' . $dispute->id . '.pdf');
    }
}
