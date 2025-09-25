<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $validateUser = Validator::make(
                request()->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if (!Auth::attempt(request()->only(['email', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = User::where('email', request()->email)->first();
            Auth::login($user);
            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'user' => Auth::user(),
                'token' => $user->createToken("API TOKEN")->plainTextToken
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
            $validateUser = Validator::make(
                request()->all(),
                [
                    'name'=>'required | string',
                    'email'=> 'required| unique:users',
                    'phone'=> 'required|unique:users',
                    'residence'=>'required',
                    'church'=>'required|string',
                    'gender'=>'required|string',
                    'password'=>'required',
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::create([
                'name'=>request('name'),
                'email'=>request('email'),
                'phone'=>request('phone'),
                'residence'=>request('residence'),
                'avatar'=>request('avatar'),
                'church'=>request('church'),
                'gender'=>request('gender'),
                'password'=>Hash::make(request('password')),
            ]);
            // Auth::login($user);
            if(request()->is('api/*')){
                return response()->json([
                    'status' => true,
                    'message' => 'User Created Successfully',
                    // 'user' => $user,
                    'token' => $user->createToken("API TOKEN")->plainTextToken
                ], 200);
            }else{
                return redirect('/dashboard');
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function profile(){
        $user = Auth::user();
        return response()->json([
            'status' => true,
            'message' => 'User Profile',
            'user' => $user,
        ], 200);
    }
    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
    {
        $user=User::findOrFail($id);
        if(request('name')!=null){
            $user->name=request('name');
        }
        if(request('email')!=null){
            $user->email=request('email');
        }
        if(request('phone')!=null){
            $user->phone=request('phone');
        }
        if(request('residence')!=null){
            $user->residence=request('residence');
        }
        if (request('image') != null) {
            $file = request()->file('image');
            $fileName = ($user->last_name) . time() . '.' . $file->getClientOriginalExtension();
            if (request('title') == 'avatar') {
                $file->move('storage/avatars', $fileName);
                $user->avatar = '/storage/avatars/' . $fileName;
            }
        }
        if(request('church')!=null){
            $user->church=request('church');
        }
        if(request('role')!=null){
            $user->role=request('role');
        }
        if(request('status')!=null){
            $user->status=request('status');
        }
        if(request('gender')!=null){
            $user->gender=request('gender');
        }
        if(request('password')!=null){
            if(Hash::check(request('old_password'), $user->password)){
                $user->password=Hash::make(request('password'));
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'Old password does not match with our record.',
                ], 401);
            }
        }
        $user->update();
        if(request()->is('api/*')){
            return response()->json([
                'user' => $user,
                'status' => true,
                'message' => 'User Updated Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);
        }else{
            return redirect()->route('dashboard')->with('success', 'User Updated Successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
