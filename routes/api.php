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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('test', function(){
    return response()->json();
});
Route::group(['prefix' => 'admin'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', 'AuthController@adminLogin')->name('adminLogin');
        Route::get('user', 'AuthController@currentAdmin')->middleware('auth:api')->name('currentAdmin');
    });
    Route::group(['prefix' => 'account'], function() {
        Route::post('changePassword/{id}', 'AuthController@changePassword')->name('changePassword');
    });
    Route::group(['prefix' => 'rentRequests'], function() {
        Route::post('/', 'RentRequestController@adminStore')->middleware('auth:api')->name('adminNewRentRequest');
        Route::get('/pending', 'RentRequestController@adminPendingRequest')->middleware('auth:api')->name('adminPendingRequest');
        Route::get('/confirmed', 'RentRequestController@adminConfirmedRequest')->middleware('auth:api')->name('adminConfirmedRequest');
        Route::get('/completed', 'RentRequestController@adminCompletedRequest')->middleware('auth:api')->name('adminCompletedRequest');
        Route::get('/overdue', 'RentRequestController@adminOverdueRequest')->middleware('auth:api')->name('adminOverdueRequest');
        Route::delete('/{id}', 'RentRequestController@removeRequest')->middleware('auth:api')->where('id', '[0-9]+')->name('removeRequest');
        Route::put('/confirm/{id}', 'RentRequestController@confirmRequest')->middleware('auth:api')->name('confirmRequest');
        Route::put('/complete/{id}', 'RentRequestController@completeRequest')->middleware('auth:api')->name('completeRequest');

        Route::get('/byDate', 'RentRequestController@getRequestByDate')->middleware('auth:api')->name('getRequestByDate');
    });
});

Route::group(['prefix' => 'user'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', 'AuthController@userLogin')->name('userLogin');
        Route::post('signUp', 'AuthController@userSignUp')->name('userSignUp');
        Route::post('getConfirmCode', "AuthController@getConfirmCode")->name('getConfirmCode');
        Route::get('user', 'AuthController@currentUser')->middleware('auth:api')->name('currentUser');
    });
    Route::group(['prefix' => 'rentRequests'], function () {
        Route::post('/', 'RentRequestController@userStore')->middleware('auth:api')->name('userNewRentRequest');
        Route::get('/current', 'RentRequestController@userCurrentRequest')->middleware('auth:api')->name('userCurrentRequest');
        Route::get('/allRequest', 'RentRequestController@userAllRequest')->middleware('auth:api')->name('userAllRequest');
    });
});

Route::group(['prefix' => 'books'], function () {
    Route::post('/', 'BookController@store')->middleware('auth:api')->name('addBook');
    Route::post('/uploadImage', 'BookController@uploadImage')->middleware('auth:api')->name('uploadBookImage');
    Route::get('/', 'BookController@index')->middleware('auth:api')->name('getAllBooks');
    Route::get('/{id}', 'BookController@view')->where('id', '[0-9]+')->name('getBookByID');
    Route::get('/available', 'BookController@available')->name('getAvailable');
    Route::get('/rented', 'BookController@rented')->name('getRented');
    Route::get('/deleted', 'BookController@deleted')->middleware('auth:api')->name('getDeleted');
    Route::get('/restoreBook/{id}', 'BookController@restoreBook')->middleware('auth:api')->name('restoreBook');
    Route::delete('/{id}', 'BookController@deleteBook')->where('id', '[0-9]+')->middleware('auth:api')->name('deleteBook');
    Route::post('/perminantDelete/{id}', 'BookController@perminantDelete')->middleware('auth:api')->name('perminantDelete');
    Route::get('/searchByTitle', 'BookController@searchByTitle')->name('searchByTitle');

    Route::put('/editTitle/{id}', 'BookController@editTitle')->middleware('auth:api')->name('editBookTitle');
    Route::put('/editAuthor/{id}', 'BookController@editAuthor')->middleware('auth:api')->name('editBookAuthor');
    Route::put('/editSummary/{id}', 'BookController@editSummary')->middleware('auth:api')->name('editBookSummary');
    Route::put('/editTrendiness/{id}', 'BookController@editTrendiness')->middleware('auth:api')->name('editTrendiness');
    Route::put('/editCondition/{id}', 'BookController@editCondition')->middleware('auth:api')->name('editCondition');
    Route::put('/editPrice/{id}', 'BookController@editPrice')->middleware('auth:api')->name('editPrice');

    Route::post('/addBookTags/{id}', 'BookController@addBookTags')->middleware('auth:api')->name('addBookTags');
    Route::delete('/deleteBookTags/{id}', 'BookController@deleteBookTags')->middleware('auth:api')->name('deleteBookTags');

    Route::get('/tags', 'BookController@getAllTags')->name('getAllTags');
    Route::post('/addTagsToBook/{book_id}/{tag_id}', 'BookController@addTagToBook')->middleware('auth:api')->name('addTagToBook');
    Route::post('/addTag', 'BookController@addTag')->name('addTag');

    Route::get('/trending', 'BookController@getTrendingBooks')->name('getTrendingBooks');
    Route::get('/newArrival', 'BookController@getNewArrivalBooks')->name('getNewArrivalBooks');
    Route::get('/fromAuthor', 'BookController@getFromAuthor')->name('getFromAuthor');
    Route::get('/byTag', 'BookController@getByTag')->name('getByTag');
});

Route::group((['prefix' => 'requests']), function () {
    Route::post('/', 'RequestController@store')->name('createRentRequest');
});