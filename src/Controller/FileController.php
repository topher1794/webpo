<?php

namespace webpo\Controller;


use webpo\Controller;
use Google\Cloud\Storage\StorageClient;


class FileController extends Controller
{

    public function __construct() {

    }


    public function uploadToGCS() {
        
        $projectId = GCP_PROJ;
        $bucketName = GCP_BUCKET;

        
        try{
   
        $localFilePath =  'C:/wamp64/www/POSUM_1713849788.pdf';  // file to upload
        $uploadName = 'webpo/POSUM_1713849788.pdf';        // path inside the bucket

        // Create Storage client
        $storage = new StorageClient([
            'projectId' => $projectId,
            'keyFilePath' => WPA_PATH . '/WPA_GCP_CREDS.json', // no putenv needed

        ]);

        $bucket = $storage->bucket($bucketName);


            echo "uploading";
              // Upload the file
        $data = $bucket->upload(
            fopen($localFilePath, 'r'),
            [
                'name' => $uploadName
            ]
        );

        print_r($data->info());


        print_r($data);

        echo "File uploaded to gs://$bucketName/$uploadName\n";
            
        }catch(\Exception $e) {
            print_r($e);
        }
      

    }
    public function extractFromZip() {

    }
}
