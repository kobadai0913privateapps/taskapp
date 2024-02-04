<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Providers;

class User extends Model
{
    use HasFactory;

    protected $table = 'user';

    protected $primaryKey = 'user_id';

    public $timestamps = false;

    public static $rules = [
        'user_name' => 'required',
        'user_pass' => 'required',
        'user_email' => 'required|email',
    ];

    public static $messages=[
            'user_email.required' => 'メールアドレスは必ず入力してください。',
            'user_email.email' => 'メールアドレスは適切な書式で入力してください。',
            'user_pass.required' => 'パスワードは必ず入力して下さい。',
            'user_name.required' => 'ユーザ名は必ず入力して下さい。',
    ];

    public static $user_param = [
        'user_id' => '',
        'user_name' => '',
        'user_pass' => '',
        'user_email' => '',
        'admin' => '',
    ];
}
