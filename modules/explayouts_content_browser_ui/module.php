<?php
$Module = array( 'name' => 'explayouts_content_browser_ui',
                 'variable_params' => true );

$ViewList = array();

$ViewList['browser'] = array(
    'script' => 'browser.php',
    'functions' => array( 'read' ),
    'default_navigation_part' => 'ezsetupnavigationpart',
    'params' => array( 'LocationNodeID' )
);

$FunctionList = array();
$FunctionList['read'] = array();
