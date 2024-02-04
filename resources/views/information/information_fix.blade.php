@extends('layouts.taskapp')

@section('content')
<br>
<h1>インフォメーション修正画面</h1>

  <br>
  @foreach($informations as $information)
  <form action="/information/fix/{{$information->information_id}}" method="post">  
    @if(count($errors)>0)
      <div class="alert alert-danger">{{session('information_errors')}}</div>
    @endif
  @csrf
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
          <input type="date" name="information_date" style="width: 190px;" value="{{$information->information_date}}">
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
          <input type="text" name="information_name" placeholder="xxxxxxxx" value="{{$information->information_name}}">
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
          <input type="text" name="information_detail" placeholder="xxxxxxxx" value="{{$information->information_detail}}">
        </td>
      </tr>
      @endforeach
    </table>
  <br>
  <input type="submit" class="btn btn-primary" value="修正する" style="margin: 20px;">
  </form>
@endsection
