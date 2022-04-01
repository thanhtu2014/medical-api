<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Http\Controllers\BaseController;
use App\Http\Requests\LoginRequest;
use App\Repositories\UserRepository;

class AuthController extends BaseController
{
    /**
     * @var UserInterface
     */
    private $userRepository;

    /**
     * RoleController constructor.
     * @param UserInterface $userRepository
     */
    public function __construct(UserRepository $userRepository) 
    {
        $this->userRepository = $userRepository;
    }

    public function login(LoginRequest $request)
    {
        $input = $request->validated();

        $user = $this->_adminRepo->findWhere(['email' => $input['email']])->first();

        if(! $user){
            return $this->error("User not found!", 404);
        } else {

            if (! Hash::check($input['password'], $user->password)){
                return response()->json([
                    'message' => 'Email or password is incorrect!'
                ], 403);
            }

            // Generate access token 
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            $token->save();

            return $this->success("Login successfully.", [
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => $tokenResult->token->expires_at,
            ]);
        }
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->token()->revoke();
            return $this->success('Logged out successfully.');
        } catch (\Throwable $th) {
            // throw $th;
            return $this->error('Error when logging out!', 500);
        }
    }

    /**
     * Retrieve authenticated user
     *
     * @return [Object] Admin
     */
    public function user(Request $request)
    {
        return $this->success('Retrieve user successfully.', new AuthAdminResource($request->user()));
    }

    /**
     * Retrieve authenticated user
     *
     * @return [Object] Admin
     */
    public function update(UpdateAdminRequest $request)
    {
        try {
            $user = $request->user();
            $input = $request->validated();
            if(!empty($input['password'])){
                $input['password'] = Hash::make($input['password']);
            }
            $user = $this->_adminRepo->update($user->id, $input);
            return $this->success('Update profile successfully.', $user);
        } catch (\Throwable $th) {
            // throw $th;
            return $this->error('Error when logging out!', 500);
        }
        return $this->success('Retrieve user successfully.', new AuthAdminResource($request->user()));
    }

}
