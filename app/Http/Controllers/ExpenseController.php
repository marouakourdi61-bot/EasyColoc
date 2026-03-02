<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Colocation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01', 
            'category' => 'required|string',
            'colocation_id' => 'required|exists:colocations,id',
        ]);

        
        return DB::transaction(function () use ($request) {
            
            
            $expense = Expense::create([
                'title' => $request->title,
                'amount' => $request->amount,
                'category' => $request->category,
                'user_id' => Auth::id(), 
                'colocation_id' => $request->colocation_id,
            ]);

            
            $colocation = Colocation::with('members')->find($request->colocation_id);
            $members = $colocation->members;
            $totalMembers = $members->count();

            if ($totalMembers > 0) {
                

                $share = $request->amount / $totalMembers;

                foreach ($members as $member) {
                    

                    $isPayer = ($member->id === Auth::id());
                    
                    $adjustment = $isPayer 
                        ? ($request->amount - $share) 
                        : -$share;


                        $colocation->members()->updateExistingPivot($member->id, [
                        'balance' => $member->pivot->balance + $adjustment
                    ]);
                }
            }

            return redirect()->back()->with('success', 'Dépense ajoutée et soldes mis à jour !');
        });
    }
}