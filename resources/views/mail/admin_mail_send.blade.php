<!DOCTYPE html>
<html lang="ja">
    <style>
    </style>
    <body>
        <p>タスク管理アプリ管理者様へ</p></br>
        <p>現時点でのユーザ毎の当日タスク・未完了タスクをご報告いたします。</p>
        <p>未完了タスクがある場合はユーザ様に確認の方をお願いします。</p>
        <br>
        <p>本日のタスクは下記の通りです。</p>
        @if(!empty($today_task))
            <table>
                <thead>
                <tr>
                    <th>No</th>
                    <th>ユーザID</th>
                    <th>ユーザ名</th>
                    <th>emailアドレス</th>
                    <th>タスク名</th>
                    <th>タスク詳細</th>
                    <th>タスク開始日付</th>
                    <th>タスク終了日付</th>
                </tr>
                </thead>
                <tbody>
                @foreach($today_task as $task)
                        <tr>
                            <td>{{ $task[0] }}</td>
                            <td>{{ $task[5] }}</td>
                            <td>{{ $task[6] }}</td>
                            <td>{{ $task[7] }}</td>
                            <td>{{ $task[1] }}</td>
                            <td>{{ $task[2] }}</td>
                            <td>{{ $task[3] }}</td>
                            <td>{{ $task[4] }}</td>
                        </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <p>本日のタスクはありません。</p>
        @endif
        <br>
        <br>
        <p>本日以前の未完了タスクは下記の通りです。</p>
        @if(!empty($old_task))
            <table>
                <thead>
                <tr>
                    <th>No</th>
                    <th>ユーザID</th>
                    <th>ユーザ名</th>
                    <th>emailアドレス</th>
                    <th>タスク名</th>
                    <th>タスク詳細</th>
                    <th>タスク開始日付</th>
                    <th>タスク終了日付</th>
                </tr>
                </thead>
                <tbody>
                @foreach($old_task as $task)
                        <tr>
                            <td>{{ $task[0] }}</td>
                            <td>{{ $task[5] }}</td>
                            <td>{{ $task[6] }}</td>
                            <td>{{ $task[7] }}</td>
                            <td>{{ $task[1] }}</td>
                            <td>{{ $task[2] }}</td>
                            <td>{{ $task[3] }}</td>
                            <td>{{ $task[4] }}</td>
                        </tr>
                @endforeach
                </tbody>
            </table>
        @else   
            <p>未完了タスクはありません。</p>
        @endif
        </br>
        <p>リンク：https://taskdvapp.herokuapp.com</p>
        <br>
        <p>タスク管理アプリ事務局</p>
    </body>
</html>