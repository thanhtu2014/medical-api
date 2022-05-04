<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interfaces\KeywordRepositoryInterface;
use App\Repositories\Interfaces\MediaKeywordRepositoryInterface;
use App\Http\Requests\V1\KeywordRequest;
use App\Http\Controllers\BaseController;
use Carbon\Carbon;
use DB;

class KeywordController extends BaseController
{
    /**
     * @var KeywordRepositoryInterface
     */
    private $keywordRepository;

    /**
     * KeywordController constructor.
     * @param KeywordRepositoryInterface $keywordRepository
     */
    public function __construct(
        KeywordRepositoryInterface $keywordRepository,
    ) {
        $this->keywordRepository = $keywordRepository;
    }

    /**
     * @param null
     */
    public function index()
    {
        try {
            $keywords = $this->keywordRepository->allBy([
                'type' => IMPORTANT_KEY_WORD_VALUE,
                'user' => Auth::user()->id,
                'chg' => CHG_VALID_VALUE
            ]);

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
            $keyword = $this->keywordRepository->findById($id);

            if($keyword) {
                return $this->sendResponse($keyword, 'Get medicine detail successfully.');
            }

            return $this->sendError("Not found!", 404);
        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }

    /**
     *  @param KeywordRequest $request
     */
    public function store(KeywordRequest $request)
    {
        try {
            $request->validated();

            $input = $request->all();
            $input['type'] = IMPORTANT_KEY_WORD_VALUE;
            $input['vx01'] = MEDICINE_KEY_VALUE;
            $input['vx02'] = MEDICINE_KEY_VALUE;
            $input['color'] = $request->input('color') ? $request->input('color') : 'Nope';
            $input['user'] = Auth::user()->id;
            $input['new_by'] = Auth::user()->id;
            $input['upd_by'] = Auth::user()->id;
            $input['upd_ts'] = Carbon::now();

            $keyword = $this->keywordRepository->create($input);

            return $this->sendResponse($keyword, 'Create keyword successfully.');
        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }

    /**
     *  @param KeywordRequest $request
     */
    public function update(KeywordRequest $request)
    {
        try {
            $hospital = $this->keywordRepository->findById($request->id);

            if(!$hospital) {
                return $this->sendError("Hospital not found with ID : $request->id!", 404);
            }
            $request->validated();

            $input = $request->all();
            $input['new_by'] = Auth::user()->id;
            $input['upd_by'] = Auth::user()->id;
            $input['upd_ts'] = Carbon::now();

            $hospital = $this->keywordRepository->update($request->id, $input);

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
            $medicine = $this->keywordRepository->findById($request->id);

            if(!$medicine) {
                return $this->sendError("Medicine not found with ID : $request->id!", 404);
            }

            $this->keywordRepository->deleteById($request->id);

            return $this->sendResponse([], 'Delete medicine successfully.');

        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }
    
}
