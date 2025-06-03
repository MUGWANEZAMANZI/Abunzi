<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\WelcomeController;

// Language switching route (placed above all other routes)
Route::get('/set-locale/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'rw'])) {
        App::setLocale($locale);
        session(['locale' => $locale]);

        // Attempt to modify .env (NOT RECOMMENDED FOR PRODUCTION)
        $path = base_path('.env');
        if (file_exists($path)) {
            $oldLocale = env('APP_LOCALE');
            file_put_contents($path, str_replace("APP_LOCALE=$oldLocale", "APP_LOCALE=$locale", file_get_contents($path)));

            // Clear config cache to try and reflect the change (might not be immediate)
            Artisan::call('config:clear');
        }
    }
    return redirect()->back();
})->name('set-locale');

Route::get('/', fn() => view('welcome'));

// Authenticated routes
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', 'locale'])->group(function () {

    //  Main redirect based on role
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    /**
     * Citizen Routes
     */
    Route::prefix('citizen')->middleware(['role:citizen'])->group(function () {
        Route::get('/dashboard', \App\Livewire\Dashboards\Citizen::class)->name('citizen.dashboard');
        //Route::get('/create-dispute', fn () => view('citizen.create'))->name('citizen.create');

    });

    /**
     *Justice Routes
     */
    Route::prefix('justice')->middleware(['role:justice'])->group(function () {
        Route::get('/dashboard', function () {
            if (!auth()->user()->currentTeam) {
                abort(403, 'You are not assigned to a team yet.');
            }

            return app()->call(\App\Livewire\Dashboards\Justice::class); // Livewire component render
        })->name('justice.dashboard');
    });

    /**
     * Chief Justice Routes
     */
    Route::prefix('chief')->middleware(['role:chief'])->group(function () {
        Route::get('/dashboard', \App\Livewire\Dashboards\Chief::class)->name('chief.dashboard');
        //Route::get('/results', fn () => view('chief.results'))->name('chief.results');
    });

});

Route::get('/dispute/{dispute}/report/download', [\App\Http\Controllers\DisputeReportController::class, 'download'])->name('dispute.report.download');


Route::get('/team/invitations/accept/{invitationId}', function ($invitationId) {
    $user = Auth::user();

    if (!$user) {
        return redirect('/login'); // Or handle unauthenticated access
    }

    app(\App\Actions\Jetstream\AcceptTeamInvitation::class)->accept($user, $invitationId);

    return redirect('/dashboard')->with('message', 'Invitation accepted!');
})->middleware('auth');

Route::get('/disputes/{dispute}/report/download', [\App\Http\Controllers\DisputeReportController::class, 'download'])
    ->name('disputes.report.download');