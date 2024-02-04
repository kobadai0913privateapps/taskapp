@extends('layouts.tasktop')

@section('content')
<div class="form-wrapper">
  <h1>パスワードを忘れた</h1>
  @if(session('login_errors'))
        <div style="color:red">※{{session('login_errors')}}</div>
  @endif  
  <form action="/pass/forget" method="post">
    @csrf
    <div class="form-item">
      <label for="email">登録されているメールアドレスを入力して下さい</label>
      @if($errors->has('user_email'))
          <div>
            <div style="color:red">※{{$errors->first('user_email')}}</div>
          </div>
      @endif
      <input type="email" name="user_email" value="{{old('user_email')}}"></input>
    </div>
    <div class="button-panel">
      <input type="submit" class="button" value="送信"></input>
    </div>
  </form>
  <div class="form-footer">
    <p><a href="/task/">ログインに戻る</a></p>
  </div>
</div>