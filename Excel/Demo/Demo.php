<?php

$data = array(
    1 => array('Name', 'Surname'),
    array('Schwarz', 'Oliver'),
    array('Test', 'Peter')
);

$xls = new Excel_Lite('UTF-8', false, 'My Test Sheet');
$xls->addArray($data);
$xls->generateXML('my-test');