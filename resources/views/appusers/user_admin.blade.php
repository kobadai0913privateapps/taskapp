@extends('layouts.taskapp')

@section('content')
    <br>    
    @if(session('delete_message'))
        <div class="alert alert-danger">{{session('delete_message')}}</div>
    @endif 
    @if(session('update_message'))
        <div class="alert alert-primary">{{session('update_message')}}</div>
    @endif 
    <h5 class="card-header">ユーザ一覧</h5>  
    @if(session('userdeleteerror_message'))
        <div style="color:red">※{{session('userdeleteerror_message')}}</div>
    @endif  
        <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col">ユーザID</th>
                <th scope="col">ユーザ名</th>
                <th scope="col">パスワード</th>
                <th scope="col">e-mailアドレス</th>
                <th scope="col">権限</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($userdata as $data)
                <tr>
                    <td>{{ $data->user_id }}</a></td>
                    <td>{{ $data->user_name }}</td>
                    <td>{{ $data->user_pass }}</td>
                    <td>{{ $data->user_email }}</td>
                    <td>{{ $data->admin }}</td>
                    <td>
                        <div>
                        <a class="btn btn-danger" href="/user/delete/{{$data->user_id}}" role="button" style="margin: 20px;">削除</a>
                        <a class="btn btn-primary" href="/user/fix/{{$data->user_id}}" role="button" style="margin: 20px;">修正</a>
                        @if($data->admin != "admin")
                            <a class="btn btn-primary" href="/login/admin/user/{{$data->user_id}}" role="button" style="margin: 20px;">ログイン</a>
                        @endif
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
@endsection
