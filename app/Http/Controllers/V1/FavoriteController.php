<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interfaces\FavoriteRepositoryInterface;
use App\Http\Requests\V1\FavoriteRequest;
use App\Http\Controllers\BaseController;
use Carbon\Carbon;

class FavoriteController extends BaseController
{
    /**
     * @var FavoriteRepositoryInterface
     */
    private $favoriteRepository;

    /**
     * FavoriteController constructor.
     * @param FavoriteRepositoryInterface $favoriteRepository
     */
    public function __construct(FavoriteRepositoryInterface $favoriteRepository) 
    {
        $this->favoriteRepository = $favoriteRepository;
    }

    /**
     * @param null
     */
    public function index() 
    {
        try {
            $favorites = $this->favoriteRepository->allBy([
                'user' => Auth::user()->id,
                'chg' => CHG_VALID_VALUE
            ]);

            return $this->sendResponse($favorites, 'Get favorite list successfully.');
        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }


    /**
     *  @param FavoriteRequest $request
     */
    public function store(FavoriteRequest $request)
    {
        try {
            $request->validated();

            $input = $request->all();
            $input['record'] = $request->record;
            $input['user'] = Auth::user()->id;
            $input['new_by'] = Auth::user()->id;
            $input['upd_by'] = Auth::user()->id;
            $input['upd_ts'] = Carbon::now();

            $favorite = $this->favoriteRepository->create($input);

            if($favorite) {
                return $this->sendResponse($favorite, 'Create favorite successfully.');
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
            $favorite = $this->favoriteRepository->findById($request->id);

            if(!$favorite) {
                return $this->sendError("Favorite not found with ID : $request->id!", 404);
            }

            $this->favoriteRepository->deleteById($request->id);

            return $this->sendResponse([], 'Delete favorite successfully.');

        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }
}
