<?php

use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatsController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\UsersController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;


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
Route::get('/', [UsersController::class, 'home']);

Route::get('/about', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function(){
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginUser']);
    Route::get('/register', [AuthController::class, 'register']);
    Route::post('/register', [AuthController::class, 'registerNewUser']);
});

Route::middleware('auth', 'verified')->group(function(){
Route::get('/profile/{id?}', [UsersController::class, 'user_profile']);
Route::get('/status/{id?}', [UsersController::class, 'status']);
Route::post('/status/{id?}', [UsersController::class, 'statusUser']);
Route::get('/edit/{id?}', [UsersController::class, 'edit']);
Route::post('/editUser/{id?}', [UsersController::class, 'editUser']);
Route::get('/delete/{id?}', [UsersController::class, 'deleteUser']);
Route::post('/delete/{id?}', [UsersController::class, 'deleteUser']);
Route::get('/security/{id?}', [AuthController::class, 'security']);
Route::post('/security/{id?}', [AuthController::class, 'securityUser']);
Route::get('/media/{id?}', [UsersController::class, 'media']);
Route::post('/media/{id?}', [UsersController::class, 'mediaUser']);
Route::get('/confirm-password/{id?}/{comment?}', [AuthController::class, 'confirmPassword']);
Route::post('/confirm-password/{id?}/{comment?}', [AuthController::class, 'confirmPasswordUser']);
Route::get('/logout', [AuthController::class, 'logout']);
Route::get('/search', [UsersController::class, 'search']);
Route::get('/chats', [ChatsController::class, 'chats']);
Route::get('/posts', [PostsController::class, 'posts']);
Route::get('/post/{post_id?}', [PostsController::class, 'post']);
Route::post('/post/{post_id?}', [PostsController::class, 'post']);
Route::get('/addPost', [PostsController::class, 'addPost']);
Route::post('/addPost/{user_id?}', [PostsController::class, 'addNewPost']);
Route::get('/editPost/{post_id?}', [PostsController::class, 'editPost']);
Route::post('/editPost/{post_id?}', [PostsController::class, 'editPost']);
Route::post('/delete_image/{image_id?}/{post_id?}', [PostsController::class, 'delete_image']);
Route::get('/delete_image/{image_id?}/{post_id?}', [PostsController::class, 'delete_image']);
Route::get('/deletePost/{post_id?}', [PostsController::class, 'deletePost']);
Route::get('/searchPosts', [PostsController::class, 'searchPosts']);
Route::get('/favoritesPosts', [PostsController::class, 'favoritesPosts']);
Route::get('/myPosts', [PostsController::class, 'myPosts']);
Route::get('/addFavorites/{post_id?}', [PostsController::class, 'addFavorites']);
Route::get('/deleteFavorites/{post_id?}', [PostsController::class, 'deleteFavorites']);
Route::get('/deleteComment/{comment_id?}/{post_id?}', [PostsController::class, 'deleteComment']);
Route::get('/chatsFavorites', [ChatsController::class, 'chatsFavorites']);
Route::get('/addChat/{iser_id?}', [ChatsController::class, 'addChat']);
Route::post('/addChat/{author_user_id?}', [ChatsController::class, 'addChat']);
Route::get('/addChatShow', [ChatsController::class, 'addChatShow']);
Route::get('/openChat/{chat_id?}', [ChatsController::class, 'openChat']);
Route::get('/message/{chat_id?}', [ChatsController::class, 'message']);
Route::get('/delete_message/{message_id}/{user_id}/{chat_id}', [ChatsController::class, 'delete_message']);
Route::get('/editChatShow/{chat_id}', [ChatsController::class, 'editChatShow']);
Route::get('/deleteUsersIsChat/{user_id?}/{chat_id?}', [ChatsController::class, 'deleteUsersIsChat']);
Route::get('/deleteChat/{chat_id?}', [ChatsController::class, 'deleteChat']);
Route::post('/editChat/{chat_id}', [ChatsController::class, 'editChat']);
Route::get('/chatsFavorites', [ChatsController::class, 'chatsFavorites']);
Route::get('/chatsMy', [ChatsController::class, 'chatsMy']);
Route::get('/searchChats', [ChatsController::class, 'searchChats']);
Route::get('/onFavorites/{chat_id?}', [ChatsController::class, 'onFavorites']);
Route::get('/offFavorites/{chat_id}', [ChatsController::class, 'offFavorites']);
Route::get('/roleModerator/{user_id}/{chat_id}', [ChatsController::class, 'roleModerator']);
Route::get('/roleParticipant/{user_id}/{chat_id}', [ChatsController::class, 'roleParticipant']);
Route::get('/imagePostShow/{image_id?}', [PostsController::class, 'imagePostShow']);
Route::post('/addNewComment/{post_id?}', [PostsController::class, 'addNewComment']);
Route::post('/downloadImage/{post_id?}/{user_id}', [PostsController::class, 'downloadImage']);
Route::post('/changeAvatar/{post_id?}', [PostsController::class, 'changeAvatar']);
Route::post('/editInsertPost/{post_id?}', [PostsController::class, 'editInsertPost']);
});

Route::get('/email/verify', function () {
    return view('verify_email');
})->middleware('auth')->name('verification.notice');

//Auth::routes(['verify' => true]);
Route::middleware('admin')->group(function(){
Route::get('/create', [UsersController::class, 'create']);
Route::post('/create', [UsersController::class, 'createUser']);
Route::get('/statusAdmin/{id?}', [UsersController::class, 'statusShow']);
Route::post('/statusAdmin/{id?}', [UsersController::class, 'statusAdmin']);
Route::get('/bannedPost/{post_id?}', [PostsController::class, 'bannedPost']);
Route::get('/unBannedPost/{post_id?}', [PostsController::class, 'unBannedPost']);
Route::get('/bannedComment/{comment_id?}/{post_id?}', [PostsController::class, 'bannedComment']);
Route::get('/unBannedComment/{comment_id?}/{post_id?}', [PostsController::class, 'unBannedComment']);
Route::get('/deleteComment/{comment_id?}/{post_id?}', [PostsController::class, 'deleteComment']);
Route::get('/onBannedChat/{chat_id}', [ChatsController::class, 'onBannedChat']);
Route::get('/offBannedChat/{chat_id}', [ChatsController::class, 'offBannedChat']);
});

Route::get('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::fallback(function() {
    abort(404);
});