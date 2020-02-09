<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// アップロード（単体）
$router->post('/upload', 'FileController@store');

// アップロード（複数）
$router->post('/upload/list', 'FileListController@store');

// ダウンロード（単体）
$router->get('/download', 'FileController@index');

// ファイルリスト取得
$router->get('/list', 'FileListController@index');

// ファイル削除（単体）
$router->delete('/delete', 'FileController@destroy');

// ファイル削除（複数）
$router->delete('/delete/list', 'FileListController@destroy');
