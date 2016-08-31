"use strict";!function(o){o(window).load(function(){o(".log-table-view").each(function(){o(this).scrollTop(o("table",this).height())}),o(".page-title-action").click(function(e){e.preventDefault(),o("#log-dialog").dialog("open")}),o("#log-dialog").dialog({title:"Add New Log",dialogClass:"wp-dialog",autoOpen:!1,draggable:!1,width:"auto",modal:!0,resizable:!1,closeOnEscape:!0,position:{my:"center",at:"center",of:window},create:function(){o(".ui-dialog-titlebar-close").addClass("ui-button")},open:function(){o(".ui-widget-overlay").bind("click",function(){o("#log-dialog").dialog("close")})}})}),o(".log-table-view").on("scroll",function(e){var t=o(this);if(0==t.scrollTop()&&0==t.find(".overlay").length){var l=o('<div class="overlay"><div>');t.append(l);var i=(o("td",this).length,{action:"fetch_log_rows",logfile:t.data("logfile"),offset:o("td",this).length,regex:t.data("regex"),cutoff_bytes:t.data("logbytes")});console.log(i),o.post(window.ajaxurl,i,function(e){var i=o("table",t).height();o("tbody",t).prepend(e),t.scrollTop(o("table",t).height()-i),l.remove()})}})}(jQuery);
//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndwLXNlcnZlci1sb2ctdmlld2VyLmpzIl0sIm5hbWVzIjpbIiQiLCJ3aW5kb3ciLCJsb2FkIiwiZWFjaCIsInRoaXMiLCJzY3JvbGxUb3AiLCJoZWlnaHQiLCJjbGljayIsImUiLCJwcmV2ZW50RGVmYXVsdCIsImRpYWxvZyIsInRpdGxlIiwiZGlhbG9nQ2xhc3MiLCJhdXRvT3BlbiIsImRyYWdnYWJsZSIsIndpZHRoIiwibW9kYWwiLCJyZXNpemFibGUiLCJjbG9zZU9uRXNjYXBlIiwicG9zaXRpb24iLCJteSIsImF0Iiwib2YiLCJjcmVhdGUiLCJhZGRDbGFzcyIsIm9wZW4iLCJiaW5kIiwib24iLCIkdGhpcyIsImZpbmQiLCJsZW5ndGgiLCIkb3ZlcmxheSIsImFwcGVuZCIsInBheWxvYWQiLCJhY3Rpb24iLCJsb2dmaWxlIiwiZGF0YSIsIm9mZnNldCIsInJlZ2V4IiwiY3V0b2ZmX2J5dGVzIiwiY29uc29sZSIsImxvZyIsInBvc3QiLCJhamF4dXJsIiwicmVzcG9uc2UiLCJvbGRoZWlnaHQiLCJwcmVwZW5kIiwicmVtb3ZlIiwialF1ZXJ5Il0sIm1hcHBpbmdzIjoiQUFBQSxjQUVBLFNBQVVBLEdBQ1JBLEVBQUVDLFFBQVFDLEtBQUssV0FFYkYsRUFBRSxtQkFBbUJHLEtBQUssV0FDeEJILEVBQUVJLE1BQU1DLFVBQVVMLEVBQUUsUUFBU0ksTUFBTUUsWUFHckNOLEVBQUUsc0JBQXNCTyxNQUFNLFNBQVNDLEdBQ3JDQSxFQUFFQyxpQkFDTFQsRUFBRSxlQUFlVSxPQUFPLFVBR3ZCVixFQUFFLGVBQWVVLFFBQ2ZDLE1BQU8sY0FDUEMsWUFBYSxZQUNiQyxVQUFVLEVBQ1ZDLFdBQVcsRUFDWEMsTUFBTyxPQUNQQyxPQUFPLEVBQ1BDLFdBQVcsRUFDWEMsZUFBZ0IsRUFDaEJDLFVBQ0VDLEdBQUksU0FDSkMsR0FBSSxTQUNKQyxHQUFJckIsUUFFTnNCLE9BQVEsV0FDTnZCLEVBQUUsNkJBQTZCd0IsU0FBUyxjQUUxQ0MsS0FBTSxXQUNKekIsRUFBRSxzQkFBc0IwQixLQUFLLFFBQVEsV0FDbkMxQixFQUFFLGVBQWVVLE9BQU8sZ0JBT2hDVixFQUFFLG1CQUFtQjJCLEdBQUcsU0FBVSxTQUFTbkIsR0FDekMsR0FBSW9CLEdBQVE1QixFQUFFSSxLQUNkLElBQUksR0FBS3dCLEVBQU12QixhQUFlLEdBQUt1QixFQUFNQyxLQUFLLFlBQVlDLE9BQVMsQ0FDakUsR0FBSUMsR0FBVy9CLEVBQUUsNkJBQ2pCNEIsR0FBTUksT0FBUUQsRUFFZCxJQUNJRSxJQURTakMsRUFBRSxLQUFNSSxNQUFNMEIsUUFFekJJLE9BQVUsaUJBQ1ZDLFFBQVdQLEVBQU1RLEtBQUssV0FDdEJDLE9BQVVyQyxFQUFFLEtBQU1JLE1BQU0wQixPQUN4QlEsTUFBU1YsRUFBTVEsS0FBSyxTQUNwQkcsYUFBZ0JYLEVBQU1RLEtBQUssYUFFN0JJLFNBQVFDLElBQUlSLEdBQ1pqQyxFQUFFMEMsS0FBS3pDLE9BQU8wQyxRQUFTVixFQUFTLFNBQVNXLEdBQ3ZDLEdBQUlDLEdBQVk3QyxFQUFFLFFBQVM0QixHQUFPdEIsUUFDbENOLEdBQUUsUUFBUzRCLEdBQU9rQixRQUFRRixHQUMxQmhCLEVBQU12QixVQUFVTCxFQUFFLFFBQVM0QixHQUFPdEIsU0FBV3VDLEdBQzdDZCxFQUFTZ0IsZUFLZEMiLCJmaWxlIjoid3Atc2VydmVyLWxvZy12aWV3ZXIuanMiLCJzb3VyY2VzQ29udGVudCI6WyIndXNlIHN0cmljdCc7XG5cbihmdW5jdGlvbigkKSB7XG4gICQod2luZG93KS5sb2FkKGZ1bmN0aW9uKCkge1xuICAgIC8vIGF1dG8tc2Nyb2xsIHRvIGJvdHRvbSBvZiBsb2cgdmlld2VycyBvbiBwYWdlIGxvYWRcbiAgICAkKCcubG9nLXRhYmxlLXZpZXcnKS5lYWNoKGZ1bmN0aW9uKCkge1xuICAgICAgJCh0aGlzKS5zY3JvbGxUb3AoJCgndGFibGUnLCB0aGlzKS5oZWlnaHQoKSlcbiAgICB9KTtcblxuICAgICQoJy5wYWdlLXRpdGxlLWFjdGlvbicpLmNsaWNrKGZ1bmN0aW9uKGUpIHtcbiAgICAgIGUucHJldmVudERlZmF1bHQoKTtcblx0XHRcdCQoJyNsb2ctZGlhbG9nJykuZGlhbG9nKCdvcGVuJyk7XG4gICAgfSk7XG5cbiAgICAkKCcjbG9nLWRpYWxvZycpLmRpYWxvZyh7XG4gICAgICB0aXRsZTogJ0FkZCBOZXcgTG9nJyxcbiAgICAgIGRpYWxvZ0NsYXNzOiAnd3AtZGlhbG9nJyxcbiAgICAgIGF1dG9PcGVuOiBmYWxzZSxcbiAgICAgIGRyYWdnYWJsZTogZmFsc2UsXG4gICAgICB3aWR0aDogJ2F1dG8nLFxuICAgICAgbW9kYWw6IHRydWUsXG4gICAgICByZXNpemFibGU6IGZhbHNlLFxuICAgICAgY2xvc2VPbkVzY2FwZSA6IHRydWUsXG4gICAgICBwb3NpdGlvbjoge1xuICAgICAgICBteTogXCJjZW50ZXJcIixcbiAgICAgICAgYXQ6IFwiY2VudGVyXCIsXG4gICAgICAgIG9mOiB3aW5kb3dcbiAgICAgIH0sXG4gICAgICBjcmVhdGU6IGZ1bmN0aW9uKCl7XG4gICAgICAgICQoJy51aS1kaWFsb2ctdGl0bGViYXItY2xvc2UnKS5hZGRDbGFzcygndWktYnV0dG9uJyk7XG4gICAgICB9LFxuICAgICAgb3BlbjogZnVuY3Rpb24oKXtcbiAgICAgICAgJCgnLnVpLXdpZGdldC1vdmVybGF5JykuYmluZCgnY2xpY2snLGZ1bmN0aW9uKCl7XG4gICAgICAgICAgJCgnI2xvZy1kaWFsb2cnKS5kaWFsb2coJ2Nsb3NlJyk7XG4gICAgICAgIH0pXG4gICAgICB9XG4gICAgfSk7XG4gIH0pO1xuXG5cbiAgJCgnLmxvZy10YWJsZS12aWV3Jykub24oJ3Njcm9sbCcsIGZ1bmN0aW9uKGUpIHtcbiAgICB2YXIgJHRoaXMgPSAkKHRoaXMpO1xuICAgIGlmKCAwID09ICR0aGlzLnNjcm9sbFRvcCgpICYmIDAgPT0gJHRoaXMuZmluZCgnLm92ZXJsYXknKS5sZW5ndGggKSB7XG4gICAgICB2YXIgJG92ZXJsYXkgPSAkKCc8ZGl2IGNsYXNzPVwib3ZlcmxheVwiPjxkaXY+Jyk7XG4gICAgICAkdGhpcy5hcHBlbmQoICRvdmVybGF5ICk7XG4gICAgICAvLyBsb2FkIG1vcmUgbGluZXNcbiAgICAgIHZhciBvZmZzZXQgPSAkKCd0ZCcsIHRoaXMpLmxlbmd0aDtcbiAgICAgIHZhciBwYXlsb2FkID0ge1xuICAgICAgICAnYWN0aW9uJzogJ2ZldGNoX2xvZ19yb3dzJyxcbiAgICAgICAgJ2xvZ2ZpbGUnOiAkdGhpcy5kYXRhKCdsb2dmaWxlJyksXG4gICAgICAgICdvZmZzZXQnOiAkKCd0ZCcsIHRoaXMpLmxlbmd0aCxcbiAgICAgICAgJ3JlZ2V4JzogJHRoaXMuZGF0YSgncmVnZXgnKSxcbiAgICAgICAgJ2N1dG9mZl9ieXRlcyc6ICR0aGlzLmRhdGEoJ2xvZ2J5dGVzJylcbiAgICAgIH07XG4gICAgICBjb25zb2xlLmxvZyhwYXlsb2FkKTtcbiAgICAgICQucG9zdCh3aW5kb3cuYWpheHVybCwgcGF5bG9hZCwgZnVuY3Rpb24ocmVzcG9uc2UpIHtcbiAgICAgICAgdmFyIG9sZGhlaWdodCA9ICQoJ3RhYmxlJywgJHRoaXMpLmhlaWdodCgpO1xuICAgICAgICAkKCd0Ym9keScsICR0aGlzKS5wcmVwZW5kKHJlc3BvbnNlKTtcbiAgICAgICAgJHRoaXMuc2Nyb2xsVG9wKCQoJ3RhYmxlJywgJHRoaXMpLmhlaWdodCgpIC0gb2xkaGVpZ2h0KTtcbiAgICAgICAgJG92ZXJsYXkucmVtb3ZlKCk7XG4gICAgICB9KTtcbiAgICB9XG4gIH0pO1xuXG59KShqUXVlcnkpO1xuIl0sInNvdXJjZVJvb3QiOiIvc291cmNlLyJ9