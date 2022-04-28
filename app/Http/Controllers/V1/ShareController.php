<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interfaces\ShareRepositoryInterface;
use App\Http\Requests\V1\ShareRequest;
use App\Mail\NotificationInvite;
use App\Http\Controllers\BaseController;
use Mail;
use Carbon\Carbon;

class ShareController extends BaseController
{
    /**
     * @var ShareRepositoryInterface
     */
    private $shareRepository;

    /**
     * ShareController constructor.
     * @param ShareRepositoryInterface $shareRepository
     */
    public function __construct(ShareRepositoryInterface $shareRepository) 
    {
        $this->shareRepository = $shareRepository;
    }

    /**
     *  @param ShareRequest $request
     */
    public function share(ShareRequest $request)
    {
        try {
            $request->validated();  

            $status = generate_status();

            $input = $request->all();
            $input['user'] = Auth::id();
            $input['new_by'] = Auth::user()->id;
            $input['upd_by'] = Auth::user()->id;
            $input['upd_ts'] = Carbon::now();
            $input['status'] = $request->to ?  STATUS_ACCEPT_VALUE : STATUS_REQUEST_VALUE;
            $share = $this->shareRepository->create($input);

            if($share->to){
                $email = $share->people->email;
            }

            if($share->mail){
                $email = $share->mail;
            }

            if($share) {
                //send mail to email accept
                Mail::to($email)->send(new NotificationInvite($status));

                if (Mail::failures()) {
                    return $this->sendError('Bad gateway.', ['error'=>'Bad gateway'], 502);
                }
            }
            
            if($share->to) {
                return $this->sendResponse($share, 'Share member with family successfully.');
            }else {
                return $this->sendResponse($share, 'Send Mail successfully.');
            }

        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }

    // public function getConfirm(){
    //     dd('dd');
    // }

    /**
     *  @param Request $request
     */
    public function delete(Request $request)
    {
        try {
            $share = $this->shareRepository->findById($request->id);

            if(!$share) {
                return $this->sendError("Share not found with ID : $request->id!", 404);
            }

            $this->shareRepository->deleteById($request->id);

            return $this->sendResponse([], 'Delete share successfully.');

        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }
}
