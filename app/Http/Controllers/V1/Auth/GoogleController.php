<?php
  
namespace App\Http\Controllers\V1\Auth;
  
use Illuminate\Http\Request;
use Socialite;
use App\Http\Controllers\BaseController;
use App\Repositories\Eloquent\UserRepository;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
  
class GoogleController extends BaseController
{
    private $userRepository;

    public function __construct(UserRepository $userRepository) 
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
        
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleGoogleCallback()
    {
        try {
            $userDetail = Socialite::driver('google')->user();

            $finduser = $this->userRepository->getUserByGoogleId($userDetail->id);
       
            // $finduser = User::where('google_id', $userDetail->id)->first();
       
            if($finduser){

                $user = Auth::user(); 
                $success = $user->createToken('Personal Access Token')->plainTextToken;

                return $this->sendResponse($success, 'User login successfully.');
            }else{
                $input = [
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id'=> $user->id,
                    'password' =>Hash::make('12345678'),
                    'key' => '',
                    'token' => '',
                    'new_by' => 'Admin',
                    'upd_by' => 'Admin',
                    'upd_ts' => Carbon::now()
                ];

                // Create a new user
                $newUser = $this->userRepository->create($input);
      
                $success =  $newUser->createToken('Personal Access Token')->plainTextToken;

                //Update tokens
                $this->userRepository->update($newUser->id, ['token' => $success]);

                return $this->sendResponse($success, 'User register successfully.');
            }
      
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}