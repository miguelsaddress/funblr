<?php namespace Funblr\Handlers;

use Config;
use Excel;
use Storage;
use ZipArchive;

use Funblr\Exceptions\CantCreateZipFileException;
use Funblr\User;

class FileExportHandler {

    
    // TO DO: config file for this info
    protected $csvName = 'csv-export-';
    protected $excelName = 'excel-export-';
    protected $zipName = 'zip-export-';

    protected $user;
    protected $bucket;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->bucket = Config::get('filesystems.disks.s3.bucket');
    }

    public function doExport($ext, $mode='object')
    {
        
        $name = $this->makeFileName($ext);
        if ($ext === 'zip') {
            $fullName = $this->getTmpFolder() . $name;
            $zipFile = $this->makeZip($fullName);

            $url = $this->uploadToS3($name, $fullName);
            return $url;

        }
        
        $f = Excel::create($name, function($excel) {
            $excel->sheet('Funblr Export', function($sheet) {
                $sheet->fromArray($this->user->posts()->select(['title', 'image_url'])->get());
            });
        });
        
        
        if( $mode === 'object') {
            return $f;            
        }
        elseif ($mode === 'store')
        {
            $info = $f->store($ext, $fullName = false, true);
            $url = $this->uploadToS3($info['file'], $info['full']);
            
            return $url;
        }
    }
    
    private function makeZip($zipFilename)
    {

        $zip = new ZipArchive();
        
        if ($zip->open($zipFilename, ZipArchive::CREATE)!==TRUE) {
            throw CantCreateZipFileException($zipFilename);
        }
        
        // CSV
        $csvUrl = $this->doExport('csv', 'store');
        $csvFile = file_get_contents($csvUrl);
        $zip->addFromString("export.csv", $csvFile);
        $this->deleteFromS3('csv');

        //EXCEL
        $excelUrl = $this->doExport('xlsx', 'store');
        $excelFile = file_get_contents($excelUrl);
        $zip->addFromString("excel.xlsx",$excelFile);
        $this->deleteFromS3('xlsx');
        //Images
        $images = $this->user->posts()->select('image_url', 'name')->get();
        foreach ($images as $image) {
            $imagefile = file_get_contents($image->image_url);
            $zip->addFromString($image->name,$imagefile);
        }
        $zip->close();
        return $zipFilename;
    }

    private function makeFileName($ext)
    {
        $name = "Export-";
        if($ext === 'csv') {
            $name = $this->csvName . time();
        }
        else if ($ext === 'xlsx') {
            $name = $this->excelName . time();
        } else if ($ext === 'zip') {
            $name = $this->zipName . time() . ".zip";
        }

        return $name;
    }

    private function getTmpFolder()
    {
        return Config::get('excel.export.store.path') . "/";
    }

    private function getBucketFolderName()
    {
        return  $this->user->username  . "/" . "file-exports" . "/";
    }

    private function uploadToS3($name, $fullLocalPath)
    {
        $s3Name = $this->getBucketFolderName() . $name;

        Storage::disk('s3')->put($s3Name, file_get_contents($fullLocalPath));

        // NOT WORKING... DUNNO WHY YET. So Plain PHP instead
        // Storage::delete($info['full']);
        unlink($fullLocalPath);

        $url = Storage::disk('s3')->getDriver()->getAdapter()->getClient()->getObjectUrl($this->bucket, $s3Name);

        return $url;
    }

    private function deleteFromS3($ext)
    {
        $regex = "/\." . $ext . "$/";
        Storage::disk('s3')->getDriver()->getAdapter()->getClient()->deleteMatchingObjects($this->bucket, $prefix="", $regex);
    }

}