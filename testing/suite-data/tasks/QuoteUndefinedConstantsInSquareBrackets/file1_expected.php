<?php

$a = array('value1', 'value2', 'value3' => 123);

var_dump($a[0]);
var_dump($a[1]);
var_dump($a [ 0 ]);
var_dump($a [ 1 ]);
var_dump($a['value3']);

$b = array('value4' => 421);

echo $a['value3'] + $b['value4'];

$another = array('value5' => 76);

echo $a['value3'] +
    $b['value4'] +
    $another['value5']
;

echo $a['value1'] + $b['value4'];

echo $a["value1"] + $b["value4"];

echo $another['value5'];

echo $another[ 'value5'  ];

echo $another[  'value5'];

echo $another ['value5'];
echo  $another  ['value5'];

echo 'this is just another message['.$a['value1'].']';

echo 'this is just another message['.
    $a['value1'].
    ']';

$adDAS4532_nother = array();
echo  $adDAS4532_nother  ['NDJSA_DJSIA_89'];
echo  $adDAS4532_nother  [ 'oidjnas_DSJNIOSA_8d89sua' ];
