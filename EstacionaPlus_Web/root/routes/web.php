<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VagaController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EstacionamentoController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Session;

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
Route::get('/', [EstacionamentoController::class, 'show'])->name('adminLogin');

//Processo de Login do Admin
Route::view('/adminLogin','adminLogin')->name('adminLogin');
Route::post('/adminLogin',[AdminController::class, 'adminLogin'])->name('admin.adminLogin');

Route::group(['middleware' => 'checkAdminSession'], function () {//Session de Admin
    Route::get('/admin/estacionamentos', [EstacionamentoController::class, 'listar'])->name('estacionamentos.listar');
    Route::post('/admin/estacionamentos/inserir', [EstacionamentoController::class, 'inserir'])->name('estacionamento.inserir');
    Route::get('/admin/estacionamentos/form/{id?}', [EstacionamentoController::class, 'estacionamentos_form'])->name('estacionamento.form');
    Route::put('/admin/estacionamentos/alterar/{id}', [EstacionamentoController::class, 'alterar'])->name('estacionamento.alterar');
    Route::get('/admin/estacionamentos/excluir/{id}', [EstacionamentoController::class, 'excluir'])->name('estacionamento.excluir');
    Route::get('/admin/estacionamentos/detalhes/{id}', [EstacionamentoController::class, 'listar_um'])->name('estacionamento.detalhes');
});



