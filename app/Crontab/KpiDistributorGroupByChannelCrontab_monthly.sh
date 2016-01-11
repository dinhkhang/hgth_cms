PID_FILE=$0.pid
[ -f $PID_FILE ] && {
   pid=`cat $PID_FILE`
   ps -p $pid && {
    echo -e "KpiDistributorGroupByChannelCrontab_monthly is running ..." >> /video/www/halovietnam/Server/cms/app/tmp/logs/KpiDistributorGroupByChannelCrontab_monthly.out.log
    exit
   }
   rm -f $PID_FILE
}
echo $$ > $PID_FILE
php /video/www/halovietnam/Server/cms/app/cron_dispatcher.php /KpiDistributorGroupByChannelCrontab/monthly >> /video/www/halovietnam/Server/cms/app/tmp/logs/KpiDistributorGroupByChannelCrontab_monthly.out.log 2>&1