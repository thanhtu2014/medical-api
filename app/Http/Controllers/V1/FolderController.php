<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interfaces\FolderRepositoryInterface;
use App\Http\Requests\V1\FolderRequest;
use App\Http\Controllers\BaseController;
use Carbon\Carbon;

class FolderController extends BaseController
{
    /**
     * @var FolderRepositoryInterface
     */
    protected $folderRepository;

    /**
     * FolderController constructor.
     * @param FolderRepositoryInterface $folderRepository
     */
    public function __construct(FolderRepositoryInterface $folderRepository) 
    {
        $this->folderRepository = $folderRepository;
        
    }

    /**
     * @param null
     */
    public function index() 
    {
        try {
            $folders = $this->folderRepository->allBy([
                'user' => Auth::user()->id,
                'chg' => CHG_VALID_VALUE
        ]);
            return $this->sendResponse($folders, 'Get folder list successfully.');
        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }

    /**
     * @param Request $request
     */
    public function getFolderDetail($id) 
    {
        try {
            $folder = $this->folderRepository->findById($id);

            if($folder) {
                return $this->sendResponse($folder, 'Get folder detail successfully.');
            }

            return $this->sendError("Not found!", 404);
        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }

    /**
     *  @param FolderRequest $request
     */
    public function store(FolderRequest $request)
    {
        try {
            $request->validated();

            $input = $request->all();
            $input['type'] = FOLDER_TYPE_KEY_VALUE;
            $input['color'] = $request->input('color') ? $request->input('color') : COLOR_DEFAULT_VALUE;
            $input['user'] = Auth::user()->id;
            $input['new_by'] = Auth::user()->id;
            $input['upd_by'] = Auth::user()->id;
            $input['upd_ts'] = Carbon::now();

            

            $folder = $this->folderRepository->create($input);

            if($folder) {
                return $this->sendResponse($folder, 'Create folder successfully.');
            }

        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }

    /**
     *  @param FolderRequest $request
     */
    public function update(FolderRequest $request)
    {
        try {
            $folder = $this->folderRepository->findById($request->id);

            if(!$folder) {
                return $this->sendError("Folder not found with ID : $request->id!", 404);
            }
            $request->validated();

            $input = $request->all();
            $input['new_by'] = Auth::user()->id;
            $input['upd_by'] = Auth::user()->id;
            $input['upd_ts'] = Carbon::now();

            $folder = $this->folderRepository->update($request->id, $input);
            if($folder) {
                return $this->sendResponse($folder, 'Update folder successfully.');
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
            $folder = $this->folderRepository->findById($request->id);

            if(!$folder) {
                return $this->sendError("Folder not found with ID : $request->id!", 404);
            }

            $this->folderRepository->deleteById($request->id);

            return $this->sendResponse([], 'Delete folder successfully.');

        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }
}
