<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerHomeController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function() {
    if (auth()->check()) {
        $user = User::find(auth()->user()->id);
        if ($user->hasRole('Customer')) {
            return redirect(route('home'));
        } else {
            return redirect(route('dashboard'));
        }
    }
    return redirect(route('dashboard'));
})->name('welcome');

Route::middleware(['auth'])->group(function() {
    // DASHBOARD
    Route::view('/dashboard', 'dashboard')->name('dashboard')->middleware(['role:Admin|Company|Employee']);

    // COMPANY
    Route::group(['prefix' => 'company', 'middleware' => 'permission:manage-company'], function() {
        Route::get('/', [CompanyController::class, 'index'])->name('company');
        Route::post('/', [CompanyController::class, 'store'])->name('company.store');
        Route::get('/datatable', [CompanyController::class, 'dataTable'])->name('company.dataTable');
        Route::get('/count', [CompanyController::class, 'count'])->name('company.count')->withoutMiddleware('permission:manage-company');
        Route::get('/{id}', [CompanyController::class, 'getById'])->name('company.getById');
        Route::post('update/{id}', [CompanyController::class, 'updateProfile'])->name('company.updateProfile');
        Route::delete('{id}/delete', [CompanyController::class, 'destroy'])->name('company.delete');
    });

    // EMPLOYEE
    Route::group(['prefix' => 'employee', 'middleware' => 'permission:manage-employee'], function() {
        Route::get('/', [EmployeeController::class, 'index'])->name('employee');
        Route::post('/', [EmployeeController::class, 'store'])->name('employee.store');
        Route::get('/datatable', [EmployeeController::class, 'dataTable'])->name('employee.dataTable');
        Route::get('/count', [EmployeeController::class, 'count'])->name('employee.count')->withoutMiddleware('permission:manage-employee');
        Route::get('/{id}', [EmployeeController::class, 'getById'])->name('employee.getById');
        Route::post('update/{id}', [EmployeeController::class, 'updateProfile'])->name('employee.updateProfile');
        Route::delete('{id}/delete', [EmployeeController::class, 'destroy'])->name('employee.delete');
    });

    // CATEGORY
    Route::group(['prefix' => 'category', 'middleware' => 'permission:manage-category'], function() {
        Route::get('/', [CategoryController::class, 'index'])->name('category');
        Route::post('/', [CategoryController::class, 'store'])->name('category.store');
        Route::get('/datatable', [CategoryController::class, 'dataTable'])->name('category.dataTable');
        Route::get('/json', [CategoryController::class, 'dataJson'])->name('category.dataJson');
        Route::get('/count', [CategoryController::class, 'count'])->name('category.count')->withoutMiddleware('permission:manage-category');
        Route::get('/{id}', [CategoryController::class, 'getById'])->name('category.getById');
        Route::post('update/{id}', [CategoryController::class, 'updateCategory'])->name('category.updateCategory');
        Route::delete('{id}/delete', [CategoryController::class, 'destroy'])->name('category.delete');
    });

    // PRODUCT
    Route::group(['prefix' => 'product', 'middleware' => 'permission:manage-product'], function() {
        Route::get('/', [ProductController::class, 'index'])->name('product');
        Route::post('/', [ProductController::class, 'store'])->name('product.store');
        Route::get('/datatable', [ProductController::class, 'dataTable'])->name('product.dataTable');
        Route::get('/count', [ProductController::class, 'count'])->name('product.count')->withoutMiddleware('permission:manage-product');
        Route::get('/{id}', [ProductController::class, 'getById'])->name('product.getById');
        Route::post('update/{id}', [ProductController::class, 'updateProduct'])->name('product.updateProduct');
        Route::delete('{id}/delete', [ProductController::class, 'destroy'])->name('product.delete');
    });

    // TRANSACTION
    Route::group(['prefix' => 'transaction', 'middleware' => 'permission:manage-transaction'], function() {
        Route::get('/', [TransactionController::class, 'index'])->name('transaction');
        Route::post('/', [TransactionController::class, 'store'])->name('transaction.store');
        Route::get('/datatable', [TransactionController::class, 'dataTable'])->name('transaction.dataTable');
        Route::get('/count', [TransactionController::class, 'count'])->name('transaction.count')->withoutMiddleware('permission:manage-transaction');
        Route::get('/{id}', [TransactionController::class, 'getById'])->name('transaction.getById');
        Route::post('update/{id}', [TransactionController::class, 'updateTransaction'])->name('transaction.updateTransaction');
        Route::delete('{id}/delete', [TransactionController::class, 'destroy'])->name('transaction.delete');
    });

    // CUSTOMER
    Route::group(['prefix' => 'customer', 'middleware' => 'permission:manage-customer'], function() {
        Route::get('/', [CustomerController::class, 'index'])->name('customer');
        Route::post('/', [CustomerController::class, 'store'])->name('customer.store');
        Route::get('/datatable', [CustomerController::class, 'dataTable'])->name('customer.dataTable');
        Route::get('/count', [CustomerController::class, 'count'])->name('customer.count')->withoutMiddleware('permission:manage-customer');
        Route::get('/{id}', [CustomerController::class, 'getById'])->name('customer.getById');
        Route::post('update/{id}', [CustomerController::class, 'updateCustomer'])->name('customer.updateCustomer');
        Route::delete('{id}/delete', [CustomerController::class, 'destroy'])->name('customer.delete');
    });

    // CUSTOMER HOME
    Route::group(['prefix' => 'home', 'middleware' => 'role:Customer'], function() {
        Route::get('/', [CustomerHomeController::class, 'index'])->name('home');
        Route::post('/', [CustomerHomeController::class, 'store'])->name('home.store');
        Route::view('/set-profile', 'auth.set-profile')->name('profile.set');
        Route::post('/set-profile', [CustomerHomeController::class, 'setProfile'])->name('set.profile');
        Route::get('/company/{id}', [CustomerHomeController::class, 'companyById'])->name('home.companyById');

        Route::get('/datatable', [CustomerHomeController::class, 'dataTable'])->name('home.dataTable');
        Route::get('/count', [CustomerHomeController::class, 'count'])->name('home.count');
        Route::get('/{id}', [CustomerHomeController::class, 'getById'])->name('home.getById');
        Route::post('update/{id}', [CustomerHomeController::class, 'updateCustomer'])->name('home.updateCustomer');
        Route::delete('{id}/delete', [CustomerHomeController::class, 'destroy'])->name('home.delete');
        Route::get('/product/{id}/buy', [CustomerHomeController::class, 'buyProduk'])->name('home.buyProduk');
    });
});

require __DIR__.'/auth.php';
