<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Colocation;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    //

    public function store(Request $request , Colocation $colocation)
    {
        // dd($request);
       $data =  $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string',
           
        ]);

       
        $expense = Expense::create([
            'title' => $request->title,
            'amount' => $request->amount,
            'category' => $request->category,
            'user_id' => Auth::id(), 
            'colocation_id' => $colocation->id,
        ]);

        
        
        return redirect()->back()->with('success', 'Dépense ajoutée avec succès !');
    }

}
