<?php

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

Route::get('/', function () {
    return view('task.taskapp_login');
});

//タスク一覧画面
Route::get('task/app', 'App\Http\Controllers\TaskController@taskapp_list');

//タスク追加画面
Route::get('task/add', 'App\Http\Controllers\TaskController@task_insert');
Route::post('task/add', 'App\Http\Controllers\TaskController@task_insert_registration');

//タスク詳細画面
Route::get('task/detail/{task_id?}', 'App\Http\Controllers\TaskController@task_detail');

//タスク修正画面
Route::get('task/fix/{task_id?}', 'App\Http\Controllers\TaskController@task_fix');
Route::post('task/fix/{task_id?}', 'App\Http\Controllers\TaskController@task_fix_registration');

//タスク削除
Route::get('task/delete/{task_id?}', 'App\Http\Controllers\TaskController@task_delete');

//ログイン画面
Route::get('task', 'App\Http\Controllers\TaskController@taskapp_login');
Route::post('task', 'App\Http\Controllers\TaskController@taskapp_login_registration');

//新規会員登録画面
Route::get('login/insert', 'App\Http\Controllers\TaskController@new_member');
Route::post('login/insert', 'App\Http\Controllers\TaskController@new_member_registration');

//csv出力
Route::get('task/csv', 'App\Http\Controllers\TaskController@task_csv');

//管理者ログイン
Route::get('login/admin', 'App\Http\Controllers\AppUserController@login_admin');
Route::post('login/admin', 'App\Http\Controllers\AppUserController@login_admin_registration');

//管理者権限ログイン
Route::get('login/admin/user/{user_id?}', 'App\Http\Controllers\AppUserController@user_login');

//パスワードを忘れた
Route::get('pass/forget', 'App\Http\Controllers\TaskController@pass_forget');

//パスワードメール送信
Route::post('pass/forget', 'App\Http\Controllers\TaskController@pass_update');

//パスワードメール送信
Route::get('pass_forget/{user_email?}', 'App\Http\Controllers\TaskController@pass_input');

//パスワードメール送信
Route::post('pass_forgeted/{user_email?}', 'App\Http\Controllers\TaskController@pass_updated');

//ユーザ一覧画面
Route::get('administrator', 'App\Http\Controllers\AppUserController@user_admin_list');

//ユーザ削除
Route::get('user/delete/{user_id?}', 'App\Http\Controllers\AppUserController@user_delete');

//ユーザ修正画面
Route::get('user/fix/{user_id?}', 'App\Http\Controllers\AppUserController@user_fix');
Route::post('user/fix/{user_id?}', 'App\Http\Controllers\AppUserController@user_fix_registration');

//タスク完了更新
Route::get('task/success/{task_id?}', 'App\Http\Controllers\TaskController@task_success_update');

//タスク完了取消
Route::get('task/successdenger/{task_id?}', 'App\Http\Controllers\TaskController@task_success_denger');

//インフォメーション追加画面
Route::get('information/add', 'App\Http\Controllers\InformationController@information_insert');
Route::post('information/add', 'App\Http\Controllers\InformationController@information_insert_registration');

//インフォメーション修正画面
Route::get('information/fix/{information_id?}', 'App\Http\Controllers\InformationController@information_fix');
Route::post('information/fix/{information_id?}', 'App\Http\Controllers\InformationController@information_fix_registration');

//インフォメーション削除
Route::get('information/delete/{information_id?}', 'App\Http\Controllers\InformationController@information_delete');

//インフォメーション詳細
Route::get('information/detail/{information_id?}', 'App\Http\Controllers\InformationController@information_detail');

//task絞り込み検索
Route::get('task/find', 'App\Http\Controllers\TaskController@task_find');
Route::post('task/find', 'App\Http\Controllers\TaskController@task_find');

