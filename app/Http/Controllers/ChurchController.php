<?php

namespace App\Http\Controllers;

use App\Models\Church;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ChurchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $churches=Church::all();
        if(request()->is('api/*')){
            $chs=[];
            foreach($churches as $church){
                $chs[]=$church->name;
            }
            return response()->json(['churches'=>$chs],200);
        }else{
            return view('church.index',compact('churches'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $validateChurch = Validator::make(
                request()->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required'
                ]
            );

            if ($validateChurch->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateChurch->errors()
                ], 401);
            }
            $church = Church::where('email', request('email'))->first();
            if (!$church) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }
            return response()->json([
                'status' => true,
                'message' => 'Church Logged In Successfully',
                'church' => $church,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validateChurch = Validator::make(
                request()->all(),
                [
                    'name'=>'required | unique:churches',
                    'location'=>'required',
                    'address'=>'required',
                    'contact'=>'required|unique:churches',
                    'password'=>'required',
                    'email'=>'required |unique:churches',
                ]
            );

            if ($validateChurch->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateChurch->errors()
                ], 401);
            }
            Church::create([
                "name" => request('name'),
                "location" => request('location'),
                "address" => request('address'),
                "contact" => request('contact'),
                "password" => Hash::make(request('password')),
                'email' => request('email'),
            ]);
            if (request()->is('api/*')) {
                return response()->json(['message' => 'Church Created Successfully'], 200);
            } else {
                return redirect()->back()->with('success', 'Church Created Successfully');
            }
        } catch (\Throwable $th) {
           if (request()->is('api/*')) {
                return response()->json([
                    'status' => false,
                    'message' => $th->getMessage()
                ], 500);
            } else {
                return redirect()->back()->with('error', $th->getMessage());
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Church $church)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Church $church)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Church $church)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Church $church)
    {
        //
    }
}
