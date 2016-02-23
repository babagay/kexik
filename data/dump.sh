#!/bin/bash

# ./dump.sh - вызов скрипта, когда находишься в его папке

# sudo chown -R $USER:$USER /var/www/example.com/public_html
# sudo chmod -R 755 /var/www
# sudo chmod 0777 -R "/var/www/$1"

# echo "First argument:$1" # вывести 1 аргумент

# Path to backup dir
to="/var/www/html/backup"
#to="/home/backup"

# Path to site root
patch="/var/www/kex"
#patch="/var/www/keksik/public_html"



# database credentials
DBUSER="root"
DBPASS=""
#DBPASS="123123"
DBHOST="localhost"

# current date
DATE=`date +%Y-%M-%d`

# y/m/d/h/m separately
YEAR=`date +%Y`
MONTH=`date +%m`
DAY=`date +%d`
HOURS=`date +%H`
MINUTES=`date +%M`


#move old backups on +1 day
for i in `seq 5 $1`
    do
        mv $to/backup$i $to/backup$[$i+1]
    done

mkdir --parents --verbose $to/backup1

# create list of databases
mysql -u $DBUSER -p$DBPASS -e "show databases;" > $to/databases.list

# excludes list (Database is a part of SHOW DATABASES output)
EXCLUDES=( information_schema mysql performance_schema phpmyadmin )

NUM_EXCLUDES=${#EXCLUDES[@]}
for database in `cat $to/databases.list`
    do
        skip=0
        let count=0
        while [ $count -lt $NUM_EXCLUDES ] ; do
        # check if this name in excludes list
        if [ "$database" = ${EXCLUDES[$count]} ] ; then
            let skip=1
        fi
         let count=$count+1
    done

if [ $skip -eq 0 ] ; then
    echo "++ $database"
    # now we can backup current database
    cd $to/backup1
    backup_name="$YEAR-$MONTH-$DAY.$database.backup.sql"
    backup_tarball_name="$backup_name.tar.gz"
    `/usr/bin/mysqldump --databases "$database" -u "$DBUSER" --password="$DBPASS" > "$backup_name"`
    echo " backup $backup_name"
    `/bin/tar -zcf "$backup_tarball_name" "$backup_name"`
    echo " compress $backup_tarball_name"
    `/bin/rm "$backup_name"`
    echo " cleanup $backup_name"
fi
done
`/bin/rm $to/databases.list`
echo "MySQL backup is done!"

########################
# backuping is www dir
########################
#ls $patch | grep -v 'www.simbl.linc.ua' > $to/dir.list
ls $patch | grep -v 'asd' > $to/dir.list
for dir in `cat $to/dir.list`
do
 #tar -zcvpf $to/backup1/"$YEAR-$MONTH-$DAY.$dir.backup.www.tar.gz" --exclude=$patch/$dir/backup.txt --exclude=$patch/$dir/awstats --exclude=$patch/$dir/bitrix/backup --exclude=$patch/$dir/upload/update --exclude=$patch/$dir/upload/support/not_image --exclude=$patch/$dir/modlogan --exclude=*.exe --exclude=*.rar $patch/$dir/

 if [[ "$dir" != "lib" && "$dir" != "403.shtml" && "$dir" != "404.shtml" && "$dir" != "404.php" && "$dir" != "int.php" && "$dir" != "500.shtml" && "$dir" != "nal" ]]; then
    tar -zcvpf $to/backup1/"$YEAR-$MONTH-$DAY.$dir.tar.gz" $patch/$dir/
 fi

 done
 rm $to/dir.list
 echo "Dir backup is Done!"


