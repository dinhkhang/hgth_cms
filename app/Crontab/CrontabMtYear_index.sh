PID_FILE=$0.pid
[ -f $PID_FILE ] && {
   pid=`cat $PID_FILE`
   ps -p $pid && {
    echo -e "CrontabMtYear_index is running ..." >> /video/www/halovietnam/Server/cms/app/tmp/logs/CrontabMtYear_index.out.log
    exit
   }
   rm -f $PID_FILE
}
echo $$ > $PID_FILE
php /video/www/halovietnam/Server/cms/app/cron_dispatcher.php /CrontabMtYear/index >> /video/www/halovietnam/Server/cms/app/tmp/logs/CrontabMtYear_index.out.log 2>&1