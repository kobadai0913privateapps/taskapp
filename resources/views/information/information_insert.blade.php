@extends('layouts.taskapp')

@section('content')
<br>
<h1>インフォメーション追加画面</h1>

  <br>
  <form action="/information/add" method="post">  
    @csrf
    @if(count($errors)>0)
      <div class="alert alert-danger">{{session('information_errors')}}</div>
    @endif
    <table class="table table-bordered">
      <tr>
        <td><label for="name">インフォメーション日付</label>
        </td>
        <td>
          @if($errors->has('information_date'))
          <div>
            <div style="color:red">※{{$errors->first('information_date')}}</div>
          </div>
          @endif
          <input type="date" name="information_date" style="width: 190px;" value="{{old('information_date')}}">
        </td>
      </tr>
      <tr>
        <td><label for="name">インフォメーション名</label>
        </td>
        <td>
          @if($errors->has('information_name'))
          <div>
            <div style="color:red">※{{$errors->first('information_name')}}</div>
          </div>
          @endif
          <input type="text" name="information_name" placeholder="xxxxxxxx" value="{{old('information_name')}}">
        </td>
      </tr>
      <tr>
        <td><label for="name">インフォメーション詳細</label>
        </td>
        <td>
          @if($errors->has('information_detail'))
          <div>
            <div style="color:red">※{{$errors->first('information_detail')}}</div>
          </div>
          @endif
          <input type="text" name="information_detail" placeholder="xxxxxxxx" value="{{old('information_detail')}}">
        </td>
      </tr>
    </table>
  <br>
  <input type="submit" class="btn btn-primary" value="追加する" style="margin: 20px;">
  </form>
@endsection
