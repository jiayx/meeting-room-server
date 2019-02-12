#!/bin/bash
# 打包脚本
# By Horizon<root@yinhongbo.com>

ROOT=$(cd `dirname $0`; pwd)"/"
SRC_ROOT=$ROOT
OUTPUT_ROOT=$ROOT"output/"
OUTPUT_WEBROOT=$OUTPUT_ROOT"bin/php/"
ENV=$1

function execCmd(){
    CMD=$(echo "$1" | sed "s#;;;#__@@__#g")
    OLDIFS=$IFS;IFS=';';
    for CMDCell in $CMD
    do
        echo $CMDCell
        CMDCell=$(echo "$CMDCell" | sed "s#__@@__#;#g")
        res=$(eval "$CMDCell" 2>&1)
        if [ "$?" !=  "0" ];then
            echo  "The Shell encountered a fatal error. then exit.This is Error Info."
            eval "printf '%.0s=' {1..50};echo"
        echo "Run Commod: "$CMDCell
            echo "STDERR: "$res
            eval "printf '%.0s=' {1..50};echo"
            echo "please fix it. and go on..."
            exit 100
        fi
    done
    IFS=$OLDIFS
    return 0;
}

CMD=""
CMD=$CMD"cd $ROOT && rm -rf $ROOT/output && mkdir -p $OUTPUT_WEBROOT;"
# COPY 处理要上线的文件/文件夹
CMD=$CMD"cd ${ROOT} && rsync -av --exclude '.git' --exclude 'output' ./ $OUTPUT_WEBROOT; rm -rf $OUTPUT_WEBROOT/.env;"
#  处理配置文件
if [ "$ENV" == "sit" ] ;then
    CMD=$CMD"cp $SRC_ROOT/.env.sit $OUTPUT_WEBROOT/.env;"
elif [ "$ENV" == "test" ] ;then
    CMD=$CMD"cp $SRC_ROOT/.env.test $OUTPUT_WEBROOT/.env;"
elif [ "$ENV" == "uat" ] ;then
    CMD=$CMD"cp $SRC_ROOT/.env.uat $OUTPUT_WEBROOT/.env;"
else
    CMD=$CMD"cp $SRC_ROOT/.env.release $OUTPUT_WEBROOT/.env;"
fi
CMD=$CMD"cd $OUTPUT_WEBROOT && rm -rf .env.*;"
# 其他处理，如fis编译等
# fis -op ....
# npm install
# npm run prod

# 处理结束, 开始执行打包

execCmd "$CMD"
cd $OUTPUT_WEBROOT;composer update;composer dump-autoload -o;