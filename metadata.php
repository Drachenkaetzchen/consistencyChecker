<?php
$sMetadataVersion = '1.1';

/**
 * Module information
 */
$aModule = array(
    'id' => 'consistencyChecker',
    'title' => 'OXID Konsistenzprüfung',
    'description' => '',
    'thumbnail' => 'picture.png',
    'version' => '1.0',
    'author' => 'Felicia Hummel / PartKeepr UG',
    'files' => array(
        'consistencyChecker' => 'consistencyChecker/consistencyChecker.php',
    ),
    'templates' => array(
        "consistencyChecker.tpl" => "consistencyChecker/views/consistencyChecker.tpl"
    )

);
