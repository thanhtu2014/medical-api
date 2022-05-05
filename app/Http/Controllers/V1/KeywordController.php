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
use Validator;
use Storage;
use DB;

class KeywordController extends BaseController
{
    /**
     * @var KeywordRepositoryInterface
     */
    private $keywordRepository;

    /**
     * @var MediaKeywordRepositoryInterface
     */
    private $mediaKeywordRepository;

    /**
     * KeywordController constructor.
     * @param KeywordRepositoryInterface $keywordRepository
     * @param MediaKeywordRepositoryInterface $mediaKeywordRepository
     */
    public function __construct(
        KeywordRepositoryInterface $keywordRepository,
        MediaKeywordRepositoryInterface $mediaKeywordRepository
    ) {
        $this->keywordRepository = $keywordRepository;
        $this->mediaKeywordRepository = $mediaKeywordRepository;
    }

    /**
     * @param null
     */
    public function index()
    {
        try {
            $keywords = $this->keywordRepository->allBy([
                'type' => MEDICINE_KEY_VALUE,
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
            $keyword = $this->keywordRepository->findById($id, ['*'], ['mediaKeyword']);

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
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(),[ 
                'file' => 'required|image:jpeg,png,jpg,gif,svg|max:2048',
                'name'  => 'required|min:3|max:128',
                'vx01'  => 'required|max:128',
                'vx02'  => 'required|max:128',
                'remark' => 'min:3|max:1024'
            ]);
    
            if($validator->fails()) {          
                return $this->sendError(['error'=>$validator->errors()], 401);                     
            }

            $input = $request->all();
            $input['type'] = MEDICINE_KEY_VALUE;
            $input['color'] = $request->input('color') ? $request->input('color') : 'COLOR_DEFAULT_VALUE;';
            $input['user'] = Auth::user()->id;
            $input['new_by'] = Auth::user()->id;
            $input['upd_by'] = Auth::user()->id;
            $input['upd_ts'] = Carbon::now();

            $keyword = $this->keywordRepository->create($input);

            if ($file = $request->file('file') && $keyword) {
                $path = Storage::putFile('keywords', $request->file('file'));
                $name = $request->file->getClientOriginalName();
                $mine = $request->file->getClientmimeType();
                $ext = $request->file->getExtension();
    
                //store your file into directory and db
                $input_media['keyword'] = $keyword->id;
                $input_media['fpath']   = $path;
                $input_media['fname']   = $name;
                $input_media['fdisk']   = $path;
                $input_media['name']    = $name;
                $input_media['mime']    = $mine;
                $input_media['fext']    = $ext;
                $input_media['new_by']  = Auth::user()->id;
                $input_media['upd_by']  = Auth::user()->id;
                $input_media['upd_ts']  = Carbon::now();

                $mediaKeyword = $this->mediaKeywordRepository->create($input_media);
            }

            DB::commit();
            return $this->sendResponse($keyword, 'Create medicine successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
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
