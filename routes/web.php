<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\generatePDFController;
use App\Http\Controllers\TestController;
use App\Notifications\UserApprovedNotification;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/login', function () {
    return view('welcome');
})->name('login');


Route::get('/regiser', function () {
    return view('filament.register');
})->name('panel.register');

Route::get('/registerRequest', function () {
    $user = User::find(10);
    // $approved = $this->data['approved'];

    if ($user->approved) {
        // dd($user);

         $user->notify(new UserApprovedNotification);
        //   return $re
    }
})->name('registerRequest');


Route::prefix('generate-pdf')->name('generate-pdf.')
    ->group(function () {
            Route::get('/{record}', [generatePDFController::class,'orderReport'])->name('order.report'); // order Reports
            Route::get('correspondence/{record}', [generatePDFController::class,'correspondenceReport'])->name('correspondence.report'); // order Reports

    });
Route::get('new-pdf/{record}',[generatePDFController::class,'testPdf'])->name('new.pdf');
Route::get('test', [TestController::class, 'test']);
