@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F8FAFC] py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-8">

        {{-- 1. HEADER SECTION --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">{{ $userColocation->name }}</h1>
                <div class="flex items-center gap-3 mt-1">
                    <span class="px-2 py-0.5 bg-indigo-100 text-indigo-600 text-xs font-bold rounded uppercase">Owner</span>
                    <span class="text-slate-400 text-sm">Reputation: <strong class="text-slate-600">{{ number_format($userColocation->members->avg('pivot.reputation') ?? 0, 0) }}%</strong></span>
                </div>
            </div>
            
            <div class="flex flex-wrap gap-2">
                <button onclick="openModal('expense-modal')" class="inline-flex items-center px-6 py-2.5 bg-indigo-600 text-white rounded-xl font-semibold hover:bg-indigo-700 transition shadow-md shadow-indigo-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Expense
                </button>
                <button onclick="openModal('invite-modal')" class="px-4 py-2 text-slate-600 bg-white border border-slate-200 rounded-xl font-medium hover:bg-slate-50 transition">Invite Member</button>
                <button class="px-4 py-2 text-slate-600 bg-white border border-slate-200 rounded-xl font-medium hover:bg-slate-50 transition">Manage Categories</button>
                <button class="px-4 py-2 text-red-500 bg-red-50 rounded-xl font-medium hover:bg-red-100 transition">Cancel Colocation</button>
            </div>
        </div>

        {{-- 2. STATS CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Total Paid --}}
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Total Paid</p>
                <p class="text-3xl font-extrabold text-slate-800 underline decoration-indigo-500 decoration-4 underline-offset-8">
                    {{ number_format($userColocation->expenses->sum('amount'), 2) }} MAD
                </p>
            </div>

            {{-- Total Owed --}}
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Total Owed</p>
                <p class="text-3xl font-extrabold text-slate-800 underline decoration-orange-400 decoration-4 underline-offset-8">
                    
                    450.00 MAD
                </p>
            </div>

            {{-- Current Balance --}}
            @php $myPivot = $userColocation->members->where('id', auth()->id())->first()->pivot; @endphp
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Current Balance</p>
                <p class="text-3xl font-extrabold {{ $myPivot->balance >= 0 ? 'text-emerald-500' : 'text-red-500' }}">
                    {{ $myPivot->balance >= 0 ? '+' : '' }}{{ number_format($myPivot->balance, 2) }} MAD
                </p>
            </div>
        </div>

        {{-- 3. MAIN CONTENT: SETTLEMENTS & EXPENSES --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            
            <div class="space-y-4">
                <h2 class="text-lg font-bold text-slate-800">Settlements</h2>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 space-y-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="font-bold text-slate-800">You</p>
                            <p class="text-xs text-slate-400 font-medium italic">owe by</p>
                        </div>
                        <p class="text-xl font-bold text-red-500">50.00</p>
                    </div>
                    <button class="w-full py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-100 uppercase tracking-widest text-sm">
                        Mark as Paid
                    </button>
                </div>
            </div>

            
            <div class="lg:col-span-2 space-y-4">
                <h2 class="text-lg font-bold text-slate-800">Recent Expenses</h2>
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase">Title</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase">Category</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase">Payer</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase text-center">Date</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase text-right tracking-widest">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($userColocation->expenses->sortByDesc('created_at') as $expense)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4 font-semibold text-slate-700">{{ $expense->title }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 bg-indigo-50 text-indigo-500 text-[10px] font-bold rounded-md uppercase tracking-wider">
                                        {{ $expense->category }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500 font-medium">
                                    {{ $expense->user->id === auth()->id() ? 'You' : $expense->user->name }}
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-400 font-medium text-center">
                                    {{ $expense->created_at->format('Y-m-d') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="font-bold text-slate-900">{{ number_format($expense->amount, 2) }} MAD</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if($userColocation->expenses->isEmpty())
                        <div class="p-12 text-center text-slate-400 italic">No expenses recorded yet.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>


@include('components.modals') 

@endsection

<script>
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        modal.querySelector('div').classList.add('scale-100');
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }


    window.onclick = function(event) {
        const modal = document.getElementById('expense-modal');
        if (event.target == modal) {
            closeModal('expense-modal');
        }
    }
</script>