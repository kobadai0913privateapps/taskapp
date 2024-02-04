@extends('layouts.tasktop')

@section('content')
<div class="form-wrapper">
  <h1>管理者としてログイン</h1>
  @if(session('login_errors'))
        <div style="color:red">※{{session('login_errors')}}</div>
  @endif 
  @if(session('insert_message'))
        <div style="color:blue">※{{session('insert_message')}}</div>
  @endif 
  <form action="/login/admin" method="post">
    @csrf
    <div class="form-item">
      <label for="email">E-Mail</label>
      @if($errors->has('user_email'))
          <div>
            <div style="color:red">※{{$errors->first('user_email')}}</div>
          </div>
      @endif
      <input type="email" name="user_email" value="{{old('user_email')}}"></input>
    </div>
    <div class="form-item">
      <label for="password">Password</label>
        @if($errors->has('user_pass'))
          <div>
            <div style="color:red">※{{$errors->first('user_pass')}}</div>
          </div>
        @endif
      <input type="password" name="user_pass" value="{{old('user_pass')}}"></input>
    </div>
    <div class="button-panel">
      <input type="submit" class="button" value="ログイン"></input>
    </div>
    <div class="form-footer">
    <p><a href="/task/">ログインに戻る</a></p>
  </div>
  </form>
</div>