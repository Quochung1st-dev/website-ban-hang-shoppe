<?php

if ($_SERVER['HTTP_ACTION'] == 'woof_upload_ext') {
    //upload protection
    if (isset($_SERVER['HTTP_ABSPATH'])) {
        require($_SERVER['HTTP_ABSPATH'] . 'wp-load.php');
        wp();
        if (!current_user_can('manage_woocommerce')) {
            return;
        }
    } else {
        return;
    }

    //***

    require('extras/Uploader.php');


    $upload_dir = $_SERVER['HTTP_LOCATION'];
    $valid_extensions = array('zip');

    $Upload = new FileUpload('uploadfile');
    //$ext = $Upload->getExtension(); // Get the extension of the uploaded file
    //$Upload->newFileName = 'customFileName.'.$ext;
    $result = $Upload->handleUpload($upload_dir, $valid_extensions);

    //***

    $zipArchive = new ZipArchive();
    $zip_result = $zipArchive->open($Upload->getSavedFile());
    $ext_info = array();
    if ($zip_result === TRUE) {
        $zipArchive->extractTo($upload_dir);
        $zipArchive->close();
        $dir = $upload_dir . str_replace('.zip', '', $Upload->getFileName());
        //$ext_info = parse_ini_file($dir . '/info.dat');
        $ext_info = WOOF_HELPER::parse_ext_data($dir . '/info.dat');
        $ext_info['idx'] = md5($dir);
        unlink($Upload->getSavedFile());
    }

    if (!$result) {
        echo json_encode(array('success' => false, 'msg' => $Upload->getErrorMsg()));
    } else {
        echo json_encode(array('success' => true, 'ext_info' => $ext_info));
    }
}


