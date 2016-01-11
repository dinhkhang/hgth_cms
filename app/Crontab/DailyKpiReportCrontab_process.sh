PID_FILE=$0.pid
[ -f $PID_FILE ] && {
   pid=`cat $PID_FILE`
   ps -p $pid && {
    echo -e "DailyKpiReportCrontab_process is running ..." >> /video/www/halovietnam/Server/cms/app/tmp/logs/DailyKpiReportCrontab_process.out.log
    exit
   }
   rm -f $PID_FILE
}
echo $$ > $PID_FILE
php /video/www/halovietnam/Server/cms/app/cron_dispatcher.php /DailyKpiReportCrontab/process >> /video/www/halovietnam/Server/cms/app/tmp/logs/DailyKpiReportCrontab_process.out.log 2>&1