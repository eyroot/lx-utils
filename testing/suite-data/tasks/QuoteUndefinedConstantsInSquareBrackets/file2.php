<?php

$a = array();
$a[UNDEFINED_CONSTANT1] = '1';
$a[ UNDEFINED_CONSTANT2 ] = "2";
$a[UNDEFINED_CONSTANT3] = 3;
$a[UNDEFINED_CONSTANT4] = 'rand text here *(%&*($HFDH*( #OIOIFJO*(EU*$#PF;lkdpwp9fe2i3904';
$a[UNDEFINED_CONSTANT5] = "'dsadwq'dqw';";
$a[UNDEFINED_CONSTANT6_ijdsoaijda] = 5;

// multi dimensional arrays
$key1 = 'key1';
$key2 = 'key2';
echo $a[$key1][undefined];
echo $a[undefined][$key1];
echo $a[$key1][undefined][$key2][undefined_const2];
echo $a[$key1][undefined][$key2][undefined_const2][undefined_const3];
echo $a[$key1][undefined][$key2][undefined_const2][undefined_const3][$key1];
echo $a [$key1] [undefined] [$key2] [undefined_const2 ]    [  undefined_const3 ][  $key1  ];
echo $a[$key1][undefined] + $a[$key2][undefined][$key2];
echo $a[$key1][undefined].$a[$key2][undefined][$key2];
