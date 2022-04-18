<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interfaces\KeyWordRepositoryInterface;
use App\Repositories\Interfaces\MediaKeyWordRepositoryInterface;
use App\Http\Requests\V1\KeyWordRequest;
use App\Http\Controllers\BaseController;
use Carbon\Carbon;

class KeyWordController extends BaseController
{
    /**
     * @var KeyWordRepositoryInterface
     */
    private $keyWordRepository;

    /**
     * @var type
     */
    private $type;

    /**
     * KeyWordController constructor.
     * @param KeyWordRepositoryInterface $keyWordRepository
     */
    public function __construct(KeyWordRepositoryInterface $keyWordRepository) 
    {
        $this->keyWordRepository = $keyWordRepository;
        $this->type = get_current_action_view_type();
    }

    /**
     * @param null
     */
    public function index()
    {
        try {
            $keywords = $this->keyWordRepository->getListByType($this->type);

            return $this->sendResponse($keywords, 'Get data list successfully.');
        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }

    /**
     * @param Request $request
     */
    public function detail($id)
    {
        try {
            $keyWord = $this->keyWordRepository->getDetail($id, $this->type);

            if($keyWord) {
                return $this->sendResponse($keyWord, 'Get people detail successfully.');
            }

            return $this->sendError("Not found!", 404);
        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }

    /**
     *  @param KeyWordRequest $request
     */
    public function store(KeyWordRequest $request)
    {
        try {
            $request->validated();

            $input = $request->all();
            $input['type'] = MEDICINE_KEY_VALUE;
            $input['color'] = $request->input('color') ? $request->input('color') : 'Nope';
            $input['new_by'] = Auth::user()->id;
            $input['upd_by'] = Auth::user()->id;
            $input['upd_ts'] = Carbon::now();

            $keyWord = $this->keyWordRepository->create($input);

            if($hospital) {
                $mediaKeyWord = $this->keyWordRepository->create($input);
            }

            if($hospital) {
                return $this->sendResponse($hospital, 'Create hospital successfully.');
            }

        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }

    /**
     *  @param KeyWordRequest $request
     */
    public function update(KeyWordRequest $request)
    {
        try {
            $hospital = $this->keyWordRepository->getDetail($request->id);

            if(!$hospital) {
                return $this->sendError("Hospital not found with ID : $request->id!", 404);
            }
            $request->validated();

            $input = $request->all();
            $input['new_by'] = Auth::user()->id;
            $input['upd_by'] = Auth::user()->id;
            $input['upd_ts'] = Carbon::now();

            $hospital = $this->keyWordRepository->update($request->id, $input);

            if($hospital) {
                return $this->sendResponse($hospital, 'Update hospital successfully.');
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
            $hospital = $this->keyWordRepository->getDetail($request->id);

            if(!$hospital) {
                return $this->sendError("Hospital not found with ID : $request->id!", 404);
            }

            $this->keyWordRepository->delete($request->id);

            return $this->sendResponse([], 'Delete hospital successfully.');

        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }
    
}
