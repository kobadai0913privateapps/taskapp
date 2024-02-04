<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Mail;
use App\Mail\SendMail;

class MailSendController extends Controller
{
	public static function batchEmailSending(){
		//全ユーザ日付フラグ更新処理
        $task_controller = new TaskController;
        $task_controller->usertask_date_allupdate();

		//user_id,admin取得
		$user_data = DB::select('select user_id, user_email, user_name, admin from user');
		foreach($user_data as $data){

			//変数定義
			$items = [];
			$csv_flg;
			$today_task=array();
			$old_task=array();

			//定数定義
			$today_count = 1;
			$old_count = 1;

			//ユーザ情報取得
			$select_user = $data->user_id;
			$select_email = $data->user_email;
			$select_admin = $data->admin;
			$select_name = $data->user_name;
			$mail_to = $select_email;

			//ユーザ権限処理
			if($select_admin == "admin"){
				$user_name = 'タスク管理アプリ管理者';
			}else{
				$user_name = $select_name;
			}
	
			//admin処理・user処理
			if($select_admin == 'admin'){
				$param = [
					"start_datetime" => '%Y年%m月%d日 %k時%i分',
            		"end_datetime" => '%Y年%m月%d日 %k時%i分',
					'completed' => 'today_incomplete',
					'completedf' => 'excess_incomplete',
				];
				$items = DB::select('select t.task_name as task_name, t.task_detail as task_detail, date_format(t.task_start_datetime,:start_datetime) as task_start_datetime, date_format(t.task_end_datetime,:end_datetime) as task_end_datetime, t.completed as task_completed, u.user_id as user_id, u.user_name as user_name, u.user_email as user_email from user_taskmanage t inner join user u on t.user_id = u.user_id where t.completed = :completed or t.completed = :completedf order by task_start_datetime, task_end_datetime',$param);
				if(!empty($items)){
					foreach($items as $datas){
						//今日のタスク
						if($datas->task_completed == "today_incomplete"){
							$work = array(array(str($today_count),$datas->task_name,$datas->task_detail,$datas->task_start_datetime,$datas->task_end_datetime,$datas->user_id,$datas->user_name,$datas->user_email));
							$today_task = array_merge($today_task,$work);
							$today_count += 1;
						}
						//未完了タスク(今日以前)
						if($datas->task_completed == "excess_incomplete"){
							$work = array(array(str($old_count),$datas->task_name,$datas->task_detail,$datas->task_start_datetime,$datas->task_end_datetime,$datas->user_id,$datas->user_name,$datas->user_email));
							$old_task = array_merge($old_task,$work);
							$old_count += 1;
						}
					}
				}
			}else{
				$param = [
					"user_id" => $select_user,
					"start_datetime" => '%Y年%m月%d日 %k時%i分',
            		"end_datetime" => '%Y年%m月%d日 %k時%i分',
					'completed' => 'today_incomplete',
					'completedf' => 'excess_incomplete',
				];
				$items = DB::select('select task_name, task_detail, date_format(task_start_datetime,:start_datetime) as task_start_datetime, date_format(task_end_datetime,:end_datetime) as task_end_datetime, completed from user_taskmanage where user_id=:user_id and (completed = :completed or completed = :completedf) order by task_start_datetime, task_end_datetime',$param);
				if(!empty($items)){
					foreach($items as $datas){
						//今日のタスク
						if($datas->completed == "today_incomplete"){
							$work = array(array(str($today_count),$datas->task_name,$datas->task_detail,$datas->task_start_datetime,$datas->task_end_datetime));
							$today_task = array_merge($today_task,$work);
							$today_count += 1;
						}
						//未完了タスク(今日以前)
						if($datas->completed == "excess_incomplete"){
							$work = array(array(str($old_count),$datas->task_name,$datas->task_detail,$datas->task_start_datetime,$datas->task_end_datetime));
							$old_task = array_merge($old_task,$work);
							$old_count += 1;
						}
					}
				}
			}
			//csv関数呼び出し
			$csv_flg = self::csvoutput($select_admin, $today_task, $old_task);

			//メール送信
			Mail::to($mail_to)->send( new SendMail($user_name, $mail_to, $today_task, $old_task, $select_admin, $csv_flg));
		}
	}

	//csv関数
	public static function csvoutput($text, $today_task, $old_task){
		//変数定義
		$csv_count = 1;
		$data = [];
		$csvw_flg = false;

		if($text == "admin"){
			//csvファイル作成
			if(!empty($today_task)){
				$data[] = ['本日のタスク'];
				$data[] = ['No', 'ユーザID', 'ユーザ名', 'emailアドレス', 'タスク名', 'タスク詳細', 'タスク開始日付', 'タスク終了日付'];
				foreach($today_task as $item){
					$data[] = [$item[0],$item[5],$item[6],$item[7],$item[1],$item[2],$item[3],$item[4]];
				}
				$csvw_flg = true;
			}
			if(!empty($old_task)){
				$data[] = ['未完了のタスク'];
				$data[] = ['No', 'ユーザID', 'ユーザ名', 'emailアドレス', 'タスク名', 'タスク詳細', 'タスク開始日付','タスク終了日付'];
				foreach($old_task as $item){
					$data[] = [$item[0],$item[5],$item[6],$item[7],$item[1],$item[2],$item[3],$item[4]];
				}
				$csvw_flg = true;
			}
			if($csvw_flg == true){
				$save_file = storage_path('task.csv');
				$file = new \SplFileObject($save_file, 'w'); // ファイルが無ければ作成
				$file->setCsvControl(",");                   // カンマ区切り
				foreach ($data as $row) {
					mb_convert_variables('SJIS', 'UTF-8', $row);
					$file->fputcsv($row);
				}
			}
		}else{
			if(!empty($today_task)){
				//csvファイル作成
				$data[] = ['本日のタスク'];
				$data[] = ['No', 'タスク名', 'タスク詳細', 'タスク開始日付', 'タスク終了日付'];
				foreach($today_task as $item){
					$data[] = [$item[0],$item[1],$item[2],$item[3],$item[4]];
				}
				$csvw_flg = true;
			}
			if(!empty($old_task)){
				$data[] = ['未完了のタスク'];
				$data[] = ['No', 'タスク名', 'タスク詳細', 'タスク開始日付', 'タスク終了日付'];
				foreach($old_task as $item){
					$data[] = [$item[0],$item[1],$item[2],$item[3],$item[4]];
				}
				$csvw_flg = true;
			}
			if($csvw_flg == true){
				$save_file = storage_path('task.csv');
				$file = new \SplFileObject($save_file, 'w'); // ファイルが無ければ作成
				$file->setCsvControl(",");                   // カンマ区切り
				foreach ($data as $row) {
					mb_convert_variables('SJIS', 'UTF-8', $row);
					$file->fputcsv($row);
				}
			}
		}
		return $csvw_flg;
	} 
}
