<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\TransacaoController;
use App\Http\Controllers\PDFController;

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

//Rota inicial  direciona para view index
Route::get('/', function () {
    return view('index');
});
//Rota para filtrar transacao por data
Route::get('transacao/showByDate',[TransacaoController::class, 'showByDate']);
//Resources
Route::resource('cliente', ClienteController::class);
Route::resource('transacao', TransacaoController::class)->only(['index', 'store']);

//Rota para criar pdf
Route::post('generate-pdf', [PDFController::class, 'generatePDF']);

