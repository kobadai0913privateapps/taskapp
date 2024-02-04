<nav class="navbar navbar-expand-lg navbar-light pl-5 pr-5 pt-2 pb-2">
    <a class="navbar-brand text-white" href="/task/app">タスク管理アプリ</a>
    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#Navber" aria-controls="Navber" aria-expanded="false" aria-label="ナビゲーションの切替">
      <span class="navbar-toggler-icon"></span>
    </button>
  
    <div class="collapse navbar-collapse" id="Navber">
  
      <ul class="navbar-nav ml-auto mr-5">
        @if(session('admin_flg') == true)
          <li class="nav-item ml-2">
            <a class="nav-link text-white" id="register" href="/administrator/">ユーザ一覧画面へ</a>
          </li>
        @endif
        <li class="nav-item ml-2">
          <a class="nav-link text-white" id="register" href="/task/">ログアウト</a>
        </li>
      </ul>
    </div>
  </nav>
  
  