<?php

namespace App\Http\Controllers\DCC;

use App\Http\Controllers\Controller;
use App\Models\EvidenceRemark;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DCCRemarkController extends Controller
{
    public function addRemark(Request $request)
    {
        $validatedData = $request->validate([
            'evidence'=>['required','exists:evidence,id'],
            'status'=>['required',Rule::in('good','neutral','bad')],
            'comment'=>['required','string','max:255']
        ]);

        EvidenceRemark::create([
            'evidence_id'=>$validatedData['evidence'],
            'status'=>$validatedData['status'],
            'user_id'=>auth()->id(),
            'comment'=>$validatedData['comment']
        ]);

        return redirect()->back()->with('success','Remark successfully added!');
    }
}
