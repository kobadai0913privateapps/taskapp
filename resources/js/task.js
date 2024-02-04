$(function () {
    $('#insert').click(function () {
      var count = document.querySelectorAll(".task_datetime").length;
  
      if (count >= 6) {
        document.getElementById("insert").setAttribute("disabled", true);
        var tr_form = "<font color='red' id='task_start'>※追加上限に達しました。5個まで追加可能です。</font><br>";
        $(tr_form).prependTo($('.task_date_append'));
      } else {
        var tr_form = '<div class="task_datetime" id="task_datetime'+''+count+'">'
                      +'<br>タスク開始日付'+''+count+'：<input type="datetime-local" id="task_start_datetime' + '' + count + '" name="task_start_datetime' + '' + count+ '" style="width: 190px;" value="{{old("task_start_datetime' + '' + count + '")}}">'
                      +'<br>タスク終了日付'+''+count+'：<input type="datetime-local" id="task_end_datetime' + '' + count + '" name="task_end_datetime' + '' + count + '" style="width: 190px;" value="{{old("task_end_datetime' + '' + count + '")}}">'
                      +'</div>';
        document.getElementById("task_datetime_counter").value = count;
        $(tr_form).appendTo($('.task_date_append'));
      }
  
      if (count >= 1) {
        var delete_document = document.getElementById("delete");
        delete_document.disabled = false;
        var status_document = document.getElementById("task_start_datetime_status");
        status_document.disabled = true;
      }
    });

    $('#delete').click(function () {
      var work_value = document.getElementById("task_datetime_counter").value;
      var work_length = document.querySelectorAll(".task_datetime").length-1;
      var count;
      if(work_value==null){
        count = work_length;
      }else{
        count = work_value;
      }
  
      if (count <= 0) {
        var delete_document = document.getElementById("delete");
        delete_document.disabled = true;
        var status_document = document.getElementById("task_start_datetime_status");
        status_document.disabled = false;
      } else {
        var delete_document = document.getElementById("insert");
        delete_document.disabled = false;
        var removeElem = document.getElementById("task_datetime" + '' + count);
        document.getElementById("task_datetime_counter").value = count - 1;
        removeElem.remove();
        removeElem = document.getElementById("task_start_datetime_" + '' + count);
        removeElem.remove();
        removeElem = document.getElementById("task_end_datetime_" + '' + count);
        removeElem.remove();
      }
    });
  
    $('#task_start_datetime_status').click(function () {
      if (document.getElementById('task_start_datetime_status').checked) {
        var start_document = document.getElementById("task_start_datetime");
        start_document.disabled = true;
        var insert_document = document.getElementById("insert");
        insert_document.disabled = true;
      } else {
        var start_document = document.getElementById("task_start_datetime");
        start_document.disabled = false;
        var insert_document = document.getElementById("insert");
        insert_document.disabled = false;
      }
  
    });
    $('#task_date_findflg').click(function () {
      if (document.getElementById('task_date_findflg').checked) {
        var check_document = document.getElementById("task_find_date");
        check_document.disabled = false;
      } else {
        check_document = document.getElementById("task_find_date");
        check_document.disabled = true;
      }
    });

    $('#task_month_findflg').click(function () {
      if (document.getElementById('task_month_findflg').checked) {
        var check_document = document.getElementById("task_find_month");
        check_document.disabled = false;
      } else {
        check_document = document.getElementById("task_find_month");
        check_document.disabled = true;
      }
    });

    $('#task_time_findflg').click(function () {
      if (document.getElementById('task_time_findflg').checked) {
        var check_document = document.getElementById("task_find_time");
        check_document.disabled = false;
      } else {
        check_document = document.getElementById("task_find_time");
        check_document.disabled = true;
      }
    });
    
    $('#task_name_findflg').click(function () {
      if (document.getElementById('task_name_findflg').checked) {
        var check_document = document.getElementById("task_find_name");
        check_document.disabled = false;
      } else {
        check_document = document.getElementById("task_find_name");
        check_document.disabled = true;
      }
    });
  });