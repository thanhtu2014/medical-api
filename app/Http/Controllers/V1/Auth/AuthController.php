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
use App\Repositories\Interfaces\UserRepositoryInterface;

class AuthController extends BaseController
{

    /**
     * @var UserRepositoryInterface
     */
    protected $userRepository;

    /**
     * AuthController constructor.
     * @param UserRepository $userRepository
     */
    public function __construct( UserRepositoryInterface $userRepository ) 
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param UserSignupRequest $request
     */
    public function signup(UserSignupRequest $request) 
    {
        try {
            $request->validated();

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
    
            return $this->sendResponseGetToken($success, 'User register successfully.');

        } catch(Exception $error) {
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised'], 500);
        }
    }

    public function login(UserLoginRequest $request) 
    {
        try {
            $request->validated();

            if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
                $user = Auth::user(); 
                $success = $user->createToken('Personal Access Token')->plainTextToken;

                return $this->sendResponseGetToken($success, 'User login successfully.');
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
        return $this->sendResponseGetToken($response, 200);
    }
}
