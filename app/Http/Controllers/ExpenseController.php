<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Colocation;
use Illuminate\Support\Facades\Auth;
use App\Models\Settlement;

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



        //
        $activeMembers = $colocation->users()
            ->wherePivotNull('left_at')
            ->get();

        $memberCount = $activeMembers->count();

        if ($memberCount > 1) {

            $share = $data['amount'] / $memberCount;

            foreach ($activeMembers as $member) {


            if ($member->id != Auth::id()) {

                    Settlement::create([
                        'expense_id' => $expense->id,
                        'sender_id' => $member->id,
                        'receiver_id' => Auth::id(),
                        'amount' => $share,
                        'status' => 'pending',
                    ]);
                }
            }
        }


        
        
        return redirect()->back()->with('success', 'Dépense ajoutée avec succès !');
    }

}
