<!DOCTYPE html>
<html lang="ja">
    <style>
    </style>
    <body>
        <p>平素よりタスク管理アプリをご利用いただきありがとうございます。</p></br>
        <p>現時点での未完了タスクをご報告いたします。</p>
        <br>
        <p>本日のタスクは下記の通りです。</p>
        @if(!empty($today_task))
            <table>
                <thead>
                <tr>
                    <th>No</th>
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
            <p>※なおこの未完了タスクについては、メールの最後に記載されているリンクより、完了変更するか、日程をずらしていただきますようお願いいたします。</p>
            <table>
                <thead>
                <tr>
                    <th>No</th>
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