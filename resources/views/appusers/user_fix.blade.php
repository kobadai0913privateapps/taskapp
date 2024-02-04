@extends('layouts.taskapp')

@section('content')
<br>
<h1>ユーザ修正画面</h1>

  <br>
  @foreach($users as $user)
  <form action="/user/fix/{{$user->user_id}}" method="post">  
    @if(count($errors)>0)
      <div class="alert alert-danger">{{session('user_errors')}}</div>
    @endif
  @csrf
  <table class="table table-bordered">
      <tr>
        <td><label for="name">ユーザ名</label>
        </td>
        <td>
          @if($errors->has('user_name'))
          <div>
            <div style="color:red">※{{$errors->first('user_name')}}</div>
          </div>
          @endif
          <input type="text" name="user_name" value={{$user->user_name}}>
        </td>
      </tr>
      <tr>
        <td><label for="name">パスワード</label>
        </td>
        <td>
          @if($errors->has('user_pass'))
          <div>
            <div style="color:red">※{{$errors->first('user_pass')}}</div>
          </div>
          @endif
          <input type="password" name="user_pass" value={{$user->user_pass}}>
        </td>
      </tr>
      <tr>
        <td><label for="name">emailアドレス</label>
        </td>
        <td>
          @if($errors->has('user_email'))
          <div>
            <div style="color:red">※{{$errors->first('user_email')}}</div>
          </div>
          @endif
          <input type="email" name="user_email" value={{$user->user_email}}>
        </td>
      </tr>
      <tr>
        <td><label for="name">権限</label>
        </td>
        <td>
        <div class="authority">
          @if($user->admin=='admin')
            <input type="radio" name="authority" value="user_authority">ユーザ</input>
            <input type="radio" name="authority" value="admin_authority" checked>管理者</input>
          @else
            <input type="radio" name="authority" value="user_authority" checked>ユーザ</input>
            <input type="radio" name="authority" value="admin_authority">管理者</input>
          @endif
        </div>
        </td>
      </tr>
      @endforeach
    </table>
  <br>
  <input type="submit" class="btn btn-primary" value="修正する" style="margin: 20px;">
  </form>
@endsection
