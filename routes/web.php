<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\BorrowerController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\ReportController;


/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'homeIndex'])->name('home');

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Dashboard Redirect
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {

    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $user = Auth::user();

    // prevent null-role crash
    if (!$user->role) {
        Auth::logout();
        return redirect()->route('login')
            ->withErrors(['email' => 'Account has no role assigned']);
    }

    return match ($user->role->role_name) {
        'admin'    => redirect()->route('admin.dashboard'),
        'staff'    => redirect()->route('staff.dashboard'),
        'borrower' => redirect()->route('borrower.dashboard'),
        default    => redirect()->route('login'),
    };
})->middleware('auth');

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [HomeController::class, 'adminDashboard'])
            ->name('dashboard');


        // Users
        Route::resource('users', UserController::class)->except('show', 'create', 'edit');

        // Roles
        Route::resource('roles', RoleController::class)->except('show', 'create', 'edit');

        // Tools
        Route::resource('tools', ToolController::class)->except('show', 'create', 'edit');

        // Categories
        Route::resource('categories', CategoryController::class)->except('show', 'create', 'edit');

        // Borrowings
        Route::get('/borrowings', [BorrowingController::class, 'index'])
            ->name('borrowings.index');

        // Borrowings -> borrow
        Route::resource('borrowings/borrow', BorrowController::class)->except('show', 'index', 'create', 'edit');

        // Borrowings -> return
        Route::resource('borrowings/return', ReturnController::class)->except('show', 'index');

        // Activity Logs
        Route::get('activity_logs', [ActivityLogController::class, 'index'])
            ->name('logs.index');
    });



/*
|--------------------------------------------------------------------------
| STAFF
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:staff'])
    ->prefix('staff')
    ->name('staff.')
    ->group(function () {

        Route::get('/dashboard', [HomeController::class, 'staffDashboard'])
            ->name('dashboard');

        Route::get('/borrowings', [BorrowController::class, 'index'])
            ->name('borrowings.index');

        Route::put(
            '/borrowings/{borrowing}/approve',
            [BorrowController::class, 'approve']
        )->name('borrowings.approve');

        Route::put(
            '/borrowings/{borrowing}/reject',
            [BorrowController::class, 'reject']
        )->name('borrowings.reject');

        Route::resource('/returns', ReturnController::class)->except('show', 'create', 'edit', 'store');

        Route::post('/returns/{borrowing}', [ReturnController::class, 'store'])
            ->name('returns.store');
        Route::prefix('reports')->name('reports.')->group(function () {

            Route::get('/borrowings', [ReportController::class, 'borrowings'])
                ->name('borrowings');

            Route::get('/returns', [ReportController::class, 'returns'])
                ->name('returns');

            Route::get('/all', [ReportController::class, 'all'])
                ->name('all');
        });
    });


// /*
// |--------------------------------------------------------------------------
// | BORROWER
// |--------------------------------------------------------------------------
// */
Route::middleware(['auth', 'role:borrower'])
    ->prefix('borrower')
    ->name('borrower.')
    ->group(function () {

        Route::get('/dashboard', fn() => view('borrower.dashboard'))
            ->name('dashboard');

        // Available tools
        Route::get('/tools', [BorrowerController::class, 'AVIndex'])
            ->name('tools.index');

        // Create borrowing (tool-based)
        Route::get('/borrowings/create/{tool}', [BorrowerController::class, 'create'])
            ->name('borrowings.create');

        Route::post('/borrowings/{tool}', [BorrowerController::class, 'store'])
            ->name('borrowings.store');

        // Return tool
        Route::post('/borrowings/{borrowing}/return', [BorrowerController::class, 'return'])
            ->name('borrowings.return');

        // Resource routes (ID MUST be numeric)
        Route::resource('borrowings', BorrowerController::class)
            ->except(['create', 'store', 'show'])
            ->where(['borrowing' => '[0-9]+']);
    });
