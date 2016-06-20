<?php namespace Funblr\Handlers;

use Storage;
use Config;
use Auth;

use Illuminate\Http\UploadedFile;

use Funblr\User;

class ImagesHandler {
    
    protected $image;
    protected $user;
    
    public function __construct(User $user, UploadedFile $imgFile) 
    {
        $this->user = $user;
        $this->image = $imgFile;
        $file = array('image' => $imgFile);
    }
    
    public function upload() 
    {

        $fileName = $this->getNewFileName();

        Storage::disk('s3')->put($fileName, file_get_contents($this->image->getRealPath()));
                
        $bucket = Config::get('filesystems.disks.s3.bucket');
        $url = $this->getS3Url($bucket, $fileName);
        return [$fileName, $url];
    }
    
    private function getS3Url($bucket, $fileName) 
    {
        $s3 = Storage::disk('s3');
        return $s3->getDriver()->getAdapter()->getClient()->getObjectUrl($bucket, $fileName);
    }
    
    private function getNewFileName()
    {
        $extension = $this->image->getClientOriginalExtension();
        $fileName = env('S3_FOLDER') . "/" . $this->user->username . "/" . rand(11111,99999).'.'.$extension;
        return $fileName;
    }
    
}