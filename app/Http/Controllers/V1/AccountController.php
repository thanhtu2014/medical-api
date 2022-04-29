<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interfaces\AccountRepositoryInterface;
use App\Http\Requests\V1\AccountRequest;
use App\Http\Requests\V1\ConfirmCodeRequest;
use App\Http\Controllers\BaseController;
use App\Mail\NotificationMail;
use Mail;
use App\Models\User;
use Carbon\Carbon;

class AccountController extends BaseController
{
    /**
     * @var AccountRepositoryInterface
     */
    private $accountRepository;
    /**
     * AccountController constructor.
     * @param AccountRepositoryInterface $accountRepository
     */
    public function __construct(AccountRepositoryInterface $accountRepository) 
    {
        // dd('dđ');
        $this->accountRepository = $accountRepository;
    }

    /**
     * @param Request $request
     */
    public function detail($id) 
    {
        dd('dđ');
        try {
            $account = $this->accountRepository->findById($id);

            if($account) {
                return $this->sendResponse($account, 'Get account detail successfully.');
            }

            return $this->sendError("Not found!", 404);
        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }

    /**
     * @param Request $request
     */
    public function searchAccount(Request $request) 
    {         
        $account = $this->accountRepository->Search($request);
        
        return $this->sendResponse($account, 'Search account successfully.');    
    }
    /**
     *  @param AccountRequest $request
     */
    public function update(AccountRequest $request)
    {
        try {
            $account = $this->accountRepository->findById($request->id);
            
            $code = generate_unique_code();

            if(!$account) {
                return $this->sendError("Account not found with ID : $request->id!", 404);
            }
            $request->validated();

            $input = $request->all();
            $input['code']  = $code;
            $input['new_by'] = Auth::user()->id;
            $input['upd_by'] = Auth::user()->id;
            $input['upd_ts'] = Carbon::now();

            $account = $this->accountRepository->update($request->id, $input);

            if($account) {
                //send mail to email update email
                Mail::to($request->temail)->send(new NotificationMail($code));

                if (Mail::failures()) {
                    return $this->sendError('Bad gateway.', ['error'=>'Bad gateway'], 502);
                }
            }
            
            if($account) {
                return $this->sendResponse($account, 'Update account successfully.');
            }

        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }


    public function confirmCode(ConfirmCodeRequest $request) 
    {
        try {
            $request->validated();
            $account = $this->accountRepository->findBy(['code' => $request->code, 'chg' => CHG_VALID_VALUE]);
            dd($account);
            if($account) {
                $success =  $account->createToken('Personal Access Token')->plainTextToken;
                $this->userRepository->update(
                    $account->id);
                return $this->sendResponseGetToken($account, $success, 'Verify code successfully.');
            }

            return $this->sendError(['error'=>'User not found!'], 404);
        } catch(Exception $error) {
            return $this->sendError(['error'=>'Unauthorised'], 500);
        }
    }
    /**
     *  @param Request $request
     */
    public function delete(Request $request)
    {
        try {
            $hospital = $this->hospitalRepository->findById($request->id);

            if(!$hospital) {
                return $this->sendError("Hospital not found with ID : $request->id!", 404);
            }

            $this->hospitalRepository->deleteById($request->id);

            return $this->sendResponse([], 'Delete hospital successfully.');

        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }
    
}
