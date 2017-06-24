#!/usr/bin/env bash
sh stop.sh
for k in $( seq 1 $1)
do
  nohup php  gearman-start.php &
done