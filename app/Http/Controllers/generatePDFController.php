<?php

namespace App\Http\Controllers;
use App\Models\Correspondence;
use App\Models\DisbursementOrder;
use PDF;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class generatePDFController extends Controller
{
    public function orderReport($id)
    {
        $record = PurchaseOrder::with(['additions', 'quotations' => function ($query) {
            $query->where('approved', true);
        }])->find($id);

        $data = [
            'order' => $record,
            'approvedQuotations' => $record->quotations->filter(function ($quotation) {
                return $quotation->approved;
            })
        ];

        $pdf = PDF::loadView('pdf.order', $data);
        return $pdf->stream('document.pdf');
    }
    public function correspondenceReport($id)
    {
        $record = Correspondence::with('trackings')->find($id);

        $data = [
            'correspondence' => $record,
            'trackings' => $record->trackings
        ];

        $pdf = PDF::loadView('pdf.correspondence', $data);
        return $pdf->stream('document.pdf');
    }

    public function testPdf($id){
        try {
            $record = DisbursementOrder::with([
                'purchaseOrder:id,sender_id',
                'purchaseOrder.sender:id,name',
                'purchaseOrder.quotations.supplier'
            ])->find($id);
            $data = [
              
                // 'order' => $record->toArray(), 
                'order' => (object)$record->toArray(),
                'one' => auth()->user()->titles()->where('slug', 'accountant')->exists() ? auth()->user()->name : 'غير محدد',
                'sigCheck' => auth()->user()->titles()->where('slug', 'accountant')->exists() && auth()->user()->signature,
                'sig' => auth()->user()->signature
            ];
            //  dd($data['order']->purchase_order);
            // dd($data);
            // return view('pdf.new', $data);
            $pdf = PDF::loadView('pdf.new', $data);
            return $pdf->stream('document.pdf');
        } catch (\Throwable $th) {
            //throw $th;
            Log::info($th->getMessage());
        }
       
        
    }
}