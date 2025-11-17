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
use App\Http\Controllers\CarController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\IndividualContainerRentalController;

Route::auth();

// Language switching route
Route::get('/locale/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }
    return redirect()->back();
})->name('locale.set');

Route::middleware('auth')->group(function () {
    // Home/Dashboard
    Route::get('/', [HomeController::class, 'index'])->middleware('permission:home.dashboard');
    
    // Containers routes
    Route::resource('containers', ContainerController::class)->middleware([
        'index' => 'permission:container.list',
        'create' => 'permission:container.create',
        'store' => 'permission:container.create',
        'show' => 'permission:container.show',
        'edit' => 'permission:container.edit',
        'update' => 'permission:container.edit',
        'destroy' => 'permission:container.delete',
    ]);
    Route::get('containers/create/bulk', [ContainerController::class, 'createBulk'])->name('containers.create-bulk')->middleware('permission:container.bulk-create');
    Route::post('containers/bulk', [ContainerController::class, 'storeBulk'])->name('containers.store-bulk')->middleware('permission:container.bulk-store');
    
    // Customers routes
    Route::resource('customers', App\Http\Controllers\CustomerController::class)->middleware([
        'index' => 'permission:customer.list',
        'create' => 'permission:customer.create',
        'store' => 'permission:customer.create',
        'show' => 'permission:customer.show',
        'edit' => 'permission:customer.edit',
        'update' => 'permission:customer.edit',
        'destroy' => 'permission:customer.delete',
    ]);
    Route::get('customers/data', [App\Http\Controllers\CustomerController::class, 'getData'])->name('customers.data')->middleware('permission:customer.data');
    Route::get('customers-search', [App\Http\Controllers\CustomerController::class, 'search'])->name('customers.search')->middleware('permission:customer.search');
    
    // Contracts routes
    Route::resource('contracts', ContractController::class)->middleware([
        'index' => 'permission:contract.list',
        'create' => 'permission:contract.create',
        'store' => 'permission:contract.create',
        'show' => 'permission:contract.show',
        'edit' => 'permission:contract.edit',
        'update' => 'permission:contract.edit',
        'destroy' => 'permission:contract.delete',
    ]);
    Route::get('contracts/type/{type}', [ContractController::class, 'index'])->name('contracts.index.by-type')->middleware('permission:contract.list');
    Route::get('contracts/create/type/{type}', [ContractController::class, 'create'])->name('contracts.create.by-type')->middleware('permission:contract.create');
    Route::get('contracts/quick/individual', [ContractController::class, 'quickIndividual'])->name('contracts.quick.individual')->middleware('permission:contract.create');
    Route::post('contracts/quick/individual', [ContractController::class, 'quickIndividualStore'])->name('contracts.quick.individual.store')->middleware('permission:contract.create');
    Route::get('contracts/{contract}/customer-data', [ContractController::class, 'getCustomerData'])->name('contracts.customer-data')->middleware('permission:contract.show');
    Route::post('contracts/convert-from-offer', [ContractController::class, 'convertFromOffer'])->name('contracts.convert-from-offer')->middleware('permission:contract.create');
    Route::get('contracts/{contract}/print', [ContractController::class, 'print'])->name('contracts.print')->middleware('permission:contract.print');
    Route::patch('contracts/{contract}/deactivate', [ContractController::class, 'deactivate'])->name('contracts.deactivate')->middleware('permission:contract.edit');
    
    // Payments routes
    Route::resource('payments', PaymentController::class)->middleware([
        'index' => 'permission:payment.list',
        'create' => 'permission:payment.create',
        'store' => 'permission:payment.create',
        'show' => 'permission:payment.show',
        'edit' => 'permission:payment.edit',
        'update' => 'permission:payment.edit',
        'destroy' => 'permission:payment.delete',
    ]);
    Route::get('contracts/{contract}/payments/create', [PaymentController::class, 'createForContract'])->name('payments.create-for-contract')->middleware('permission:payment.create');
    
    // Contract Container Fills routes
    Route::resource('contract-container-fills', ContractContainerFillController::class)->middleware([
        'index' => 'permission:contract-container-fill.list',
        'create' => 'permission:contract-container-fill.create',
        'store' => 'permission:contract-container-fill.create',
        'show' => 'permission:contract-container-fill.show',
        'edit' => 'permission:contract-container-fill.edit',
        'update' => 'permission:contract-container-fill.edit',
        'destroy' => 'permission:contract-container-fill.delete',
    ]);
    Route::get('contract-container/fills', [ContractContainerFillController::class, 'filled'])->name('contract-container-filled')->middleware('permission:contract-container-fill.filled');
    Route::get('contracts/{contract}/container-fills/create', [ContractContainerFillController::class, 'createForContract'])->name('contract-container-fills.create-for-contract')->middleware('permission:contract-container-fill.create');
    Route::post('contract-container-fills/{contractContainerFill}/discharge', [ContractContainerFillController::class, 'discharge'])->name('contract-container-fills.discharge')->middleware('permission:contract-container-fill.discharge');

    // Type management routes
    Route::get('types', [TypeController::class, 'index'])->name('types.index')->middleware('permission:type.list');
    Route::post('types', [TypeController::class, 'store'])->name('types.store')->middleware('permission:type.create');
    Route::put('types/{type}', [TypeController::class, 'update'])->name('types.update')->middleware('permission:type.edit');
    Route::delete('types/{type}', [TypeController::class, 'destroy'])->name('types.destroy')->middleware('permission:type.delete');
    
    // Reports routes
    Route::get('reports/container-status', [App\Http\Controllers\ReportsController::class, 'containerStatus'])->name('reports.container-status')->middleware('permission:report.list');
    Route::get('reports/daily-report', [App\Http\Controllers\ReportsController::class, 'dailyReport'])->name('reports.daily-report')->middleware('permission:report.list');
    Route::get('reports/monthly-report', [App\Http\Controllers\ReportsController::class, 'monthlyReport'])->name('reports.monthly-report')->middleware('permission:report.list');
    Route::get('reports/receipts-report', [App\Http\Controllers\ReportsController::class, 'receiptsReport'])->name('reports.receipts-report')->middleware('permission:report.list');
    Route::get('reports/container-status/print', [App\Http\Controllers\ReportsController::class, 'printContainerStatus'])->name('reports.container-status.print')->middleware('permission:report.print');
    Route::get('reports/daily-report/print', [App\Http\Controllers\ReportsController::class, 'printDailyReport'])->name('reports.daily-report.print')->middleware('permission:report.print');

    // Receipts routes
    Route::resource('receipts', App\Http\Controllers\ReceiptController::class)->middleware([
        'index' => 'permission:receipt.list',
        'create' => 'permission:receipt.create',
        'store' => 'permission:receipt.create',
        'show' => 'permission:receipt.show',
        'edit' => 'permission:receipt.edit',
        'update' => 'permission:receipt.edit',
        'destroy' => 'permission:receipt.delete',
    ]);
    Route::post('receipts/{receipt}/collect', [App\Http\Controllers\ReceiptController::class, 'collect'])->name('receipts.collect')->middleware('permission:receipt.collect');
    Route::get('receipts/{receipt}/print', [App\Http\Controllers\ReceiptController::class, 'print'])->name('receipts.print')->middleware('permission:receipt.print');
    Route::get('contracts/{contract}/receipts/create-from-fills', [App\Http\Controllers\ReceiptController::class, 'createFromContractFills'])->name('receipts.create-from-fills')->middleware('permission:receipt.create');
    Route::post('contracts/{contract}/receipts/create-from-fills', [App\Http\Controllers\ReceiptController::class, 'storeFromContractFills'])->name('receipts.store-from-fills')->middleware('permission:receipt.create');

    // Filled containers management routes
    Route::get('filled-containers', [FilledContainerController::class, 'index'])->name('filled-containers.index')->middleware('permission:filled-container.list');
    Route::post('filled-containers/{contractContainer}/mark-filled', [FilledContainerController::class, 'markAsFilled'])->name('filled-containers.mark-filled')->middleware('permission:filled-container.mark-filled');
    Route::post('filled-containers/{contractContainer}/discharge', [FilledContainerController::class, 'discharge'])->name('filled-containers.discharge')->middleware('permission:filled-container.discharge');
    Route::post('filled-containers/{contractContainer}/assign', [FilledContainerController::class, 'assignContainer'])->name('filled-containers.assign')->middleware('permission:filled-container.assign');

    // Offers CRUD + helpers
    Route::resource('offers', App\Http\Controllers\OfferCrudController::class)->middleware([
        'index' => 'permission:offer.list',
        'create' => 'permission:offer.create',
        'store' => 'permission:offer.create',
        'show' => 'permission:offer.show',
        'edit' => 'permission:offer.edit',
        'update' => 'permission:offer.edit',
        'destroy' => 'permission:offer.delete',
    ]);
    Route::get('offers/{offer}/print', [App\Http\Controllers\OfferCrudController::class, 'print'])->name('offers.print')->middleware('permission:offer.show');
    Route::patch('offers/{offer}/deactivate', [App\Http\Controllers\OfferCrudController::class, 'deactivate'])->name('offers.deactivate')->middleware('permission:offer.edit');
    Route::get('offers-search', [OfferController::class, 'search'])->name('offers.search')->middleware('permission:offer.search');
    Route::get('offers/{offer}/data', [OfferController::class, 'data'])->name('offers.data')->middleware('permission:offer.show');

    // Cars CRUD
    Route::resource('cars', CarController::class)->middleware([
        'index' => 'permission:car.list',
        'create' => 'permission:car.create',
        'store' => 'permission:car.create',
        'show' => 'permission:car.show',
        'edit' => 'permission:car.edit',
        'update' => 'permission:car.edit',
        'destroy' => 'permission:car.delete',
    ]);
    
    // Employees CRUD
    Route::resource('employees', EmployeeController::class)->middleware([
        'index' => 'permission:employee.list',
        'create' => 'permission:employee.create',
        'store' => 'permission:employee.create',
        'show' => 'permission:employee.show',
        'edit' => 'permission:employee.edit',
        'update' => 'permission:employee.edit',
        'destroy' => 'permission:employee.delete',
    ]);

    // Users CRUD
    Route::resource('users', UserController::class)->middleware([
        'index' => 'permission:user.list',
        'create' => 'permission:user.create',
        'store' => 'permission:user.create',
        'show' => 'permission:user.show',
        'edit' => 'permission:user.edit',
        'update' => 'permission:user.edit',
        'destroy' => 'permission:user.delete',
    ]);
    Route::get('users-search', [UserController::class, 'search'])->name('users.search')->middleware('permission:user.search');

    // Roles CRUD
    Route::resource('roles', RoleController::class)->middleware([
        'index' => 'permission:role.list',
        'create' => 'permission:role.create',
        'store' => 'permission:role.create',
        'show' => 'permission:role.show',
        'edit' => 'permission:role.edit',
        'update' => 'permission:role.edit',
        'destroy' => 'permission:role.delete',
    ]);
});
