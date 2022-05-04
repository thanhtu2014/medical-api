<?php
  
namespace App\Http\Controllers\V1\Auth;

use Exception;
use Socialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BaseController;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Carbon\Carbon;
  
class GoogleController extends BaseController
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository) 
    {
        $this->userRepository = $userRepository;
    }

    public function create(Request $request) 
    {
        try {
            $googleUser = $request->input();
        } catch (Exception $e) {
            return $this->sendError(['error'=>$e->getMessage()], 500);
        }

        // check for email in returned user
        return empty($googleUser['email'])
            ? $this->sendError(["error" => "No email id returned from google provider."], 404)
            : $this->loginOrCreateAccount($googleUser);
    }

    public function loginOrCreateAccount($googleUser)
    {
        try {
            // check for already has account
            $user = $this->userRepository->findBy(['email' => $googleUser['email']]);

            // if user already found
            if($user) {
                // update the photo and provider that might have changed
                $input = [
                    'type' => LOGIN_GOOGLE_TYPE_VALUE,
                    'temail' => $googleUser['email'],
                    'google_id'=> $googleUser['id'],
                    'profile_photo_path' => $googleUser['photo'],
                    'token' => $googleUser['token'],
                    'upd_ts' => Carbon::now()
                ];

                // Update user
                $res = $this->userRepository->update($user->id, $input);

                if($res) {
                    $userDetail = $this->userRepository->findById($user->id);

                    return $this->sendResponseGetToken($userDetail, $userDetail->token, 'User login successfully.');
                }
            } else {
                $input = [
                    'type' => LOGIN_GOOGLE_TYPE_VALUE,
                    'name' => $googleUser['name'],
                    'email' => $googleUser['email'],
                    'temail' => $googleUser['email'],
                    'password' =>Hash::make(USER_PASSWORD_DEFAULT_VALUE),
                    'key' => '',
                    'token' => $googleUser['token'],
                    'plan' => FREE_PLAN_VALUE,
                    'gender' => '',
                    'progress' => '',
                    'status' => USER_WAITING_STATUS_KEY_VALUE,
                    'google_id'=> $googleUser['id'],
                    'profile_photo_path' => $googleUser['photo'],
                    'new_by' => 'Admin',
                    'upd_by' => 'Admin',
                    'upd_ts' => Carbon::now()
                ];

                // Create a new user
                $userDetail = $this->userRepository->create($input);

                return $this->sendResponseGetToken($userDetail, $userDetail->token, 'User register successfully.');
            }
        } catch(Exception $error) {
            return $this->sendError(['error'=>'Unauthorised'], 500);
        }
    }
}