@extends('layouts.taskapp')

@section('content')
<br>
@if(session('informationupdate_message'))
    <div class="alert alert-primary">{{session('informationupdate_message')}}</div>
    @endif 
@foreach($informations as $information)
  <p>【更新日付】{{$information->information_date}}</p>
  <p>【更新タイトル】{{$information->information_name}}</p>
  <p>【更新内容】</p>
  <p>{!!$information->information_detail!!}</p>
  <br>
  @if(session('admin') == 'admin')
  <div>
    <a class="btn btn-danger" href="/information/delete/{{$information->information_id}}" role="button" style="margin: 20px;" name= "delete">削除</a>
    <a class="btn btn-primary" href="/information/fix/{{$information->information_id}}" role="button" style="margin: 20px;">修正</a>
  </div>
  @endif
@endforeach
<br>

@endsection
