@extends('layouts.taskapp')

@section('content')
<br>
<h1>タスク修正画面</h1>

  <br>
  @foreach($tasks as $task)
  <form action="/task/fix/{{$task->task_id}}" method="post">  
    @if(count($errors)>0)
      <div class="alert alert-danger">{{session('task_errors')}}</div>
    @endif
  @csrf
  <table class="table table-bordered">
      <tr>
        <td><label for="name">タスク名</label>
        </td>
        <td>
          @if($errors->has('task_name'))
          <div>
            <div style="color:red">※{{$errors->first('task_name')}}</div>
          </div>
          @endif
          <input type="text" name="task_name" value={{$task->task_name}}>
        </td>
      </tr>
      <tr>
        <td><label for="name">タスク詳細</label>
        </td>
        <td>
          @if($errors->has('task_detail'))
          <div>
            <div style="color:red">※{{$errors->first('task_detail')}}</div>
          </div>
          @endif
          <input type="text" name="task_detail" value={{$task->task_detail}}>
        </td>
      </tr>
      <tr>
        <td><label for="name">タスク開始日付</label>
        </td>
        <td>
          @if($errors->has('task_start_datetime'))
          <div>
            <div style="color:red">※{{$errors->first('task_start_datetime')}}</div>
          </div>
          @endif
          @if($task->completed == 'deadline_incomplete')
            <input type="datetime-local" style="width: 190px;" id="task_start_datetime" name="task_start_datetime" disabled value={{$task->task_start_datetime}}>
            <div>
              <input type="checkbox" id="task_start_datetime_status" name="task_start_datetime_status" value='true' checked>
              <label for="scales">開始日付は入力しない</label>
            </div>
          @else
            <input type="datetime-local" style="width: 190px;" id="task_start_datetime" name="task_start_datetime" value={{$task->task_start_datetime}}>
            <div>
              <input type="checkbox" id="task_start_datetime_status" name="task_start_datetime_status" value='true'>
              <label for="scales">開始日付は入力しない</label>
            </div>
          @endif
        </td>
      </tr>
      <tr>
        <td><label for="name">タスク終了日付</label>
        </td>
        <td>
          @if($errors->has('task_end_datetime'))
          <div>
            <div style="color:red">※{{$errors->first('task_end_datetime')}}</div>
          </div>
          @endif
          <input type="datetime-local" style="width: 190px;" name="task_end_datetime" value={{$task->task_end_datetime}}>
        </td>
      </tr>
      @endforeach
    </table>
  <br>
  <input type="submit" class="btn btn-primary" value="修正する" style="margin: 20px;">
  </form>
@endsection
