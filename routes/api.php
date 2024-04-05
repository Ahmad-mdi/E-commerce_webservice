<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaymentController;
use App\Http\Middleware\CheckPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
Route::post('logout',[AuthController::class,'logout'])->middleware('auth:sanctum');
Route::post('payment/store',[PaymentController::class,'store'])->middleware('auth:sanctum');
Route::post('payment/verify',[PaymentController::class,'verify']);
//AdminPanel:
Route::prefix('admin')->middleware(/* CheckPermission::class . ':view-dashboard', */'auth:sanctum')->group(function(){
    Route::apiResource('brands',BrandController::class);
    Route::get('/brands/{brand}/products',[BrandController::class,'getProducts']);
    Route::apiResource('category',CategoryController::class);
    Route::get('/category/{category}/products',[CategoryController::class,'getProducts']);
    Route::get('/category/{category}/parent',[CategoryController::class,'parent']);
    Route::get('/category/{category}/children',[CategoryController::class,'children']);
    Route::apiResource('product',ProductController::class)->middleware('auth:sanctum'/* ,CheckPermission::class .':create-product' */);
    Route::apiResource('product.gallery', GalleryController::class);
    Route::apiResource('role', RoleController::class);
});


