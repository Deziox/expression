<?php
require('vendor/autoload.php');

$s3 = new Aws\S3\S3Client([
    'version'  => 'latest',
    'region'   => 'us-east-1',
    'credentials' => [
        'key'    => 'AKIARR2QLCN77CCRK2KR',//getenv('AWS_ACCESS_KEY_ID'),
        'secret' => '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',//getenv('AWS_SECRET_ACCESS_KEY'),
    ]
]);
//$bucket = getenv('S3_BUCKET')?: die('No "S3_BUCKET" config var in found in env!');
$bucket = 'expression'?: die('No "S3_BUCKET" config var in found in env!');

//AKIARR2QLCN77CCRK2KR
//btgDHJMLiuSAsXfxFvGWuIpCWgOtEUsGvrYYA9b4