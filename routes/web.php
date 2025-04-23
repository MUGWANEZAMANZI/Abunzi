<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Livewire\Dashboards\Citizen;
use App\Livewire\Dashboards\Justice;
use App\Livewire\Dashboards\Chief;

Route::get('/', fn () => view('welcome'));

// Authenticated routes
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    
    //  Main redirect based on role
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /**
     * Citizen Routes
     */
    Route::prefix('citizen')->middleware('role:citizen')->group(function () {
        Route::get('/dashboard', Citizen::class)->name('citizen.dashboard');
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
            return app(Justice::class);
        })->name('justice.dashboard');

       // Route::get('/assigned-disputes', fn () => view('justice.assigned'))->name('justice.assigned');
    });

    /**
     * Chief Justice Routes
     */
    Route::prefix('chief')->middleware('role:chief')->group(function () {
        Route::get('/dashboard', Chief::class)->name('chief.dashboard');
        //Route::get('/results', fn () => view('chief.results'))->name('chief.results');
    });

});

