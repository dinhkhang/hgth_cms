PID_FILE=$0.pid
[ -f $PID_FILE ] && {
   pid=`cat $PID_FILE`
   ps -p $pid && {
    echo -e "KpiDistributorGroupByChannelCrontab_quarterly is running ..." >> /video/www/halovietnam/Server/cms/app/tmp/logs/KpiDistributorGroupByChannelCrontab_quarterly.out.log
    exit
   }
   rm -f $PID_FILE
}
echo $$ > $PID_FILE
php /video/www/halovietnam/Server/cms/app/cron_dispatcher.php /KpiDistributorGroupByChannelCrontab/quarterly >> /video/www/halovietnam/Server/cms/app/tmp/logs/KpiDistributorGroupByChannelCrontab_quarterly.out.log 2>&1