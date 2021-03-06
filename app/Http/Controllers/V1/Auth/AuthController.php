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
use Mail;
use DB;

use App\Http\Requests\V1\UserLoginRequest;
use App\Http\Requests\V1\UserSignupRequest;
use App\Http\Requests\V1\SendMailRequest;
use App\Http\Requests\V1\ConfirmCodeRequest;
use App\Mail\NotificationMail;
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
    public function __construct(UserRepositoryInterface $userRepository) 
    {
        $this->userRepository = $userRepository;
    }

    public function login(UserLoginRequest $request) 
    {
        try {
            $request->validated();

            if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
                $user = Auth::user();
                $success_token = $user->createToken('Personal Access Token')->plainTextToken;

                //Update tokens
                $this->userRepository->update($user->id, ['token' => $success_token]);

                return $this->sendResponseGetToken($user, $success_token, 'User login successfully.');
            } 
            else{
                return $this->sendError(['error'=>'Wrong username or password!'], 401);
            }
        } catch(Exception $error) {
            return $this->sendError(['error'=>'Unauthorised'], 500);
        }
    }

    public function register(SendMailRequest $request) 
    {
        DB::beginTransaction();
        try {
            $request->validated();
            
            //get random code
            $code = generate_unique_code();

            $input = $request->all();
            $input['type'] = LOGIN_MAIL_TYPE_VALUE;
            $input['name'] = USER_NAME_DEFAULT_VALUE;
            $input['temail'] = $request->email;
            $input['gender'] = '';
            $input['password'] = Hash::make(PASSWORD_DEFAULT_VALUE);
            $input['key']   = '';
            $input['token'] = '';
            $input['code']  = $code;
            $input['plan']  = FREE_PLAN_VALUE;
            $input['progress']  = '';
            $input['status'] = USER_WAITING_STATUS_KEY_VALUE;
            $input['new_by'] = NEW_USER_DEFAULT_VALUE;
            $input['upd_by'] = NEW_USER_DEFAULT_VALUE;
            $input['upd_ts'] = Carbon::now();

            // Create a new user
            $user = $this->userRepository->create($input);

            if($user) {
                //send mail to email register
                Mail::to($request->email)->send(new NotificationMail($code));

                if (Mail::failures()) {
                    return $this->sendError('Bad gateway.', ['error'=>'Bad gateway'], 502);
                }
            }

            DB::commit();
            return $this->sendResponse(['success' => 'true'], 'Send Mail successfully.');
        } catch(Exception $error) {
            DB::rollBack();
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised'], 500);
        }
    }

    public function confirmCode(ConfirmCodeRequest $request) 
    {
        try {
            $request->validated();
            $user = $this->userRepository->findBy(['code' => $request->code, 'chg' => CHG_VALID_VALUE]);

            if($user) {
                $user1 = Auth::user();
                
                $success_token = $user->createToken('Personal Access Token')->plainTextToken;

                $this->userRepository->update(
                    $user->id, [
                        'token' => $success_token
                    ]);
                
                $userDetail = $this->userRepository->findById($user->id);

                return $this->sendResponseGetToken($userDetail, $success_token, 'Verify code successfully.');
            }

            return $this->sendError(['error'=>'User not found!'], 404);
        } catch(Exception $error) {
            return $this->sendError(['error'=>'Unauthorised'], 500);
        }
    }

    public function logout (Request $request) 
    {
        Auth::user()->token()->delete();
        $response = ['message' => 'You have been successfully logged out!'];
        return $this->sendResponse(['success' => 'true'], $response, 200);
    }
}
