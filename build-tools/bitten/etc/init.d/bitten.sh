#!/bin/sh
# $Id$

# for RedHat...
# chkconfig: 35 60 95

# for SuSE...
### BEGIN INIT INFO
# Provides:       bitten
# Required-Start: $local_fs $syslog $network
# Required-Stop:  $local_fs $syslog $network
# Default-Start:        3   5
# Default-Stop:   0 1 2   4   6
# Description:    @SUMMARY@
### END INIT INFO

vendor="`lsb_release -si | tr '[A-Z]' '[a-z]' | cut -d\  -f1`"
case $vendor in
   suse)
     . /etc/rc.status
     usg="usage: $0 {start|stop|try-restart|restart|status}"
     ;;
   redhat|fedora|centos)
     . /etc/rc.d/init.d/functions
     . /etc/sysconfig/network
     usg="usage: $0 {start|stop|restart|condrestart|status}"
     ;;
   *)
     [ -f /etc/rc.d/init.d/functions ] && . /etc/rc.d/init.d/functions
     [ -f /etc/sysconfig/network     ] && . /etc/sysconfig/network
     usg="usage: $0 {start|stop|restart|condrestart|status}"
     ;;
esac

n="bitten"
sconf="/etc/sysconfig/$n"
pgm="/usr/bin/$n-slave"
pid="/var/run/$n-slave.pid"
cfg="/var/tmp/$n.conf"

err()                                           # error handling...
{
   echo -n >&2 "$n: $*"
   [ $vendor = suse ] && echo -n $rc_failed
   echo
   exit 1

}

warn()
{
   echo >&2 "$0: warning: $*"

}

[ -f $sconf           ] || err "$sconf: not found"
. /etc/sysconfig/$n
[ -f $pgm             ] || err "$pgm: not found"
[ -x $pgm             ] || err "$pgm: not executable"
[ -d $BUILD_DIRECTORY ] || err "$BUILD_DIRECTORY: not found"

id $BUILD_USER > /dev/null 2>&1 || err "missing user $BUILD_USER"

[ -f "$LOG_FILE" ] || { touch $LOG_FILE && chown $BUILD_USER $LOG_FILE; }

[ -x /usr/bin/lsb_release ] && {
   cat <<! > $cfg
[distro]
vendor=`lsb_release -si`
release=`lsb_release -sr`
!

}

# LSB specific...
lsb()
{
   rc_reset
   case "$1" in
     start)
       echo -n "Starting $n "
       cd /var/tmp       # make sure we have a current working directory
       checkproc $pgm
       case $? in
         0) err "already running";;
         1) echo -n "(pid file found, process dead) ";;
       esac
       cmd="sudo -u $BUILD_USER $pgm $DEBUG -d $BUILD_DIRECTORY -f $cfg"
       cmd="$cmd -u $TRAC_USER -p $TRAC_PASS -l $LOG_FILE $BUILD_INTERVAL"
       cmd="$cmd $MASTER_URL"
       if [ -n "$DEBUG" ]; then
         cmd="$cmd >> $LOG_FILE 2>&1"
       else
         cmd="$cmd > /dev/null 2>&1"
       fi
       cmd="$cmd &"
       [ -n "$DEBUG" ] && echo -en "\n$cmd"
       eval $cmd
       rc_status -v
       echo $! > $pid
       ;;
     stop)
       echo -n "Stopping $n "
       checkproc $pgm
       case $? in
         0) killproc -TERM $pgm;;
         1) echo -n "(pid file found, process dead) "   ; checkproc $pgm;;
         3) echo -n "(no pid file found, process dead) "; checkproc $pgm;;
         *) warn "internal: unknown status code ($?) "  ; checkproc $pgm;;
       esac
       rc_status -v
       rm -f $pid
       ;;
     try-restart)
       $0 status && $0 restart
       rc_status
       ;;
     restart)
       $0 stop
       sleep 5
       $0 start
       rc_status
       ;;
     reload)
       exit 3
       ;;
     status)
       echo -n "Checking for $n "
       checkproc $pgm
       case $? in
         0) ;;
         1) echo -n "(pid file found, process dead) ";;
         3) ;;
         *) warn "internal: unknown status code ($?) ";;
       esac
       checkproc $pgm
       rc_status -v
       ;;
     *) echo >&2 "$usg" && exit 1;;
   esac

}

# RedHat specific (much the same except for exit behaviour)...
rh()
{
   r=0
   case "$1" in
     start)
       echo -n "Starting $n "
       cd /var/tmp       # make sure we have a current working directory
       cmd="sudo -u $BUILD_USER $pgm $DEBUG -d $BUILD_DIRECTORY -f $cfg"
       cmd="$cmd -u $TRAC_USER -p $TRAC_PASS -l $LOG_FILE $BUILD_INTERVAL"
       cmd="$cmd $MASTER_URL"
       cmd="$cmd >> $LOG_FILE 2>&1 &"
       [ -n "$DEBUG" ] && echo -en "\n$cmd"
       eval $cmd >> $LOG_FILE 2>&1
       r=$?
       echo $! > $pid
       if [ $r -eq 0 ]; then echo $success; else echo $failure; fi
       ;;
     stop)
       echo -n "Stopping $n "
       [ -f $pid ] && ps -p `cat $pid` > /dev/null 2>&1 && \
         { kill `cat $pid`; rm $pid; }
       r=$?
       if [ $r -eq 0 ]; then echo $success; else echo $failure; fi
       ;;
     try-restart)
       $0 status && $0 restart
       r=$?
       ;;
     restart)
       $0 stop
       $0 start
       r=$?
       ;;
     reload)
       r=3
       ;;
     status)
       echo -n "Checking for $n "
       [ -f $pid ] && ps -p `cat $pid` > /dev/null 2>&1
       r=$?
       if [ $r -eq 0 ]; then echo $success; else echo $failure; fi
       ;;
     *) err "$usg";;
   esac
   return $r

}

# How are we called?
case $vendor in
                   suse) lsb $1; rc_exit;;
   redhat|fedora|centos) rh  $1; exit $?;;
                      *) rh  $1; exit $?;;
esac 