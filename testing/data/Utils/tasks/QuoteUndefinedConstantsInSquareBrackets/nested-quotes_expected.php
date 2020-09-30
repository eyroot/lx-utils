<?php

$array = [];
$array['key'] = 'value';

echo "string without " . "" . " variables";
echo "escaped quote example\"";
echo "another escaped quote example\\\"";


echo "I am an old coding style variable: {$array['key']}, but still a variable";
echo "Another example {$array['key']} here";
echo "Another example {$array['key']}";
echo "{$array['key']} is an example";
echo "{$array['key']}";

function testFunction($a) { return $a . "_x"; }
testFunction("param {$array['key']} abc");

echo "Another use case {$array['key1']} inside a string" . " and on the same line {$array['key2']}.";

echo "Another use
case {$array['key']} inside a
    multiline string";

echo "I am almost properly defined here {$array['key']} and only need a minor adjustment :)";
echo "I am already properly defined here {$array['key']} and must remain as I am";

// multidimensional arrays inside quotes
echo "{$array['key1']['key2']}";
echo "{$array['key1']['key2']['key3']}";

print_r($datadata['TimeInTransitResponse']['#']['TransitResponse'][0]['#']['ServiceSummary'][0]['#']);

$request = "<?php xml version=\"1.0\"?>
<AccessRequest xml:lang=\"en-US\">
<?php xml version=\"1.0\"?>

         <CompanyName>{$ship_to['company_name']}</CompanyName>
         <AttentionName>{$ship_to['attn_name']}</AttentionName>
";

echo '<td><strong><a href="file.php?getparam='.$_GET['getparam'].'">linkname</a></strong></td>';

$designerlist.="<option value=\"{$designer['designerid']}\" $designerselector>{$designer['designername']}</option>";

$identifier=stripslashes(substr("{$openinfo['username']}-{$openinfo['orderid']}",0,15) . "...");
?>
<tr>
    <td colspan="4" bgcolor="CBC8CE">
        <a href="ordermanager.php?director=<?= $_GET['director'] ?>">Prev Month</a>
    </td>
</tr>
