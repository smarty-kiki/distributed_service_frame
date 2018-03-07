#!/bin/bash

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"/../..

DEP_FILE=$ROOT_DIR/dep_service_list
if [ ! -f $DEP_FILE ]
then
    echo "DEP_FILE 不存在，检查 $DEP_FILE"
    exit
fi

sh $ROOT_DIR/project/tool/dep_build.sh link

DEP_SERVICE_VOLUMN="-v $ROOT_DIR/../frame:/var/www/frame"
while read line
do
    SERVICE_NAME=`echo $line | grep -v ^# | awk '{print $1}'`
    if [ "$SERVICE_NAME" != "" ]
    then
        DEP_SERVICE_VOLUMN="$DEP_SERVICE_VOLUMN -v $ROOT_DIR/../$SERVICE_NAME:/var/www/$SERVICE_NAME"
    fi
done<$DEP_FILE

sudo docker run --rm -ti --name distributed_service_frame \
    -v $ROOT_DIR/:/var/www/distributed_service_frame $DEP_SERVICE_VOLUMN \
    -v $ROOT_DIR/project/config/development/nginx/distributed_service_frame.conf:/etc/nginx/sites-enabled/default \
kikiyao/debian_php_dev_env start