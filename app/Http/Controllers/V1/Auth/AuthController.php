<?php

namespace App\Http\Controllers\V1\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

use App\Http\Requests\V1\UserLoginRequest;
use App\Http\Requests\V1\UserSignupRequest;
use App\Repositories\Eloquent\UserRepository;

class AuthController extends BaseController
{
    private $userRepository;

    public function __construct( UserRepository $userRepository ) 
    {
        $this->userRepository = $userRepository;
    }

    public function signup(UserSignupRequest $request) 
    {
        try {
            $input = $request->validated();

            // Check user exist
            $user = $this->userRepository->getUserByEmail($input['email']);

            if ($user) {
                return $this->sendError('Email is already signuped!', ['error'=>'Existing email.'], 409);
            }
        
            $input = $request->all();
            $input['password'] = Hash::make($input['password']);
            $input['key'] = '';
            $input['token'] = '';
            $input['new_by'] = 'Admin';
            $input['upd_by'] = 'Admin';
            $input['upd_ts'] = Carbon::now();

            // Create a new user
            $user = $this->userRepository->create($input);

            $success =  $user->createToken('Personal Access Token')->plainTextToken;

            //Update tokens
            $this->userRepository->update($user->id, ['token' => $success]);
    
            return $this->sendResponse($success, 'User register successfully.');

        } catch(Exception $error) {
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised'], 500);
        }
    }

    public function login(UserLoginRequest $request) 
    {
        try {
            $input = $request->validated();

            if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
                $user = Auth::user(); 
                $success = $user->createToken('Personal Access Token')->plainTextToken;

                return $this->sendResponse($success, 'User login successfully.');
            } 
            else{ 
                return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
            }
        } catch(Exception $error) {
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised'], 500);
        }
    }

    public function logout (Request $request) 
    {
        Auth::user()->token()->delete();
        $response = ['message' => 'You have been successfully logged out!'];
        return $this->sendResponse($response, 200);
    }
}
