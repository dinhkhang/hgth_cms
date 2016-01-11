PID_FILE=$0.pid
[ -f $PID_FILE ] && {
   pid=`cat $PID_FILE`
   ps -p $pid && {
    echo -e "DailyKpiInteractionCrontab_proccess is running ..." >> /video/www/halovietnam/Server/cms/app/tmp/logs/DailyKpiInteractionCrontab_proccess.out.log
    exit
   }
   rm -f $PID_FILE
}
echo $$ > $PID_FILE
php /video/www/halovietnam/Server/cms/app/cron_dispatcher.php /DailyKpiInteractionCrontab/proccess >> /video/www/halovietnam/Server/cms/app/tmp/logs/DailyKpiInteractionCrontab_proccess.out.log 2>&1