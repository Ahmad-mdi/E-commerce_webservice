<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('/brand',BrandController::class);
Route::get('/brand/{brand}/products',[BrandController::class,'showProducts']);
Route::apiResource('/category',CategoryController::class);
Route::get('/category/{category}/parent',[CategoryController::class,'parent']);
Route::get('/category/{category}/children',[CategoryController::class,'children']);
Route::get('/category/{category}/products',[CategoryController::class,'showProducts']);
Route::apiResource('/product',ProductController::class);

