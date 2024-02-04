<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Providers;

class Task extends Model
{
    use HasFactory;

    protected $table = 'user_taskmanage';

    protected $primaryKey = 'task_id';

    public $timestamps = false;

    public static $rules = [
        'task_name' => 'required',
        'task_detail' => 'required',
        'task_start_datetime' => 'required|task_datetime',
        'task_end_datetime' => 'required|after_or_equal:task_start_datetime|task_datetime',
    ];

    public static $messages=[
            'task_name.required' => 'タスク名は必ず入力してください。',
            'task_detail.required' => 'タスクの詳細は必ず入力して下さい。',
            'task_start_datetime.required' => 'タスク開始日付・時間は必ず入力して下さい。',
            'task_start_datetime.task_datetime' => 'タスク開始日付には過去の日付を登録することはできません。',
            'task_end_datetime.required' => 'タスク終了日付・時間は必ず入力して下さい。',
            'task_end_datetime.after_or_equal' => 'タスク終了日付にはタスク開始日付・時間以降の日付を入力して下さい。',
            'task_end_datetime.task_datetime' => 'タスク終了日付には過去の日付を登録することはできません。',
    ];

    public static $task_param=[
        'user_id' => '',
        'task_id' => '',
        'task_name' => '',
        'task_detail' => '',
        'task_start_datetime' => '',
        'task_end_datetime' => '',
        'completed' => '',
    ];
}
