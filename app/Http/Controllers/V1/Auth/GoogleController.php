<?php
  
namespace App\Http\Controllers\V1\Auth;
  
use Illuminate\Http\Request;
use Socialite;
use App\Http\Controllers\BaseController;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
  
class GoogleController extends BaseController
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var GooogleDriver
     */
    protected $driver = 'google';

    public function __construct(UserRepositoryInterface $userRepository) 
    {
        $this->userRepository = $userRepository;
        $this->driver = $driver;
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function redirectToGoogle()
    {
        return Socialite::driver($this->driver)->redirect();
    }
        
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver($this->driver)->user();
        } catch (Exception $e) {
            return $this->sendError('Unauthorised.', ['error'=>$e->getMessage()], 500);
        }

        // check for email in returned user
        return empty( $user->email )
            ? $this->sendError('Not found.', "No email id returned from {$driver} provider.", 404)
            : $this->loginOrCreateAccount($user, $this->driver);
            
        // try {
        //     $userDetail = Socialite::driver($this->driver)->user();

        //     $finduser = $this->userRepository->getUserByGoogleId($userDetail->id);
       
        //     // $finduser = User::where('google_id', $userDetail->id)->first();
       
        //     if($finduser){

        //         $user = Auth::user(); 
        //         $success = $user->createToken('Personal Access Token')->plainTextToken;

        //         return $this->sendResponse($success, 'User login successfully.');
        //     }else{
        //         $input = [
        //             'name' => $user->name,
        //             'email' => $user->email,
        //             'google_id'=> $user->id,
        //             'password' =>Hash::make('12345678'),
        //             'key' => '',
        //             'token' => '',
        //             'new_by' => 'Admin',
        //             'upd_by' => 'Admin',
        //             'upd_ts' => Carbon::now()
        //         ];

        //         // Create a new user
        //         $newUser = $this->userRepository->create($input);
      
        //         $success =  $newUser->createToken('Personal Access Token')->plainTextToken;

        //         //Update tokens
        //         $this->userRepository->update($newUser->id, ['token' => $success]);

        //         return $this->sendResponse($success, 'User register successfully.');
        //     }
      
        // } catch (Exception $e) {
        //     dd($e->getMessage());
        // }
    }

    protected function loginOrCreateAccount($googleUser, $driver)
    {
        // check for already has account
        // $user = User::where('email', $googleUser->getEmail())->first();
        $user = $this->userRepository->getUserByEmail($googleUser->getEmail());

        dd($user);

        // if user already found
        if( $user ) {
            // update the avatar and provider that might have changed
            $input = [
                'type' => LOGIN_GOOGLE_TYPE_VALUE,
                'google_id'=> $googleUser->id,
                'profile_photo_path' => $googleUser->avatar,
                'token' => $googleUser->token,
                'upd_ts' => Carbon::now()
            ];

            // login the user
            Auth::login($user, true);

            // Update user
            $userDetail = $this->userRepository->update($user->id, $input);

            return $this->sendResponse($userDetail->token, 'User update successfully.');
            
        } else {
            $input = [
                'type' => LOGIN_GOOGLE_TYPE_VALUE,
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id'=> $googleUser->id,
                'profile_photo_path' => $googleUser->avatar,
                'password' =>Hash::make('12345678'),
                'token' => $googleUser->token,
                'key' => '',
                'new_by' => 'Admin',
                'upd_by' => 'Admin',
                'upd_ts' => Carbon::now()
            ];

            // login the user
            Auth::login($user, true);

            // Create a new user
            $userDetail = $this->userRepository->create($input);

            return $this->sendResponse($userDetail->token, 'User register successfully.');
        }
    }
}