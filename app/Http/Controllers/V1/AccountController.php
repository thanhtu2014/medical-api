<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interfaces\AccountRepositoryInterface;
use App\Http\Requests\V1\AccountRequest;
use App\Http\Controllers\BaseController;
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
        $this->accountRepository = $accountRepository;
    }

    /**
     * @param Request $request
     */
    public function detail($id) 
    {
        try {
            $account = $this->accountRepository->getDetail($id);

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
     *  @param AccountRequest $request
     */
    public function update(AccountRequest $request)
    {
        try {
            $account = $this->accountRepository->getDetail($request->id);

            if(!$account) {
                return $this->sendError("Account not found with ID : $request->id!", 404);
            }
            $request->validated();

            $input = $request->all();
            $input['new_by'] = Auth::user()->id;
            $input['upd_by'] = Auth::user()->id;
            $input['upd_ts'] = Carbon::now();

            $account = $this->accountRepository->update($request->id, $input);

            if($account) {
                return $this->sendResponse($account, 'Update account successfully.');
            }

        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }

    /**
     *  @param Request $request
     */
    public function delete(Request $request)
    {
        try {
            $hospital = $this->hospitalRepository->getDetail($request->id);

            if(!$hospital) {
                return $this->sendError("Hospital not found with ID : $request->id!", 404);
            }

            $this->hospitalRepository->delete($request->id);

            return $this->sendResponse([], 'Delete hospital successfully.');

        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }
    
}
