<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ContractContainerFillController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\ContainerController;

use App\Http\Controllers\FilledContainerController;
use App\Http\Controllers\OfferController;

Route::auth();
Route::get('/', [HomeController::class, 'index']);

// Language switching route
Route::get('/locale/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }
    return redirect()->back();
})->name('locale.set');

Route::middleware('auth')->group(function () {
    Route::resource('containers', ContainerController::class);
    Route::get('containers/create/bulk', [ContainerController::class, 'createBulk'])->name('containers.create-bulk');
    Route::post('containers/bulk', [ContainerController::class, 'storeBulk'])->name('containers.store-bulk');
    
    // Customers routes
    Route::resource('customers', App\Http\Controllers\CustomerController::class);
    Route::get('customers/data', [App\Http\Controllers\CustomerController::class, 'getData'])->name('customers.data');
    
    // Contracts routes with type segmentation
    Route::get('contracts/type/{type}', [ContractController::class, 'index'])->name('contracts.index.by-type');
    Route::get('contracts/create/type/{type}', [ContractController::class, 'create'])->name('contracts.create.by-type');
    Route::resource('contracts', ContractController::class);
    Route::get('contracts/{contract}/customer-data', [ContractController::class, 'getCustomerData'])->name('contracts.customer-data');
    Route::post('contracts/convert-from-offer', [ContractController::class, 'convertFromOffer'])->name('contracts.convert-from-offer');
    Route::get('contracts/{contract}/print', [ContractController::class, 'print'])->name('contracts.print');
    
    // Payments routes
    Route::resource('payments', PaymentController::class);
    Route::get('contracts/{contract}/payments/create', [PaymentController::class, 'createForContract'])->name('payments.create-for-contract');
    
    // Contract Container Fills routes
    Route::resource('contract-container-fills', ContractContainerFillController::class);
    Route::get('contracts/{contract}/container-fills/create', [ContractContainerFillController::class, 'createForContract'])->name('contract-container-fills.create-for-contract');
    Route::post('contract-container-fills/{contractContainerFill}/mark-filled', [ContractContainerFillController::class, 'markAsFilled'])->name('contract-container-fills.mark-filled');
    Route::post('contract-container-fills/{contractContainerFill}/discharge', [ContractContainerFillController::class, 'discharge'])->name('contract-container-fills.discharge');
    Route::post('contract-container-fills/{contractContainerFill}/replace', [ContractContainerFillController::class, 'replaceContainer'])->name('contract-container-fills.replace');

    // Type management routes
    Route::get('types', [TypeController::class, 'index'])->name('types.index');
    Route::post('types', [TypeController::class, 'store'])->name('types.store');
    Route::put('types/{type}', [TypeController::class, 'update'])->name('types.update');
    Route::delete('types/{type}', [TypeController::class, 'destroy'])->name('types.destroy');
    
    // Reports routes
    Route::get('reports/container-status', [App\Http\Controllers\ReportsController::class, 'containerStatus'])->name('reports.container-status');
    Route::get('reports/daily-report', [App\Http\Controllers\ReportsController::class, 'dailyReport'])->name('reports.daily-report');
    Route::get('reports/monthly-report', [App\Http\Controllers\ReportsController::class, 'monthlyReport'])->name('reports.monthly-report');
    Route::get('reports/receipts-report', [App\Http\Controllers\ReportsController::class, 'receiptsReport'])->name('reports.receipts-report');
    Route::get('reports/container-status/print', [App\Http\Controllers\ReportsController::class, 'printContainerStatus'])->name('reports.container-status.print');
    Route::get('reports/daily-report/print', [App\Http\Controllers\ReportsController::class, 'printDailyReport'])->name('reports.daily-report.print');

    // Bookings routes
    Route::resource('bookings', App\Http\Controllers\BookingController::class);
    Route::post('bookings/{booking}/confirm', [App\Http\Controllers\BookingController::class, 'confirm'])->name('bookings.confirm');
    Route::post('bookings/{booking}/deliver', [App\Http\Controllers\BookingController::class, 'deliver'])->name('bookings.deliver');
    Route::post('bookings/{booking}/cancel', [App\Http\Controllers\BookingController::class, 'cancel'])->name('bookings.cancel');

    // Receipts routes
    Route::resource('receipts', App\Http\Controllers\ReceiptController::class);
    Route::post('receipts/{receipt}/collect', [App\Http\Controllers\ReceiptController::class, 'collect'])->name('receipts.collect');
    Route::get('receipts/{receipt}/print', [App\Http\Controllers\ReceiptController::class, 'print'])->name('receipts.print');
    Route::get('contracts/{contract}/receipts/create-from-fills', [App\Http\Controllers\ReceiptController::class, 'createFromContractFills'])->name('receipts.create-from-fills');
    Route::post('contracts/{contract}/receipts/create-from-fills', [App\Http\Controllers\ReceiptController::class, 'storeFromContractFills'])->name('receipts.store-from-fills');

    // Filled containers management routes
    Route::get('filled-containers', [FilledContainerController::class, 'index'])->name('filled-containers.index');
    Route::post('filled-containers/{contractContainer}/mark-filled', [FilledContainerController::class, 'markAsFilled'])->name('filled-containers.mark-filled');
    Route::post('filled-containers/{contractContainer}/discharge', [FilledContainerController::class, 'discharge'])->name('filled-containers.discharge');
    Route::post('filled-containers/{contractContainer}/assign', [FilledContainerController::class, 'assignContainer'])->name('filled-containers.assign');

    // Offers CRUD + helpers
    Route::resource('offers', App\Http\Controllers\OfferCrudController::class);
    Route::get('offers-search', [OfferController::class, 'search'])->name('offers.search');
    Route::get('offers/{offer}/data', [OfferController::class, 'data'])->name('offers.data');
});
