PID_FILE=$0.pid
[ -f $PID_FILE ] && {
   pid=`cat $PID_FILE`
   ps -p $pid && {
    echo -e "KpiEmail_daily is running ..." >> /video/www/halovietnam/Server/cms/app/tmp/logs/KpiEmail_daily.out.log
    exit
   }
   rm -f $PID_FILE
}
echo $$ > $PID_FILE
php /video/www/halovietnam/Server/cms/app/cron_dispatcher.php /KpiEmail/daily >> /video/www/halovietnam/Server/cms/app/tmp/logs/KpiEmail_daily.out.log 2>&1