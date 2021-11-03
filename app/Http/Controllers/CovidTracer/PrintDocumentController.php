<?php

namespace App\Http\Controllers\CovidTracer;

use App\Http\Controllers\Controller;
use App\CovidTracer\PrintDocument;
use Illuminate\Http\Request;
use Picqer;
use Auth;

class PrintDocumentController extends Controller
{
    public function store(Request $request)
    {
        $generator = new Picqer\Barcode\BarcodeGeneratorPNG();

        $document = new PrintDocument;
        $document->user_id = Auth::user()->id;
        $document->module_printed = convertData($request['module']);
        $changes = $document->getDirty();
        $document->save();

        $update_document = PrintDocument::find($document->id);
        $update_document->barcode = 'TRANS'.str_pad($document->id,8,"0", STR_PAD_LEFT);
        $changes = array_merge($changes, $update_document->getDirty());
        $update_document->save();

        
        /* logs */
        action_log('Print Document Mngt', 'CREATE', array_merge(['id' => $document->id], $changes));

        // echo DNS2D::getBarcodeHTML('4445645656', 'QRCODE');
        return response()->json(array('data' => base64_encode($generator->getBarcode($update_document->barcode, $generator::TYPE_CODE_128, 1))));
    }
}
