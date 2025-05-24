<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\adminIncidentsController;
use App\Http\Controllers\RequestController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route::group(['middleware' => 'role:' . Config::get('constants.roles.super-admin')], function () {
    Route::get('category/ajax', [CategoryController::class, 'categoryAjax'])->name('category.ajax');
    Route::get('user/ajax', [UserController::class, 'userAjax'])->name('user.ajax');
    Route::resource('categories', CategoryController::class);
    Route::resource('user', UserController::class);
    Route::get('request/ajax', [RequestController::class, 'requestAjax'])->name('request.ajax');
    Route::resource('requests', RequestController::class);

// });

// Route::group(['middleware' => 'role:' . Config::get('constants.roles.user')], function () {
    Route::resource('incidents', IncidentController::class);
    Route::get('incident/ajax', [IncidentController::class, 'incidentAjax'])->name('incident.ajax');
// });

// Route::group(['middleware' => 'role:' . Config::get('constants.roles.admin')], function () {
// });
// 
Route::resource('adminIncidents', adminIncidentsController::class)
     ->parameters(['adminIncidents' => 'incident']);
Route::post('admin/incidents/bulk-resolve', [adminIncidentsController::class, 'bulkResolve'])->name('admin.incidents.bulk-resolve');
Route::get('/admin/incidents/export', [adminIncidentsController::class, 'export'])->name('adminIncidents.export');
Route::get('/notifications/fetch', [adminIncidentsController::class, 'fetch'])->name('notifications.fetch');


Route::post('/notifications/mark-all-read', function() {
    auth()->user()->unreadNotifications->markAsRead();
    return back();
})->name('user.notifications.markAllRead')->middleware('auth');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
