@extends('layouts.taskapp')

@section('content')
    <br>    
    @if(session('delete_message'))
        <div class="alert alert-danger">{{session('delete_message')}}</div>
    @endif 
    @if(session('insert_message'))
        <div class="alert alert-success">{{session('insert_message')}}</div>
    @endif 
    @if(session('csvoutput_message'))
        <div class="alert alert-primary">{{session('csvoutput_message')}}</div>
    @endif 
    @if(session('sendmail_message'))
        <div class="alert alert-success">{{session('sendmail_message')}}</div>
    @endif 
    @if(session('informationinsert_message'))
        <div class="alert alert-success">{{session('informationinsert_message')}}</div>
    @endif 
    @if(session('informationdelete_message'))
        <div class="alert alert-danger">{{session('informationdelete_message')}}</div>
    @endif 
    <details>
        <summary class="card-header">インフォメーションボード</summary>
                    @foreach($informations as $information)
                        @if(($information->{$user_name."_flg"})==false)
                                <dl>
                                    <dt><a class="new">NEW!</a>{{ $information->information_date }}更新</dt>
                                    <dd><a href="/information/detail/{{$information->information_id}}">{{ $information->information_name }}</a></dd>
                                </dl>
                        @else
                                <dl>
                                    <dt>{{ $information->information_date }}更新</dt>
                                    <dd><a href="/information/detail/{{$information->information_id}}">{{ $information->information_name }}</a></dd>
                                </dl>
                        @endif
                    @endforeach
    </details>
        @if(session('admin') == 'admin')
            <h5 class="card-header">ユーザのタスクの一覧</h5>  
                <details>
                    <summary class="card-header">絞り込み検索</summary>
                    <form action="/task/find" method="post">
                        @csrf
                        <table class="table table-hover">
                                <tbody>
                                    <tr>
                                        <td>タスクステータス</td>
                                        <td>
                                            <input type="checkbox" name="task_status_excess" value="true">未完了</input>
                                        </td>
                                        <td>
                                            <input type="checkbox" name="task_status_complete" value="true">完了</input>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>日付</td>
                                        <td>
                                            <input type="checkbox" name="task_today_flg" value="true">今日</input>
                                        </td>
                                        <td>
                                            <input type="checkbox" id="task_month_findflg" name="task_month_findflg" value="true">月：</input>
                                            <input type="month" id="task_find_month" name="task_find_month" style="width: 190px;" value="{{old('task_find_month')}}" disabled>
                                        </td>
                                        <td>
                                            <input type="checkbox" id="task_date_findflg" name="task_date_findflg" value="true">月日：</input>
                                            <input type="date" id="task_find_date" name="task_find_date" style="width: 190px;" value="{{old('task_find_date')}}" disabled>
                                        </td>
                                        <td>
                                            <input type="checkbox" id="task_time_findflg" name="task_time_findflg" value="true">時間：</input>
                                            <input type="time"  id="task_find_time" name="task_find_time" style="width: 190px;" value="{{old('task_find_time')}}" disabled>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>タスク名</td>
                                        <td>
                                            <input type="checkbox" id="task_name_findflg" name="task_name_findflg" value="true"></input>
                                            <input type="text"  id="task_find_name" name="task_find_name" style="width: 190px;" value="{{old('task_name_find')}}" placeholder="XXXXXXXX" disabled>
                                        </td>
                                    </tr>
                                </tbody>
                        </table>
                        <input type="submit" name="push_search" class="btn btn-primary" value="検索" style="margin: 20px;">
                        </form>
                </details>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">ユーザID</th>
                        <th scope="col">タスク名</th>
                        <th scope="col">タスク詳細</th>
                        <th scope="col">タスク開始日付</th>
                        <th scope="col">タスク終了日付</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody class="task">
                    @foreach($tasks as $task)
                        @if($task->completed == "excess_incomplete")
                            <tr class="excess">
                                <td></td>
                                <td>{{ $task->user_id }}</a></td>
                                <td>&#x26a0;{{ $task->task_name }}</a></td>
                                <td>{{ $task->task_detail }}</td>
                                <td>{{ $task->task_start_datetime }}</td>
                                <td>{{ $task->task_end_datetime }}</td>
                            </tr>
                        @elseif($task->completed == "today_incomplete")
                            <tr class="successd">
                                <td></td>
                                <td>{{ $task->user_id }}</a></td>
                                <td>　　{{ $task->task_name }}</a></td>
                                <td>{{ $task->task_detail }}</td>
                                <td>{{ $task->task_start_datetime }}</td>
                                <td>{{ $task->task_end_datetime }}</td>
                            </tr>
                        @elseif($task->completed == "deadline_incomplete")
                            <tr class="deadline">
                                <td></td>
                                <td>{{ $task->user_id }}</a></td>
                                <td>&#x23F1;{{ $task->task_name }}</a></td>
                                <td>{{ $task->task_detail }}</td>
                                <td>{{ $task->task_start_datetime }}</td>
                                <td>{{ $task->task_end_datetime }}</td>
                            </tr>
                        @else
                            <tr>
                            <td></td>
                                <td>{{ $task->user_id }}</a></td>
                                <td>　　{{ $task->task_name }}</a></td>
                                <td>{{ $task->task_detail }}</td>
                                <td>{{ $task->task_start_datetime }}</td>
                                <td>{{ $task->task_end_datetime }}</td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
        @else
            <h5 class="card-header">タスク一覧</h5>  
                <details>
                    <summary class="card-header">絞り込み検索</summary>
                    <form action="/task/find" method="post">
                        @csrf
                        <table class="table table-hover">
                                <tbody>
                                    <tr>
                                        <td>タスクステータス</td>
                                        <td>
                                            <input type="checkbox" name="task_status_excess" value="true">未完了</input>
                                        </td>
                                        <td>
                                            <input type="checkbox" name="task_status_complete" value="true">完了</input>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>日付</td>
                                        <td>
                                            <input type="checkbox" name="task_today_flg" value="true">今日</input>
                                        </td>
                                        <td>
                                            <input type="checkbox" id="task_month_findflg" name="task_month_findflg" value="true">月：</input>
                                            <input type="month" id="task_find_month" name="task_find_month" style="width: 190px;" value="{{old('task_find_month')}}" disabled>
                                        </td>
                                        <td>
                                            <input type="checkbox" id="task_date_findflg" name="task_date_findflg" value="true">月日：</input>
                                            <input type="date" id="task_find_date" name="task_find_date" style="width: 190px;" value="{{old('task_find_date')}}" disabled>
                                        </td>
                                        <td>
                                            <input type="checkbox" id="task_time_findflg" name="task_time_findflg" value="true">時間：</input>
                                            <input type="time"  id="task_find_time" name="task_find_time" style="width: 190px;" value="{{old('task_find_time')}}" disabled>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>タスク名</td>
                                        <td>
                                            <input type="checkbox" id="task_name_findflg" name="task_name_findflg" value="true"></input>
                                            <input type="text"  id="task_find_name" name="task_find_name" style="width: 190px;" value="{{old('task_name_find')}}" placeholder="XXXXXXXX" disabled>
                                        </td>
                                    </tr>
                                </tbody>
                        </table>
                        <input type="submit" name="push_search" class="btn btn-primary" value="検索" style="margin: 20px;">
                        </form>
                </details>
            <table class="table table-hover">
                <thead>
                <tr>
                        <th scope="col">No</th>
                        <th scope="col">タスク名</th>
                        <th scope="col">タスク詳細</th>
                        <th scope="col">タスク開始日付</th>
                        <th scope="col">タスク終了日付</th>
                </tr>
                </thead>
                <tbody class="task">
                @foreach($tasks as $task)
                    @if($task->completed == "excess_incomplete")
                        <tr class="excess">
                            <td></td>
                            <td><a href="/task/detail/{{$task->task_id}}">&#x26a0;{{ $task->task_name }}</a></td>
                            <td>{{ $task->task_detail }}</td>
                            <td>{{ $task->task_start_datetime }}</td>
                            <td>{{ $task->task_end_datetime }}</td>
                        </tr>
                    @elseif($task->completed == "today_incomplete")
                        <tr class="successd">
                            <td></td>
                            <td><a href="/task/detail/{{$task->task_id}}">　　{{ $task->task_name }}</a></td>
                            <td>{{ $task->task_detail }}</td>
                            <td>{{ $task->task_start_datetime }}</td>
                            <td>{{ $task->task_end_datetime }}</td>
                        </tr>
                    @elseif($task->completed == "deadline_incomplete")
                        <tr class="deadline">
                            <td></td>
                            <td><a href="/task/detail/{{$task->task_id}}">&#x23F1;{{ $task->task_name }}</a></td>
                            <td>{{ $task->task_detail }}</td>
                            <td>{{ $task->task_start_datetime }}</td>
                            <td>{{ $task->task_end_datetime }}</td>
                        </tr>
                    @else
                        <tr>
                            <td></td>
                            <td><a href="/task/detail/{{$task->task_id}}">　　{{ $task->task_name }}</a></td>
                            <td>{{ $task->task_detail }}</td>
                            <td>{{ $task->task_start_datetime }}</td>
                            <td>{{ $task->task_end_datetime }}</td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        @endif
        {{$tasks->appends(request()->input())->render()}}
    @if(session('admin') != 'admin')
        <a class="btn btn-primary" href="/task/add" role="button" style="margin: 20px;">タスクを追加する</a>
    @endif
    @if(session('admin') == 'admin')
        <a class="btn btn-primary" href="/information/add" role="button" style="margin: 20px;">インフォメーションを追加する</a>
    @endif
    @if(empty($tasks))
        <a class="btn btn-primary" href="/task/csv" style="margin: 20px;" disabled>CSV出力</a>
    @else
        <a class="btn btn-primary" href="/task/csv" style="margin: 20px;">CSV出力</a>
    @endif
@endsection
