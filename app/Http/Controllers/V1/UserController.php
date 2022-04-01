<?php

namespace App\Http\Controllers\V1;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController;
use App\Repositories\RepaymentRepository;
use App\Repositories\LoanRepository;
use App\Http\Requests\API\V1\UserRepaymentAPIRequest;
use App\Http\Resources\API\V1\UserProfileResource;
use App\Http\Resources\API\V1\LoansResource;
use App\Http\Resources\API\V1\RepaymentResource;

class UserController extends BaseController
{
    private $_loanRepo;
    private $_repaymentRepo;

    public function __construct(
        LoanRepository $loanRepo,
        RepaymentRepository $repaymentRepo
    ) {
        $this->_loanRepo = $loanRepo;
        $this->_repaymentRepo = $repaymentRepo;
    }


    /**
     * Retrieve authenticated user
     *
     * @return object User
     */
    public function profile(Request $request)
    {
        return $this->success('Retrieve user successfully.', new UserProfileResource($request->user()));
    }

}
