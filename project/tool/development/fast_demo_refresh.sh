#!/bin/bash

ROOT_DIR="$(cd "$(dirname $0)" && pwd)"/../../..
env=development

filenames=`ls $ROOT_DIR/domain/description/`

for filename in $filenames
do
  entity_name=${filename%.*}

  ENV=$env /usr/bin/php $ROOT_DIR/public/cli.php migrate:reset

  rm -rf $ROOT_DIR/command/migration/tmp/*[0-9]_$entity_name.sql
  rm -rf $ROOT_DIR/service/$entity_name.php

  grep -v "'\/$entity_name\." $ROOT_DIR/public/index.php > /tmp/index.php
  mv /tmp/index.php $ROOT_DIR/public/index.php

  grep -v "'$entity_name'" $ROOT_DIR/service/index.php > /tmp/service_index.php
  mv /tmp/service_index.php $ROOT_DIR/service/index.php

  ENV=$env /usr/bin/php $ROOT_DIR/public/cli.php entity:make-from-description --entity_name=$entity_name
  /bin/bash $ROOT_DIR/project/tool/classmap.sh $ROOT_DIR/domain
  ENV=$env /usr/bin/php $ROOT_DIR/public/cli.php migrate -tmp_files

  ENV=$env /usr/bin/php $ROOT_DIR/public/cli.php crud:make-from-description --entity_name=$entity_name
  /bin/sed -i "/init\ service/a\include\ SERVICE_DIR\.\'\/$entity_name\.php\'\;" $ROOT_DIR/public/index.php
done
