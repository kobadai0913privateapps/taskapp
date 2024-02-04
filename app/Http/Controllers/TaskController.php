<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Providers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Validator;
use Timestamp;
use Mail;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Models\Task;
use App\Models\User;
use App\Models\Information;

class TaskController extends Controller
{
    //タスク修正(get)
    public function task_fix(Request $request){

        try{
            //入力内容取得
            $task_id = $request->task_id;

            //タスクデータ取得
            $items = Task::select('task_id','task_name','task_detail',Task::raw('date_format(task_start_datetime,"%Y-%m-%dT%H:%i") as task_start_datetime'),Task::raw('date_format(task_end_datetime,"%Y-%m-%dT%H:%i") as task_end_datetime'),'completed')
                                ->where('task_id',$task_id)
                                ->get();

            //タスク修正画面に遷移
            return view('task.task_fix',['tasks'=>$items]);
        }catch(\Exception $e){

            $request->session()->flash('login_errors', 'セッションが切れました。もう一度ログインしてください。');
            //ログイン画面へ遷移
            return self::taskapp_login();
        }
    }

    //タスク修正(post)
    public function task_fix_registration(Request $request){
        try{
            //入力内容取得
            $task_id = $request->task_id;
            $task_name = $request->task_name;
            $task_detail = $request->task_detail;
            $task_start_datetime = $request->task_start_datetime;
            $task_end_datetime = $request->task_end_datetime;
            $task_start_datetime_status = $request->task_start_datetime_status;
    
            //作業用変数
            $work_id;
            $work_column;
            $work_max_id;
    
            //バリデーション情報取得
            $rules = Task::$rules;
            $messages = Task::$messages;
    
            //insertパラメータ取得
            $insert_param = Task::$task_param;

            //タスクパラメータ削除処理
            if($task_start_datetime_status=='true'){
                unset($rules['task_start_datetime']);
                unset($messages['task_start_datetime.required']);
            }

            //エラー処理
            $validator  = Validator::make($request->all(), $rules, $messages);
            $request->session()->flash('task_errors', '入力項目に問題があります。');
            if($validator->fails()){
                return redirect('/task/fix/'.$task_id)
                ->withErrors($validator)
                ->withInput();
            }

            //追加タスクパラメータセット処理
            if($task_start_datetime_status=='true'){
                //insertパラメータセット
                unset($insert_param['user_id']);
                unset($insert_param['task_id']);
                $insert_param['task_name']=$task_name;
                $insert_param['task_detail']=$task_detail;
                $insert_param['task_start_datetime']=$task_start_datetime;
                $insert_param['task_end_datetime']=$task_end_datetime;
                $insert_param['completed']='deadline_incomplete';
            }else{
                //insertパラメータセット
                unset($insert_param['user_id']);
                unset($insert_param['task_id']);
                $insert_param['task_name']=$task_name;
                $insert_param['task_detail']=$task_detail;
                $insert_param['task_start_datetime']=$task_start_datetime;
                $insert_param['task_end_datetime']=$task_end_datetime;
                $insert_param['completed']='update';
            }

            //タスク更新処理
            Task::where('task_id',$task_id)
                    ->update($insert_param);
            $request->session()->flash('update_message', 'タスクを更新しました');
            
            //タスク詳細画面に遷移する
            return redirect('task/detail/'.$task_id);

        }catch(\Exception){
            $request->session()->flash('login_errors', 'セッションが切れました。もう一度ログインしてください。');
            //ログイン画面へ遷移
            return self::taskapp_login();
        }
    }

    //タスク追加(get)
    public function task_insert(){
        //タスク追加画面に遷移
        return view('task.task_insert');
    }

    //タスク追加(post)
    public function task_insert_registration(Request $request){

        try{
            //セッション情報取得
            $user_id = $request->session()->get('user_id');

            //入力内容取得
            $task_id;
            $task_name = $request->task_name;
            $task_detail = $request->task_detail;
            $task_start_datetime = $request->task_start_datetime;
            $task_end_datetime = $request->task_end_datetime;
            $task_start_datetime_status = $request->task_start_datetime_status;
            $task_datetime_counter = $request->task_datetime_counter;

            //作業用変数
            $work_column;
            $work_max_id;

            //バリデーション情報取得
            $rules = Task::$rules;
            $messages = Task::$messages;

            //insertパラメータ取得
            $insert_param = Task::$task_param;

            //追加タスク件数
            $insert_count = 1;

            //タスクパラメータ削除処理
            if($task_start_datetime_status=='true'){
                unset($rules['task_start_datetime']);
                unset($messages['task_start_datetime.required']);
                unset($messages['task_start_datetime.task_datetime']);
            }       
            if($task_datetime_counter!=null){
                if($task_datetime_counter>0){
                    for($i=0; $i<$task_datetime_counter; $i++){
                        $work_column = "task_start_datetime".($i+1);
                        $rules = $rules+[$work_column => 'required|task_datetime'];
                        $messages = $messages+[$work_column.'.required' => 'タスク開始日付・時間は必ず入力して下さい。'];
                        $messages = $messages+[$work_column.'.task_datetime' => 'タスク開始日付には過去の日付を登録することはできません。'];
                        $work_column = "task_end_datetime".($i+1);
                        $rules = $rules+[$work_column => 'required|after_or_equal:task_start_datetime'.($i+1).'|task_datetime'];
                        $messages = $messages+[$work_column.'.required' => 'タスク終了日付・時間は必ず入力して下さい。'];
                        $messages = $messages+[$work_column.'.after_or_equal' => 'タスク終了日付にはタスク開始日付・時間以降の日付を入力して下さい。'];
                        $messages = $messages+[$work_column.'.task_datetime' => 'タスク終了日付には過去の日付を登録することはできません。'];
                        $request->session()->put('task_datetime_counter',$task_datetime_counter);
                    }
                }else{
                    $request->session()->put('task_datetime_counter',0);
                }
            }

            //エラー処理
            $validator  = Validator::make($request->all(), $rules, $messages);
            $request->session()->flash('task_errors', '入力項目に問題があります。');
            if($validator->fails()){
                return redirect('task/add')
                ->withErrors($validator)
                ->withInput();
            }

            //タスク追加処理
            //タスクID作成
            $work_max_id = Task::select('task_id')
                                ->orderBy('task_id','desc')
                                ->first();
            if($work_max_id==null){
                $task_id = 1;
            }else{
                $task_id = $work_max_id->task_id+1;
            }

            //追加タスクパラメータセット処理
            if($task_start_datetime_status=='true'){
                //insertパラメータセット
                $insert_param['user_id']=$user_id;
                $insert_param['task_id']=$task_id;
                $insert_param['task_name']=$task_name;
                $insert_param['task_detail']=$task_detail;
                $insert_param['task_start_datetime']=$task_start_datetime;
                $insert_param['task_end_datetime']=$task_end_datetime;
                $insert_param['completed']='deadline_incomplete';
            }else{
                //insertパラメータセット
                $insert_param['user_id']=$user_id;
                $insert_param['task_id']=$task_id;
                $insert_param['task_name']=$task_name;
                $insert_param['task_detail']=$task_detail;
                $insert_param['task_start_datetime']=$task_start_datetime;
                $insert_param['task_end_datetime']=$task_end_datetime;
                $insert_param['completed']='insert';
            }

            //初期タスク追加処理
            Task::insert($insert_param);

            //追加タスク処理
            if($task_datetime_counter!=null){
                //入力内容取得
                $task_start_datetime1 = $request->task_start_datetime1;
                $task_end_datetime1 = $request->task_end_datetime1;
                $task_start_datetime2 = $request->task_start_datetime2;
                $task_end_datetime2 = $request->task_end_datetime2;
                $task_start_datetime3 = $request->task_start_datetime3;
                $task_end_datetime3 = $request->task_end_datetime3;
                $task_start_datetime4 = $request->task_start_datetime4;
                $task_end_datetime4 = $request->task_end_datetime4;
                $task_start_datetime5 = $request->task_start_datetime5;
                $task_end_datetime5 = $request->task_end_datetime5;

                for($i=0; $i<$task_datetime_counter; $i++){
                    //タスク追加処理
                    if($i==0){
                        $task_start_datetime = $task_start_datetime1;
                        $task_end_datetime = $task_end_datetime1;
                    }elseif($i==1){
                        $task_start_datetime = $task_start_datetime2;
                        $task_end_datetime = $task_end_datetime2;
                    }elseif($i==2){
                        $task_start_datetime = $task_start_datetime3;
                        $task_end_datetime = $task_end_datetime3;
                    }elseif($i==3){
                        $task_start_datetime = $task_start_datetime4;
                        $task_end_datetime = $task_end_datetime4;
                    }elseif($i==4){
                        $task_start_datetime = $task_start_datetime5;
                        $task_end_datetime = $task_end_datetime5;
                    }

                    //タスクID作成処理
                    $work_max_id = Task::select('task_id')
                                ->orderBy('task_id','desc')
                                ->first();
                    if($work_max_id==null){
                        $task_id = 1;
                    }else{
                        $task_id = $work_max_id->task_id+1;
                    }

                    //insertパラメータセット
                    $insert_param['user_id']=$user_id;
                    $insert_param['task_id']=$task_id;
                    $insert_param['task_name']=$task_name;
                    $insert_param['task_detail']=$task_detail;
                    $insert_param['task_start_datetime']=$task_start_datetime;
                    $insert_param['task_end_datetime']=$task_end_datetime;
                    $insert_param['completed']='insert';

                    //追加タスク追加
                    Task::insert($insert_param);
                    $insert_count += 1;
                }
            }
            
            //タスク追加メッセージ
            $request->session()->flash('insert_message', str($insert_count).'件のタスクを追加しました。');

            //タスク一覧画面に遷移
            return redirect('/task/app');
        }catch(\Exception){
            $request->session()->flash('login_errors', 'セッションが切れました。もう一度ログインしてください。');
            //ログイン画面へ遷移
            return self::taskapp_login();
        }
    }

    //タスク一覧(get)
    public function taskapp_list(Request $request){

        try{
            //タスク日付カウンター削除
            $request->session()->forget('task_datetime_counter');

            //変数定義
            $users_data;
            $user_admin;
            $user_name;
            $information_datas;
            $covid_data;
            $now_date;

            //ユーザ情報取得
            $user_id = $request->session()->get('user_id');
            $user_admin = $request->session()->get('admin');

            if($user_id == null){
                $user_id = $request->session()->get('admin_id');
            }

            //ユーザ名取得
            $user_data = User::select('user_name')
                                ->where('user_id',$user_id)
                                ->first();
            $user_name = $user_data->user_name;
    
            //タスク権限更新処理
            self::usertask_date_update($user_id);

            //タスクデータ取得
            $items = self::task_createlist($request,$user_id,$user_admin);

            //インフォメーション情報取得
            $information_datas = Information::select('information_id','information_name','information_detail','information_date',str($user_name).'_flg')
                                                ->orderby('information_date','desc')
                                                ->orderby('information_id','desc')
                                                ->get();

            //現在時間取得
            $now_date = date("Y年m月d日 H時i分s秒"); 

            //タスク一覧画面に遷移
            return view('task.taskapp_top',['tasks'=>$items, 'date'=>$now_date, 'informations'=>$information_datas, 'user_name'=>$user_name]);
        }catch(\Exception $e){

            $request->session()->flash('login_errors', 'セッションが切れました。もう一度ログインしてください。');
            //ログイン画面へ遷移
            return self::taskapp_login();
        }
    }

    //タスクデータ取得
    public function task_createlist(Request $request,$user_id,$user_admin){
        //絞り込み検索処理
        //checkbox内容取得
        //タスクステータス
        $task_status_excess = $request->task_status_excess;
        $task_status_complete = $request->task_status_complete;
        //タスク日付
        //フラグ内容
        $task_month_findflg = $request->task_month_findflg;
        $task_date_findflg = $request->task_date_findflg;
        $task_time_findflg = $request->task_time_findflg;
        $task_today_flg = $request->task_today_flg;
        //入力内容
        $task_find_month = $request->task_find_month;
        $task_find_date = $request->task_find_date;
        $task_find_time = $request->task_find_time;
        //タスク名
        //フラグ内容
        $task_name_findflg = $request->task_name_findflg;
        //入力内容
        $task_find_name = $request->task_find_name;

        //変数定義
        $task_status_flg = false;
        $task_date_flg = false;
        $task_name_flg = false;
        $task_find_flg = false;
        $now_date;
        $now;
        $task_get_items = [];
        $task_total_items = array();
        $task_datetotal_items = array();
        $task_date_items = array();
        $task_work_items = [];
        $task_insert_items = [];
        $dis_count = 0;
        $dis_date_count = 0;


        if($task_status_excess == 'true' || $task_status_complete == 'true'){
            $task_status_flg = true;
        }

        if($task_month_findflg == 'true' || $task_date_findflg == 'true' || $task_time_findflg == 'true' || $task_today_flg == 'true'){
            $task_date_flg = true;
        }

        if($task_name_findflg == 'true'){
            $task_name_flg = true;
        }

        if($task_status_flg == 'true' || $task_date_flg == 'true' || $task_name_flg == 'true'){
            $task_find_flg = true;
        }

        //日付
        //検索日付取得
        $task_find_date = Carbon::parse($task_find_date);
        $task_find_date = $task_find_date->format("Y/m/d");
        $task_find_month = Carbon::parse($task_find_month);
        $task_find_month = $task_find_month->format("Y/m");
        //今日の日付・時間
        $now = Carbon::now();
        $now_date = $now->format("Y/m/d");
        if($task_find_flg == true){
            if($user_admin == "admin"){
                if($task_status_flg == true){
                    if($task_status_excess == true){
                        $task_get_items = Task::select('task_id','task_name','task_detail',Task::raw('date_format(task_start_datetime,"%Y年%m月%d日 %k時%i分") as task_start_datetime'),Task::raw('date_format(task_end_datetime,"%Y年%m月%d日 %k時%i分") as task_end_datetime'),'user_id','completed')
                                                    ->where('completed','!=','complete')
                                                    ->orderby('user_id','asc')
                                                    ->get()
                                                    ->all();
                        $task_total_items = array_merge($task_total_items,$task_get_items);
                    }
                    if($task_status_complete == true){
                        $task_get_items = Task::select('task_id','task_name','task_detail',Task::raw('date_format(task_start_datetime,"%Y年%m月%d日 %k時%i分") as task_start_datetime'),Task::raw('date_format(task_end_datetime,"%Y年%m月%d日 %k時%i分") as task_end_datetime'),'user_id','completed')
                                                    ->where('completed','complete')
                                                    ->orderby('user_id','asc')
                                                    ->get()
                                                    ->all();
                        $task_total_items = array_merge($task_total_items,$task_get_items);
                    }
                    $dis_count += 1;
                }
                if($task_date_flg == true){
                    if($task_today_flg == true){
                        $task_get_items = Task::select('task_id','task_name','task_detail',Task::raw('date_format(task_start_datetime,"%Y年%m月%d日 %k時%i分") as task_start_datetime'),Task::raw('date_format(task_end_datetime,"%Y年%m月%d日 %k時%i分") as task_end_datetime'),'user_id','completed')
                                                    ->where(Task::raw('date_format(task_start_datetime,"%Y/%m/%d")'),$now_date)
                                                    ->orwhere(Task::raw('date_format(task_end_datetime,"%Y/%m/%d")'),$now_date)
                                                    ->orderby('user_id','asc')
                                                    ->get()
                                                    ->all();
                        $task_datetotal_items = array_merge($task_datetotal_items,$task_get_items);
                        $dis_date_count += 1;
                    }
                    if($task_month_findflg == true){
                        $task_get_items = Task::select('task_id','task_name','task_detail',Task::raw('date_format(task_start_datetime,"%Y年%m月%d日 %k時%i分") as task_start_datetime'),Task::raw('date_format(task_end_datetime,"%Y年%m月%d日 %k時%i分") as task_end_datetime'),'user_id','completed')
                                                    ->where(Task::raw('date_format(task_start_datetime,"%Y/%m")'),$task_find_month)
                                                    ->orwhere(Task::raw('date_format(task_end_datetime,"%Y/%m")'),$task_find_month)
                                                    ->orderby('user_id','asc')
                                                    ->get()
                                                    ->all();
                        $task_datetotal_items = array_merge($task_datetotal_items,$task_get_items);
                        $dis_date_count += 1;
                    }
                    if($task_date_findflg == true){
                        $task_get_items = Task::select('task_id','task_name','task_detail',Task::raw('date_format(task_start_datetime,"%Y年%m月%d日 %k時%i分") as task_start_datetime'),Task::raw('date_format(task_end_datetime,"%Y年%m月%d日 %k時%i分") as task_end_datetime'),'user_id','completed')
                                                    ->where(Task::raw('date_format(task_start_datetime,"%Y/%m/%d")'),$task_find_date)
                                                    ->orwhere(Task::raw('date_format(task_end_datetime,"%Y/%m/%d")'),$task_find_date)
                                                    ->orderby('user_id','asc')
                                                    ->get()
                                                    ->all();
                        $task_datetotal_items = array_merge($task_datetotal_items,$task_get_items);
                        $dis_date_count += 1;
                    }
                    if($task_time_findflg == true){
                        $task_get_items = Task::select('task_id','task_name','task_detail',Task::raw('date_format(task_start_datetime,"%Y年%m月%d日 %k時%i分") as task_start_datetime'),Task::raw('date_format(task_end_datetime,"%Y年%m月%d日 %k時%i分") as task_end_datetime'),'user_id','completed')
                                                    ->where(Task::raw('date_format(task_start_datetime,"%H:%i")'),$task_find_time)
                                                    ->orwhere(Task::raw('date_format(task_end_datetime,"%H:%i")'),$task_find_time)
                                                    ->orderby('user_id','asc')
                                                    ->get()
                                                    ->all();
                        $task_datetotal_items = array_merge($task_datetotal_items,$task_get_items);
                        $dis_date_count += 1;
                    }
                    if($dis_date_count != 1){
                        for($i=0; count($task_datetotal_items)>$i; $i++){
                            $dis_date_count = 0;
                            for($j=0; count($task_datetotal_items)>$j; $j++){
                                if($task_datetotal_items[$i]==$task_datetotal_items[$j]){
                                    $dis_date_count += 1;
                                }
                                if($dis_date_count == 2){
                                    $task_insert_items[] = $task_datetotal_items[$i];
                                    $task_date_items = array_merge($task_date_items,$task_insert_items);
                                }
                            }
                        }
                        $task_date_items = array_unique($task_date_items);
                        $task_total_items = array_merge($task_total_items,$task_date_items);
                    }else{
                        $task_total_items = array_merge($task_total_items,$task_datetotal_items);
                    }
                    $dis_count += 1;
                }
                if($task_name_flg == true){
                    if($task_name_flg == true){
                        $task_get_items = Task::select('task_id','task_name','task_detail',Task::raw('date_format(task_start_datetime,"%Y年%m月%d日 %k時%i分") as task_start_datetime'),Task::raw('date_format(task_end_datetime,"%Y年%m月%d日 %k時%i分") as task_end_datetime'),'user_id','completed')
                                                    ->where('task_name','like','%'.$task_find_name.'%')
                                                    ->get()
                                                    ->all();
                        $task_total_items = array_merge($task_total_items,$task_get_items);
                    }
                    $dis_count += 1;
                }
                if($dis_count != 1){
                    for($i=0; count($task_total_items)>$i; $i++){
                        $dis_count = 0;
                        for($j=0; count($task_total_items)>$j; $j++){
                            if($task_total_items[$i]==$task_total_items[$j]){
                                $dis_count += 1;
                            }
                            if($dis_count == 2){
                                $task_insert_items[] = $task_total_items[$i];
                                $task_work_items = array_merge($task_work_items,$task_insert_items);
                            }
                        }
                    }
                    $task_work_items = array_unique($task_work_items);
                    $task_total_items = $task_work_items;
                }
                $items = collect($task_total_items);
                $items = new LengthAwarePaginator(
                    $items->forPage($request->page,10),
                    count($items),
                    10,
                    $request->page,
                    array('path'=>$request->url()));
            }else{
                if($task_status_flg == true){
                    if($task_status_excess == true){
                        $task_get_items = Task::select('task_id','task_name','task_detail',Task::raw('date_format(task_start_datetime,"%Y年%m月%d日 %k時%i分") as task_start_datetime'),Task::raw('date_format(task_end_datetime,"%Y年%m月%d日 %k時%i分") as task_end_datetime'),'user_id','completed')
                                                    ->where('user_id',$user_id)
                                                    ->where('completed','!=','complete')
                                                    ->get()
                                                    ->all();
                        $task_total_items = array_merge($task_total_items,$task_get_items);
                    }
                    if($task_status_complete == true){
                        $task_get_items = Task::select('task_id','task_name','task_detail',Task::raw('date_format(task_start_datetime,"%Y年%m月%d日 %k時%i分") as task_start_datetime'),Task::raw('date_format(task_end_datetime,"%Y年%m月%d日 %k時%i分") as task_end_datetime'),'user_id','completed')
                                                    ->where('user_id',$user_id)
                                                    ->where('completed','complete')
                                                    ->get()
                                                    ->all();
                        $task_total_items = array_merge($task_total_items,$task_get_items);
                    }
                    $dis_count += 1;
                }
                if($task_date_flg == true){
                    if($task_today_flg == true){
                        $task_get_items = Task::select('task_id','task_name','task_detail',Task::raw('date_format(task_start_datetime,"%Y年%m月%d日 %k時%i分") as task_start_datetime'),Task::raw('date_format(task_end_datetime,"%Y年%m月%d日 %k時%i分") as task_end_datetime'),'user_id','completed')
                                                    ->where('user_id',$user_id)
                                                    ->where(Task::raw('date_format(task_start_datetime,"%Y/%m/%d")'),$now_date)
                                                    ->orwhere(Task::raw('date_format(task_end_datetime,"%Y/%m/%d")'),$now_date)
                                                    ->get()
                                                    ->all();
                        $task_datetotal_items = array_merge($task_datetotal_items,$task_get_items);
                        $dis_date_count += 1;
                    }
                    if($task_month_findflg == true){
                        $task_get_items = Task::select('task_id','task_name','task_detail',Task::raw('date_format(task_start_datetime,"%Y年%m月%d日 %k時%i分") as task_start_datetime'),Task::raw('date_format(task_end_datetime,"%Y年%m月%d日 %k時%i分") as task_end_datetime'),'user_id','completed')
                                                    ->where('user_id',$user_id)
                                                    ->where(Task::raw('date_format(task_start_datetime,"%Y/%m")'),$task_find_month)
                                                    ->orwhere(Task::raw('date_format(task_end_datetime,"%Y/%m")'),$task_find_month)
                                                    ->get()
                                                    ->all();
                        $task_datetotal_items = array_merge($task_datetotal_items,$task_get_items);
                        $dis_date_count += 1;
                    }
                    if($task_date_findflg == true){
                        $task_get_items = Task::select('task_id','task_name','task_detail',Task::raw('date_format(task_start_datetime,"%Y年%m月%d日 %k時%i分") as task_start_datetime'),Task::raw('date_format(task_end_datetime,"%Y年%m月%d日 %k時%i分") as task_end_datetime'),'user_id','completed')
                                                    ->where('user_id',$user_id)
                                                    ->where(Task::raw('date_format(task_start_datetime,"%Y/%m/%d")'),$task_find_date)
                                                    ->orwhere(Task::raw('date_format(task_end_datetime,"%Y/%m/%d")'),$task_find_date)
                                                    ->get()
                                                    ->all();
                        $task_datetotal_items = array_merge($task_datetotal_items,$task_get_items);
                        $dis_date_count += 1;
                    }
                    if($task_time_findflg == true){
                        $task_get_items = Task::select('task_id','task_name','task_detail',Task::raw('date_format(task_start_datetime,"%Y年%m月%d日 %k時%i分") as task_start_datetime'),Task::raw('date_format(task_end_datetime,"%Y年%m月%d日 %k時%i分") as task_end_datetime'),'user_id','completed')
                                                    ->where('user_id',$user_id)
                                                    ->where(Task::raw('date_format(task_start_datetime,"%H:%i")'),$task_find_time)
                                                    ->orwhere(Task::raw('date_format(task_end_datetime,"%H:%i")'),$task_find_time)
                                                    ->get()
                                                    ->all();
                        $task_datetotal_items = array_merge($task_datetotal_items,$task_get_items);
                        $dis_date_count += 1;
                    }
                    if($dis_date_count != 1){
                        for($i=0; count($task_datetotal_items)>$i; $i++){
                            $dis_date_count = 0;
                            for($j=0; count($task_datetotal_items)>$j; $j++){
                                if($task_datetotal_items[$i]==$task_datetotal_items[$j]){
                                    $dis_date_count += 1;
                                }
                                if($dis_date_count == 2){
                                    $task_insert_items[] = $task_datetotal_items[$i];
                                    $task_date_items = array_merge($task_date_items,$task_insert_items);
                                }
                            }
                        }
                        $task_date_items = array_unique($task_date_items);
                        $task_total_items = array_merge($task_total_items,$task_date_items);
                    }else{
                        $task_total_items = array_merge($task_total_items,$task_datetotal_items);
                    }
                    $dis_count += 1;
                }
                if($task_name_flg == true){
                    if($task_name_flg == true){
                        $task_get_items = Task::select('task_id','task_name','task_detail',Task::raw('date_format(task_start_datetime,"%Y年%m月%d日 %k時%i分") as task_start_datetime'),Task::raw('date_format(task_end_datetime,"%Y年%m月%d日 %k時%i分") as task_end_datetime'),'user_id','completed')
                                                    ->where('user_id',$user_id)
                                                    ->where('task_name','like','%'.$task_find_name.'%')
                                                    ->get()
                                                    ->all();
                        $task_total_items = array_merge($task_total_items,$task_get_items);
                    }
                    $dis_count += 1;
                }
                if($dis_count != 1){
                    for($i=0; count($task_total_items)>$i; $i++){
                        $dis_count = 0;
                        for($j=0; count($task_total_items)>$j; $j++){
                            if($task_total_items[$i]==$task_total_items[$j]){
                                $dis_count += 1;
                            }
                            if($dis_count == 2){
                                $task_insert_items[] = $task_total_items[$i];
                                $task_work_items = array_merge($task_work_items,$task_insert_items);
                            }
                        }
                    }
                    $task_work_items = array_unique($task_work_items);
                    $task_total_items = $task_work_items;
                }
                $items = collect($task_total_items);
                $items = new LengthAwarePaginator(
                    $items->forPage($request->page,10),
                    count($items),
                    10,
                    $request->page,
                    array('path'=>$request->url()));
            }
        }else{
            if($user_admin == "admin"){
                $items = Task::select('task_id','task_name','task_detail',Task::raw('date_format(task_start_datetime,"%Y年%m月%d日 %k時%i分") as task_start_datetime'),Task::raw('date_format(task_end_datetime,"%Y年%m月%d日 %k時%i分") as task_end_datetime'),'user_id','completed')
                                ->orderby('user_id','asc')
                                ->paginate(10);
            }else{
                $items = Task::select('task_id','task_name','task_detail',Task::raw('date_format(task_start_datetime,"%Y年%m月%d日 %k時%i分") as task_start_datetime'),Task::raw('date_format(task_end_datetime,"%Y年%m月%d日 %k時%i分") as task_end_datetime'),'user_id','completed')
                                ->where('user_id',$user_id)
                                ->orderby('task_id','asc')
                                ->paginate(10);
            }
        }
        return $items;
    }

    //タスク検索
    public function task_find(Request $request){

        //タスク一覧画面遷移
        return self::taskapp_list($request);
    }

    //タスク詳細(get)
    public function task_detail(Request $request){
        try{
            //タスクID取得
            $task_id = $request->task_id;

            //タスク情報取得
            $items = Task::select('task_id','task_name','task_detail',Task::raw('date_format(task_start_datetime,"%Y年%m月%d日 %k時%i分") as task_start_datetime'),Task::raw('date_format(task_end_datetime,"%Y年%m月%d日 %k時%i分") as task_end_datetime'),'completed')
                                ->where('task_id',$task_id)
                                ->get();

            //タスク詳細画面に遷移
            return view('task.task_detail',['tasks'=>$items]);
        }catch(\Exception){
            $request->session()->flash('login_errors', 'セッションが切れました。もう一度ログインしてください。');
            //ログイン画面へ遷移
            return self::taskapp_login();
        }
    }

    //タスク削除
    public function task_delete(Request $request){
        try{
            //タスクID取得
            $task_id = $request->task_id;

            //変数定義
            $task_datas;

            //指定したタスクID以上のタスクIDを昇順抽出
            $task_datas = Task::select('task_id')
                                ->where('task_id','>',$task_id)
                                ->orderby('task_id','asc')
                                ->get();

            //タスクデータ削除
            Task::where('task_id',$task_id)
                    ->delete();

            foreach($task_datas as $task_data){
                //タスクID取得
                $get_task_id = $task_data->task_id;

                //タスクID更新
                $update_param=[
                    'task_id'=>($get_task_id-1),
                ];
                Task::where('task_id',$get_task_id)->update($update_param);
            }

            $request->session()->flash('delete_message', 'タスクを削除しました。');
            
            //タスク一覧画面に遷移
            return redirect('/task/app');
        }catch(\Exception){
            $request->session()->flash('login_errors', 'セッションが切れました。もう一度ログインしてください。');
            //ログイン画面へ遷移
            return self::taskapp_login();
        }
    }

    //ログイン(get)
    public function taskapp_login(){
        //ログイン画面に遷移
        return view('task.taskapp_login');
    }

    //ログイン(post)
    public function taskapp_login_registration(Request $request){
            //セッション削除
            $request->session()->flush();

            //変数
            $user_admin;
            $user_id;

            //入力情報取得
            $user_pass = $request->user_pass;
            $user_email = $request->user_email;

            //バリデーション情報取得
            $rules = User::$rules;
            $messages = User::$messages;

            //バリデーション情報編集
            unset($rules['user_name']);
            unset($messages['user_name.required']);

            //バリデーション処理
            $validator  = Validator::make($request->all(), $rules, $messages);
            if($validator->fails()){
                return redirect('/task')
                ->withErrors($validator)
                ->withInput();
            }

            //ユーザ認証
            $user = User::select('user_id','admin')
                            ->where('user_email',$user_email)
                            ->where('user_pass',$user_pass)
                            ->first();

            //エラー処理
            if(empty($user)){
                $request->session()->flash('login_errors', 'ユーザが見つかりません。');
                return redirect('/task');
            }elseif($user->admin == "admin"){
                $request->session()->flash('login_errors', 'こちらでは管理者ログインを行うことができません。');
                return redirect('/task');
            }

            //ユーザ情報取得
            $user_id = $user->user_id;
            $user_admin = $user->admin;

            //セッション登録
            $request->session()->put('user_id', $user_id);
            $request->session()->put('admin', $user_admin);
            $request->session()->put('admin_flg', false);
        
            //タスク一覧画面に遷移
            return redirect('/task/app');
    }

    //新規会員登録(get)
    public function new_member(){
        return view('task.loginuser_insert');
    }

    //新規会員登録(post)
    public function new_member_registration(Request $request){
        
            //入力情報取得
            $user_name = $request->user_name;
            $user_pass = $request->user_pass;
            $user_email = $request->user_email;
            $user_admin = $request->authority;

            //変数
            $user_id;
            $get_user_id;
            $insert_admin;

            //バリデーション情報取得
            $rules = User::$rules;
            $messages = User::$messages;

            //バリデーション処理
            $validator  = Validator::make($request->all(), $rules, $messages);
            if($validator->fails()){
                return redirect('/login/insert')
                ->withErrors($validator)
                ->withInput();
            }

            //登録ユーザ確認
            $get_user_data = User::select('user_id')
                                    ->where('user_name',$user_name)
                                    ->orwhere('user_email',$user_email)
                                    ->first();  
            if($get_user_data!=null){
                $request->session()->flash('insert_errors', '入力されたユーザ名は使用できません。');
                return redirect('/login/insert');
            }

            //ユーザID取得&生成
            $get_user_data = User::select('user_id')
                                    ->orderBy('user_id','desc')
                                    ->first();  
            if($get_user_data==null){
                $user_id = 1;
            }else{
                $user_id = $get_user_data->user_id+1;
            }

            //権限設定
            if($user_admin == 'user_authority'){
                $insert_admin = "user";
            }else{
                $insert_admin = "admin";
            }

            //insertパラメータ取得
            $insert_param = User::$user_param;

            //insertパラメータセット
            $insert_param['user_id']=$user_id;
            $insert_param['user_name']=$user_name;
            $insert_param['user_pass']=$user_pass;
            $insert_param['user_email']=$user_email;
            $insert_param['admin']=$insert_admin;
            User::insert($insert_param);

            //ユーザ名フラグ追加
            $alter_sql = 'alter table information_board add column '.str($user_name).'_flg boolean default false';
            DB::statement($alter_sql);

            $request->session()->flash('insert_message', 'ユーザを追加しました。');
            
            //ログイン画面に遷移
            return redirect('/task'); 
    }
    
    //csv出力
    public function task_csv(Request $request)
    {
        try{
            //ユーザID・権限取得
            $user_id = $request->session()->get('user_id');
            $user_admin = $request->session()->get('admin');

            //定数定義
            $counter = 1;
            $completed = '';

            if($user_admin == 'admin'){
                //タスクデータ取得
                $items = Task::select('task_name','task_detail',Task::raw('date_format(task_start_datetime,"%Y-%m-%dT%k:%i") as task_start_datetime'),Task::raw('date_format(task_end_datetime,"%Y-%m-%dT%k:%i") as task_end_datetime'),'completed')
                                    ->orderby('user_id','asc')
                                    ->orderby('task_id','asc')
                                    ->get();

                //タスク配列作成
                $data = [];
                $data[] = ['タスク一覧'];
                $data[] = ['No', 'タスク名', 'タスク詳細', 'タスク日付', 'タスク時間','ステータス'];
                foreach($items as $item){
                    if($item->completed=='excess_incomplete'){
                        $completed = "未完了のタスク";
                    }elseif($item->completed=='today_incomplete'){
                        $completed = "今日のタスク";
                    }elseif($item->completed=='future_incomplete'){
                        $completed = "明日以降のタスク";
                    }elseif($item->completed=='complete'){
                        $completed = "完了済タスク";
                    }else{
                        $completed = "期限タスク";
                    }
                    $data[] = [$counter,$item->task_name,$item->task_detail,$item->task_start_datetime,$item->task_end_datetime,$completed];
                    $counter += 1;
                }
            }else{
                //タスクデータ取得
                $items = Task::select('task_name','task_detail',Task::raw('date_format(task_start_datetime,"%Y-%m-%dT%k:%i") as task_start_datetime'),Task::raw('date_format(task_end_datetime,"%Y-%m-%dT%k:%i") as task_end_datetime'),'completed')
                                ->where('user_id',$user_id)
                                ->orderby('task_start_datetime','asc')
                                ->orderby('task_end_datetime','asc')
                                ->get();

                //タスク配列作成
                $data = [];
                $data[] = ['タスク一覧'];
                $data[] = ['No', 'タスク名', 'タスク詳細', 'タスク日付', 'タスク時間','ステータス'];
                foreach($items as $item){
                    if($item->completed=='excess_incomplete'){
                        $completed = "未完了のタスク";
                    }elseif($item->completed=='today_incomplete'){
                        $completed = "今日のタスク";
                    }elseif($item->completed=='future_incomplete'){
                        $completed = "明日以降のタスク";
                    }elseif($item->completed=='complete'){
                        $completed = "完了済タスク";
                    }else{
                        $completed = "期限タスク";
                    }
                    $data[] = [$counter,$item->task_name,$item->task_detail,$item->task_start_datetime,$item->task_end_datetime,$completed];
                    $counter += 1;
                }
            }

            $save_file = storage_path('task.csv');
            $file = new \SplFileObject($save_file, 'w'); // ファイルが無ければ作成
            $file->setCsvControl(",");                   // カンマ区切り
            foreach ($data as $row) {
                mb_convert_variables('SJIS', 'UTF-8', $row);
                $file->fputcsv($row);
            }

            return response()->download(storage_path('task.csv'));
        }catch(\Exception){
            $request->session()->flash('login_errors', 'セッションが切れました。もう一度ログインしてください。');
            //ログイン画面へ遷移
            return self::taskapp_login();
        }
    }

    //タスク完了更新処理
    public function task_success_update(Request $request){
        
        //タスクID取得
        $task_id = $request->task_id;

        //タスク権限更新
        $update_param = [
            'completed' => 'complete',
        ];
        Task::where('task_id',$task_id)
                ->update($update_param);

        $request->session()->flash('completed_message', 'タスクを完了済みに更新しました。');
        
        //タスク一覧画面に遷移
        return redirect('/task/detail/'.$task_id);
    }

    //タスク完了取消処理
    public function task_success_denger(Request $request){

        //タスクID取得
        $task_id = $request->task_id;

        //タスクフラグ更新処理
        $update_param = [
            'completed' => 'cancel',
        ];
        Task::where('task_id',$task_id)
                ->update($update_param);

        $request->session()->flash('incomplete_message', 'タスクの完了を取り消しました');
        
        //タスク一覧画面に遷移
        return redirect('/task/detail/'.$task_id);
    }

    //タスク日付更新処理(user)
    public function usertask_date_update($user_id){
        
       //通常タスク取得
        $task_datas = Task::select('task_id','task_start_datetime','task_end_datetime')
                            ->where('completed','!=','complete')
                            ->where('completed','!=','deadline_incomplete')
                            ->where('user_id',$user_id)
                            ->get();
        static::task_repeat($task_datas,'normal');

        //期限タスク更新処理
        $task_datas = Task::select('task_id','task_end_datetime')   
                            ->where('user_id',$user_id) 
                            ->where('completed','deadline_incomplete')
                            ->get();
        if(!empty($task_datas)){
            static::task_repeat($task_datas,'deadline');
        }
    }

    //データ更新繰り返し処理
    public static function task_repeat($task_datas, $task_type){

        //初期値設定
        $completed = '';

        //変数定義
        $task_start_datetime;
        $task_start_date;
        $task_end_datetime;
        $task_end_date;
        $now_datetime;
        $now_date;
        $now;

        if($task_type == "normal")
            foreach($task_datas as $task_data){
                $task_id = $task_data->task_id;

                //更新処理
                $task_start_datetime = $task_data->task_start_datetime;
                $task_end_datetime = $task_data->task_end_datetime;

                //日付・時間処理
                //タスク開始日付・時間
                $task_start_datetime = Carbon::parse($task_start_datetime);
                $task_start_date = $task_start_datetime->format("Y/m/d");
                $task_start_datetime = $task_start_datetime->format('Y-m-d H:i:s');
                //タスク終了日付・時間
                $task_end_datetime = Carbon::parse($task_end_datetime);
                $task_end_date = $task_end_datetime->format("Y/m/d");
                $task_end_datetime = $task_end_datetime->format('Y-m-d H:i:s');
                //今日の日付・時間
                $now = Carbon::now();
                $now_date = $now->format("Y/m/d");
                $now_datetime = $now->format('Y-m-d H:i:s');

                //フラグ設定処理
                if($task_end_datetime<$now_datetime){
                    $completed = "excess_incomplete";   
                }elseif(($task_start_date<=$now_date)&&($task_end_date>=$now_date)){
                    $completed = "today_incomplete";
                }else{
                    $completed = "future_incomplete";
                }

                //タスク権限更新処理
                $update_param = [
                    'completed' => $completed,
                ];
                Task::where('task_id',$task_id)
                    ->update($update_param); 
        }else{
            foreach($task_datas as $task_data){
                $task_id = $task_data->task_id;
    
                //更新処理
                $task_end_datetime = $task_data->task_end_datetime;
    
                //日付・時間処理
                //タスク終了日付・時間
                $task_end_datetime = Carbon::parse($task_end_datetime);
                $task_end_date = $task_end_datetime->format("Y/m/d");
                $task_end_datetime = $task_end_datetime->format('Y-m-d H:i:s');
                //今日の日付・時間
                $now = Carbon::now();
                $now_date = $now->format("Y/m/d");
                $now_datetime = $now->format('Y-m-d H:i:s');
    
                //フラグ設定処理
                if($task_end_datetime<$now_datetime){
                    $completed = "excess_incomplete";   
                }elseif($task_end_date==$now_date){
                    $completed = "today_incomplete";
                }else{
                    $completed = "deadline_incomplete";
                }

                //タスクフラグ更新処理
                $update_param = [
                    'completed' => $completed,
                ];
                Task::where('task_id',$task_id)
                    ->update($update_param);
            }
        }
    }

    //タスク日付更新処理(全ユーザ)
    public static function usertask_date_allupdate(){

        //ユーザデータ取得
        $user_datas = User::select('user_id')
                            ->get();

        foreach($user_datas as $user_data){

            //ユーザID取得
            $user_id = $user_data->user_id;

            //通常タスク取得
            $task_datas = Task::select('task_id','task_start_datetime','task_end_datetime')
                                ->where('completed','!=','complete')
                                ->where('completed','!=','deadline_incomplete')
                                ->where('user_id',$user_id)
                                ->get();
            static::task_repeat($task_datas,'normal');

            //期限タスク更新処理
            $task_datas = Task::select('task_id','task_end_datetime')   
                                ->where('user_id',$user_id) 
                                ->where('completed','deadline_incomplete')
                                ->get();
            if(!empty($task_datas)){
                static::task_repeat($task_datas,'deadline');
            }
        }
    }

    //パスワードを忘れた
    public function pass_forget(){
        //パスワード変更画面に遷移
        return view('task.pass_forget');        
    }

    //パスワードを更新
    public function pass_update(Request $request){

        try{
            //入力情報取得
            $user_email = $request->user_email;

            //バリデーション情報取得
            $rules = User::$rules;
            $messages = User::$messages;

            //バリデーション情報編集
            unset($rules['user_name']);
            unset($rules['user_pass']);
            unset($messages['user_name.required']);
            unset($messages['user_pass.required']);

            //バリデーション処理
            $validator  = Validator::make($request->all(), $rules, $messages);
            if($validator->fails()){
                return redirect('/pass/forget')
                ->withErrors($validator)
                ->withInput();
            }   

            $get_user_data = User::select('user_name')
                                    ->where('user_email',$user_email)
                                    ->first();  

            //ユーザ情報取得
            $user_name = $get_user_data->user_name;
            $mail_to = $user_email;
            $today_task = null;
            $old_task = null;
            $select_admin = null;
            $flg = "passforget";

            //メール送信
            Mail::to($mail_to)->send( new SendMail($user_name, $mail_to, $today_task, $old_task, $select_admin, $flg));

            //パスワード変更画面に遷移
            return view('task.pass_update');   
        }catch(\Exception){
            $request->session()->flash('login_errors', 'セッションが切れました。もう一度ログインしてください。');
            //ログイン画面へ遷移
            return self::taskapp_login();
        }
    }

    //パスワードを更新
    public function pass_updated(Request $request){

        try{
            //入力情報取得
            $user_email = $request->user_email;
            $user_pass = $request->user_pass;

            //バリデーション情報取得
            $rules = User::$rules;
            $messages = User::$messages;

            //更新パラメータ取得
            $user_param = User::$user_param;

            //バリデーション情報編集
            unset($rules['user_name']);
            unset($rules['user_email']);
            unset($messages['user_name.required']);
            unset($messages['user_email.required']);
            unset($user_param['user_id']);
            unset($user_param['user_name']);
            unset($user_param['user_email']);
            unset($user_param['admin']);

            //バリデーション処理
            $validator  = Validator::make($request->all(), $rules, $messages);
            if($validator->fails()){
                return redirect("/pass_forget"."/".$user_email)
                ->withErrors($validator)
                ->withInput();
            }   

            //タスク権限更新処理
            $user_param['user_pass']=$user_pass;

            User::where('user_email',$user_email)
                    ->update($user_param);

            //パスワード変更画面に遷移
            return view('task.pass_updated');   
        }catch(\Exception){
            $request->session()->flash('login_errors', 'セッションが切れました。もう一度ログインしてください。');
            //ログイン画面へ遷移
            return self::pass_input();
        }
    }

    //パスワード変更画面
    public function pass_input(Request $request){

        //メールアドレス取得
        $user_email = $request->user_email;

        //パスワード変更画面に遷移
        return view('task.pass_input',['user_email'=>$user_email]);        
    }
}