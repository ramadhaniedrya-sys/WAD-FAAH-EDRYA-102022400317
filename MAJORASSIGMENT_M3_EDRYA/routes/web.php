
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SupplierController;

Route::get('/', function () {
    return redirect()->route('suppliers.index');
});

Route::resource('suppliers', SupplierController::class);
