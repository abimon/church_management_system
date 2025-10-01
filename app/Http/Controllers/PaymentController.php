<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth ;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::all();
        if(request()->is('api/*')){
            return response()->json($payments);
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
        // validate and store payment logic here
        $validatedData = request()->validate([
            'account_id' => 'required|exists:accounts,id',
            'amount' => 'required|numeric',
            'status' => 'nullable|string',
            'payment_method' => 'required|string',
            'reference' => 'nullable|string',
        ]);
        $payment = Payment::create([
            'account_id'=>request('account_id'),
            'amount'=>request('amount'),
            'status'=>request('status'),
            'payment_method'=>request('payment_method'),
            'reference'=>request('reference'),
            'logged_by'=>Auth::user()->id,
        ]);
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
