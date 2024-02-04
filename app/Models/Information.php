<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Providers;

class Information extends Model
{
    use HasFactory;

    protected $table = 'information_board';

    protected $primaryKey = 'information_id';

    public $timestamps = false;

    public static $rules = [
        'information_date' => 'required|after:yesterday',
        'information_detail' => 'required',
        'information_name' => 'required',
    ];

    public static $messages=[
        'information_name.required' => 'インフォメーション名は必ず入力して下さい。',
        'information_detail.required' => 'インフォメーション詳細は必ず入力して下さい。',
        'information_date.required' => 'インフォメーション日付は必ず入力して下さい。',
        'information_date.after' => 'インフォメーション日付には今日以降の日付を入力して下さい。',
    ];

    public static $information_param = [
        'information_id' => '',
        'information_name' => '',
        'information_date' => '',
        'information_detail' => '',
    ];
}
