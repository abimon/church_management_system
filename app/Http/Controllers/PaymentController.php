<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::all();
        if (request()->is('api/*')) {
            return response()->json(['data' => $payments]);
        }
        return view('payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        $accounts = Account::all();

        if (request()->is('api/*')) {
            return response()->json(['accounts' => $accounts,'request'=>request()->all()]);
            foreach ($accounts as $account) {
                if (request($account->name) != '0'|| request($account->name) != null) {
                    try {
                        Payment::create([
                            'account_id' => $account->id,
                            'amount' => request($account->name),
                            'status' => 'pending',
                            'payment_method' => request('payment_method') ?? 'Mobile Money',
                            'reference' => strtoupper(uniqid()),
                            'user_id' => Auth::user()->id,
                            'logged_by' => Auth::user()->id,
                        ]);
                    } catch (\Exception $e) {
                        return response()->json(['message' => 'Error logging payment for account: ' . $account->name, 'error' => $e->getMessage()], 500);
                    }
                }
            }
            return response()->json(['message' => 'Payments logged successfully']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
