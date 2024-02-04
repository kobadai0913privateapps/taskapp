@extends('layouts.taskapp')
@section('content')
<br>
<h1>タスク追加画面</h1>
  <br>
  <form action="/task/add" method="post">  
    @csrf
    @if(count($errors)>0)
      <div class="alert alert-danger">{{session('task_errors')}}</div>
    @endif
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
          <input type="text" name="task_name" placeholder="xxxxxxxx" value="{{old('task_name')}}">
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
          <input type="text" name="task_detail" placeholder="xxxxxxxx" value="{{old('task_detail')}}">
        </td>
      </tr>
      <tr>
        <td><label for="name">タスク日付</label>
        </td>
        <td>
          <div class="task_date_append">
            @if($errors->has('task_start_datetime'))
            <div>
              <div style="color:red">※{{$errors->first('task_start_datetime')}}</div>
            </div>
            @endif
            @if($errors->has('task_end_datetime'))
            <div>
              <div style="color:red">※{{$errors->first('task_end_datetime')}}</div>
            </div>
            @endif
            <div class="task_datetime">
              タスク開始日付：<input type="datetime-local" id="task_start_datetime" name="task_start_datetime" style="width: 190px;" value="{{old('task_start_datetime')}}">
              <div>
              @if(session('task_datetime_counter')>=1)
                <input type="checkbox" id="task_start_datetime_status" name="task_start_datetime_status" value='true' disabled>
              @else
                <input type="checkbox" id="task_start_datetime_status" name="task_start_datetime_status" value='true'>
              @endif
                <label for="scales">開始日付は入力しない</label>
              </div>
              タスク終了日付：<input type="datetime-local" id="task_end_datetime" name="task_end_datetime" style="width: 190px;" value="{{old('task_end_datetime')}}">
            </div>
            <br>
            @if(session('task_datetime_counter'))
              @for($i=0; $i<session('task_datetime_counter'); $i++)
                @if($i==0)
                  @if($errors->has('task_start_datetime'.($i+1)))
                    <div id="task_start_datetime_1">
                      <div style="color:red">※{{$errors->first('task_start_datetime'.($i+1))}}</div>
                    </div>
                  @endif
                  @if($errors->has('task_end_datetime'.($i+1)))
                    <div id="task_end_datetime_1">
                      <div style="color:red">※{{$errors->first('task_end_datetime'.($i+1))}}</div>
                    </div>
                  @endif
                  <div class="task_datetime" id="task_datetime1">
                    タスク開始日付1：<input type="datetime-local" name="task_start_datetime1" style="width: 190px;" value="{{old('task_start_datetime1')}}">
                    <br>
                    タスク終了日付1：<input type="datetime-local" name="task_end_datetime1" style="width: 190px;" value="{{old('task_end_datetime1')}}">
                  </div>
                @elseif($i==1)
                  <br>
                  @if($errors->has('task_start_datetime'.($i+1)))
                    <div id="task_start_datetime_2">
                      <div style="color:red">※{{$errors->first('task_start_datetime'.($i+1))}}</div>
                    </div>
                  @endif
                  @if($errors->has('task_end_datetime'.($i+1)))
                    <div id="task_end_datetime_2">
                      <div style="color:red">※{{$errors->first('task_end_datetime'.($i+1))}}</div>
                    </div>
                  @endif
                  <div class="task_datetime" id="task_datetime2">
                    タスク開始日付2：<input type="datetime-local" name="task_start_datetime2" style="width: 190px;" value="{{old('task_start_datetime2')}}">
                    <br>
                    タスク終了日付2：<input type="datetime-local" name="task_end_datetime2" style="width: 190px;" value="{{old('task_end_datetime2')}}">
                  </div>
                @elseif($i==2)
                  <br>
                  @if($errors->has('task_start_datetime'.($i+1)))
                    <div id="task_start_datetime_3">
                      <div style="color:red">※{{$errors->first('task_start_datetime'.($i+1))}}</div>
                    </div>
                  @endif
                  @if($errors->has('task_end_datetime'.($i+1)))
                    <div id="task_end_datetime_3">
                      <div style="color:red">※{{$errors->first('task_end_datetime'.($i+1))}}</div>
                    </div>
                  @endif
                  <div class="task_datetime" id="task_datetime3">
                    タスク開始日付3：<input type="datetime-local" name="task_start_datetime3" style="width: 190px;" value="{{old('task_start_datetime3')}}">
                    <br>
                    タスク終了日付3：<input type="datetime-local" name="task_end_datetime3" style="width: 190px;" value="{{old('task_end_datetime3')}}">
                  </div>
                @elseif($i==3)
                  <br>
                  @if($errors->has('task_start_datetime'.($i+1)))
                    <div id="task_start_datetime_4">
                      <div style="color:red">※{{$errors->first('task_start_datetime'.($i+1))}}</div>
                    </div>
                  @endif
                  @if($errors->has('task_end_datetime'.($i+1)))
                    <div id="task_end_datetime_4">
                      <div style="color:red">※{{$errors->first('task_end_datetime'.($i+1))}}</div>
                    </div>
                  @endif
                  <div class="task_datetime" id="task_datetime4">
                    タスク開始日付4：<input type="datetime-local" name="task_start_datetime4" style="width: 190px;" value="{{old('task_start_datetime4')}}">
                    <br>
                    タスク終了日付4：<input type="datetime-local" name="task_end_datetime4" style="width: 190px;" value="{{old('task_end_datetime4')}}">
                  </div>
                @elseif($i==4)
                  <br>
                  @if($errors->has('task_start_datetime'.($i+1)))
                    <div id="task_start_datetime_5">
                      <div style="color:red">※{{$errors->first('task_start_datetime'.($i+1))}}</div>
                    </div>
                  @endif
                  @if($errors->has('task_end_datetime'.($i+1)))
                    <div id="task_end_datetime_5">
                      <div style="color:red">※{{$errors->first('task_end_datetime'.($i+1))}}</div>
                    </div>
                  @endif
                  <div class="task_datetime" id="task_datetime5">
                    タスク開始日付5：<input type="datetime-local" name="task_start_datetime5" style="width: 190px;" value="{{old('task_start_datetime5')}}">
                    <br>
                    タスク終了日付5：<input type="datetime-local" name="task_end_datetime5" style="width: 190px;" value="{{old('task_end_datetime5')}}">
                  </div>
                @endif
              @endfor
            @endif
          </div>
          <input type="hidden" id="task_datetime_counter" name="task_datetime_counter" value="{{session('task_datetime_counter')}}">
          <br>
          <div>
            @if(session('task_datetime_counter')>=5)
              <input type="button" id="insert" class="btn btn-primary" value="追加" disabled>
            @else
              <input type="button" id="insert" class="btn btn-primary" value="追加">
            @endif
            @if(session('task_datetime_counter')<=0)
              <input type="button" id="delete" class="btn btn-danger" value="削除" disabled>
            @else
              <input type="button" id="delete" class="btn btn-danger" value="削除">
            @endif
          </div>
      </tr>
    </table>
  <br>
  <input type="submit" class="btn btn-primary" value="追加する" style="margin: 20px;">
  </form>
@endsection
