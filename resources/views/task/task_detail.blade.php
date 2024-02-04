@extends('layouts.taskapp')

@section('content')
<br>
@if(session('completed_message'))
    <div class="alert alert-success">{{session('completed_message')}}</div>
@endif 
@if(session('incomplete_message'))
    <div class="alert alert-danger">{{session('incomplete_message')}}</div>
@endif 
@if(session('update_message'))
    <div class="alert alert-primary">{{session('update_message')}}</div>
@endif 
<h1>タスク詳細画面</h1>

  <br>
    <table class="table table-bordered">
      @foreach($tasks as $task)
      <tr>
        <td><label for="name">タスク名</label>
        </td>
        <td>
            <span>{{$task->task_name}}</span>
        </td>
      </tr>
      <tr>
        <td><label for="name">タスク詳細</label>
        </td>
        <td>
            <span>{{$task->task_detail}}</span>
        </td>
      </tr>
      <tr>
        <td><label for="name">タスク開始日付</label>
        </td>
        <td>
            <span>{{$task->task_start_datetime}}</span>
        </td>
      </tr>
      <tr>
        <td><label for="name">タスク終了日付</label>
        </td>
        <td>
            <span>{{$task->task_end_datetime}}</span>
        </td>
      </tr>
    </table>
    <br>
    <div>
        <a class="btn btn-danger" href="/task/delete/{{$task->task_id}}" role="button" style="margin: 20px;" name= "delete">削除</a>
        <a class="btn btn-primary" href="/task/fix/{{$task->task_id}}" role="button" style="margin: 20px;">修正</a>
        @if($task->completed != 'complete')
            <a class="btn btn-success" href="/task/success/{{$task->task_id}}" role="button" style="margin: 20px;">完了</a>
        @else
            <a class="btn btn-danger" href="/task/successdenger/{{$task->task_id}}" role="button" style="margin: 20px;">完了取り消し</a>
        @endif
    </div>
    @endforeach
@endsection
