<?php

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

Route::get('/', function () {
    return view('welcome');
});

// Chapter Index
Route::get('index', 'ElasticsearchController@index');
Route::get('get-index', 'ElasticsearchController@getIndex');
Route::get('delete-index', 'ElasticsearchController@deleteIndex');
Route::get('update-index', 'ElasticsearchController@updateMappings');

// Chapter Documents
Route::get('document', 'ElasticsearchController@saveSingleDocument');
Route::get('bulk-documents', 'ElasticsearchController@saveBulkDocuments');
Route::get('get-document', 'ElasticsearchController@getDocument');
Route::get('update-document', 'ElasticsearchController@updateDocument');
Route::get('delete-document', 'ElasticsearchController@deleteDocument');

// Chapter Search
Route::get('find/{terms}', 'ElasticsearchController@matchQuery');
Route::get('find/{terms}/result', 'ElasticsearchController@matchQueryOnlyResults');
Route::get('find-bool/{terms}', 'ElasticsearchController@boolQuery');
