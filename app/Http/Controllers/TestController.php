<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test()
    {
        $id = 49;
        $record = PurchaseOrder::with(['additions', 'quotations' => function ($query) {
            $query->where('approved', true);
        }])->find($id);

        $data = [
            'order' => $record,
            'approvedQuotations' => $record->quotations->filter(function ($quotation) {
                return $quotation->approved;
            })
        ];

        return view('pdf.test_order', $data);
    }
}
