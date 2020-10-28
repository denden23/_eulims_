<?php
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');


$whitelist = array(
    '127.0.0.1',
    '::1',
    'localhost'
);
if (isset($_SERVER['SERVER_NAME'])) {
    if (!in_array($_SERVER['SERVER_NAME'], $whitelist)) {
        $Backend_URI = 'http://' . $_SERVER['SERVER_NAME'];
        $BaseURI = $Backend_URI.'/';
        $Upload_URI = $Backend_URI . "/uploads/user/photo/";
        $FrontendBaseURI = 'http://' . $_SERVER['SERVER_NAME'] . '/';
    } else {
        $Upload_URI = 'http://localhost/eulims/backend/web/uploads/user/photo/';
        $BaseURI = "http://localhost/eulims/backend/web/";
        $FrontendBaseURI = "http://localhost/eulims/frontend/web/";
    }
    /*
    $Upload_URI="http://192.168.1.90:8081/uploads/user/photo/";
    $BaseURI="http://192.168.1.90:8081/";
    $FrontendBaseURI="http://eulims.dost9.ph/";
    */
    $GLOBALS['upload_url'] = $Upload_URI;
    $GLOBALS['base_uri'] = $BaseURI;
    $GLOBALS['frontend_base_uri'] = $FrontendBaseURI;
    /********************** Global Configuration Settings **********************/
    $GLOBALS['rstl_id']= 11; //RSTL ID
    $GLOBALS['api_url']="https://eulims.onelab.dost.gov.ph/api/"; //API_URL
    $GLOBALS['terminal_id']=1; // Unique Terminal ID for each RSTL Cashier
    /***************************************************************************/
}