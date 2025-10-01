<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accounts = Account::where('is_active',true)->get();
        if(request()->is('api/*')){
            $accs = [];
            foreach($accounts as $account){
                $accs[] =$account->name;
            }
            return response()->json(['accounts'=>$accounts]);
        }
        return view('accounts.index', compact('accounts'));
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
    public function store(Request $request)
    {
        try {
            // validate the request data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'is_active' => 'nullable|boolean',
                'target' => 'nullable|numeric',
                'parent_account_id' => 'nullable|exists:accounts,id',
            ]);
            // create a new account
            $account = Account::create($validatedData);
            // return a response
            if(request()->is('api/*')){
                return response()->json(['message' => 'Account created successfully', 'account' => $account], 201);
            }
            return redirect()->back()->with('success', 'Account created successfully');
        } catch (\Throwable $th) {
            if(request()->is('api/*')){
                return response()->json(['message' => 'Account creation failed', 'error' => $th->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Account creation failed: ' . $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Account $account)
    {
        if(request()->is('api/*')){
            return response()->json($account);
        }
        return view('accounts.show', compact('account'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Account $account)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Account $account)
    {
        if(request('name')!=null){
            $account->name=request('name');
        }
        if(request('is_active')!=null){
            $account->is_active=request('is_active');
        }
        if(request('target')!=null){
            $account->target=request('target');
        }
        if(request('parent_account_id')!=null){
            $account->parent_account_id=request('parent_account_id');
        }
        $account->update();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        $account->is_active=false;
        $account->update();
        if(request()->is('api/*')){
            return response()->json(['message' => 'Account deleted successfully']);
        }
        return redirect()->back()->with('success', 'Account deleted successfully');
    }
}
