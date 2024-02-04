@extends('layouts.tasktop')

@section('content')
<div class="form-wrapper">
  <h1>パスワード変更フォーム</h1>
  @if(session('login_errors'))
        <div style="color:red">※{{session('login_errors')}}</div>
  @endif 
  <form action="/pass_forgeted/{{$user_email}}" method="post">
    @csrf
    <div class="form-item">
      <label for="user_password">パスワード</label>
        @if($errors->has('user_pass'))
          <div>
            <div style="color:red">※{{$errors->first('user_pass')}}</div>
          </div>
        @endif
      <input type="password" name="user_pass" value="{{old('user_pass')}}"></input>
    </div>
    <div class="button-panel">
      <input type="submit" class="button" value="変更"></input>
    </div>
    <br>
  </form>
</div>