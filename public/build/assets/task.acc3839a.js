$(function(){$("#insert").click(function(){var e=document.querySelectorAll(".task_datetime").length;if(e>=6){document.getElementById("insert").setAttribute("disabled",!0);var t="<font color='red' id='task_start'>\u203B\u8FFD\u52A0\u4E0A\u9650\u306B\u9054\u3057\u307E\u3057\u305F\u30025\u500B\u307E\u3067\u8FFD\u52A0\u53EF\u80FD\u3067\u3059\u3002</font><br>";$(t).prependTo($(".task_date_append"))}else{var t='<div class="task_datetime" id="task_datetime'+e+'"><br>\u30BF\u30B9\u30AF\u958B\u59CB\u65E5\u4ED8'+e+'\uFF1A<input type="datetime-local" id="task_start_datetime'+e+'" name="task_start_datetime'+e+'" style="width: 190px;" value="{{old("task_start_datetime'+e+'")}}"><br>\u30BF\u30B9\u30AF\u7D42\u4E86\u65E5\u4ED8'+e+'\uFF1A<input type="datetime-local" id="task_end_datetime'+e+'" name="task_end_datetime'+e+'" style="width: 190px;" value="{{old("task_end_datetime'+e+'")}}"></div>';document.getElementById("task_datetime_counter").value=e,$(t).appendTo($(".task_date_append"))}if(e>=1){var d=document.getElementById("delete");d.disabled=!1;var a=document.getElementById("task_start_datetime_status");a.disabled=!0}}),$("#delete").click(function(){var e=document.getElementById("task_datetime_counter").value,t=document.querySelectorAll(".task_datetime").length-1,d;if(e==null?d=t:d=e,d<=0){var a=document.getElementById("delete");a.disabled=!0;var s=document.getElementById("task_start_datetime_status");s.disabled=!1}else{var a=document.getElementById("insert");a.disabled=!1;var n=document.getElementById("task_datetime"+d);document.getElementById("task_datetime_counter").value=d-1,n.remove(),n=document.getElementById("task_start_datetime_"+d),n.remove(),n=document.getElementById("task_end_datetime_"+d),n.remove()}}),$("#task_start_datetime_status").click(function(){if(document.getElementById("task_start_datetime_status").checked){var e=document.getElementById("task_start_datetime");e.disabled=!0;var t=document.getElementById("insert");t.disabled=!0}else{var e=document.getElementById("task_start_datetime");e.disabled=!1;var t=document.getElementById("insert");t.disabled=!1}}),$("#task_date_findflg").click(function(){if(document.getElementById("task_date_findflg").checked){var e=document.getElementById("task_find_date");e.disabled=!1}else e=document.getElementById("task_find_date"),e.disabled=!0}),$("#task_month_findflg").click(function(){if(document.getElementById("task_month_findflg").checked){var e=document.getElementById("task_find_month");e.disabled=!1}else e=document.getElementById("task_find_month"),e.disabled=!0}),$("#task_time_findflg").click(function(){if(document.getElementById("task_time_findflg").checked){var e=document.getElementById("task_find_time");e.disabled=!1}else e=document.getElementById("task_find_time"),e.disabled=!0}),$("#task_name_findflg").click(function(){if(document.getElementById("task_name_findflg").checked){var e=document.getElementById("task_find_name");e.disabled=!1}else e=document.getElementById("task_find_name"),e.disabled=!0})});
