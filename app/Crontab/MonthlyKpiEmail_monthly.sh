PID_FILE=$0.pid
[ -f $PID_FILE ] && {
   pid=`cat $PID_FILE`
   ps -p $pid && {
    echo -e "MonthlyKpiEmail_monthly is running ..." >> /video/www/halovietnam/Server/cms/app/tmp/logs/MonthlyKpiEmail_monthly.out.log
    exit
   }
   rm -f $PID_FILE
}
echo $$ > $PID_FILE
php /video/www/halovietnam/Server/cms/app/cron_dispatcher.php /MonthlyKpiEmail/monthly >> /video/www/halovietnam/Server/cms/app/tmp/logs/MonthlyKpiEmail_monthly.out.log 2>&1