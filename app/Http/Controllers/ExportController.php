<?php namespace Funblr\Http\Controllers;

use Illuminate\Http\Request;
use Funblr\Handlers\FileExportHandler;

class ExportController extends ApiController
{
    protected $exporter;
    
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->exporter = new FileExportHandler($this->user);
    }   
    
    public function downloadAsCsv()
    {
        $this->exporter->doExport('csv')->download('csv');
    }
    
    public function downloadAsExcel()
    {
        $this->exporter->doExport('xlsx')->download('xlsx');
    }
    
    public function downloadAsZip()
    {
        $zipUrl = $this->exporter->doExport('zip');
        return redirect()->to($zipUrl);
    }
    
    /**
     * Returns a url where the bulk export file can be obtained.
     *
     * @return \Illuminate\Http\Response
     */
    public function bulkAsUrl()
    {
        $zipUrl = $this->exporter->doExport('zip');
        return response()->json([
            'file_url' => $zipUrl,
        ]);
    }
}
