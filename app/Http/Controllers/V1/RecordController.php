<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\BaseController;
use App\Http\Requests\V1\RecordRequest;
use App\Http\Requests\V1\MediaRequest;
use App\Repositories\Interfaces\RecordRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecordController extends BaseController
{

    /**
     * @var RecordRepositoryInterface
     */
    protected $recordRepository;

    /**
     * RecordController constructor.
     * @param RecordRepository $recordRepository
     */
    public function __construct(RecordRepositoryInterface $recordRepository)
    {
        $this->recordRepository = $recordRepository;
    }

    /**
     * @param null
     */
    public function index()
    {
        try {
            $records = $this->recordRepository->allBy([
                'user' => Auth::user()->id,
                'chg' => CHG_VALID_VALUE
            ]);

            return $this->sendResponse($records, 'Get record list successfully.');
        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }

    public function detail($id) 
    {
        try {
            $record = $this->recordRepository->findById($id);

            if($record) {
                return $this->sendResponse($record, 'Get record detail successfully.');
            }

            return $this->sendError("Record not found with ID : $id!", 404);
        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }

    /**
     * @param Request $request
     */
    public function searchRecord(Request $request) 
    {     
        try {
            $recordSearch = $this->recordRepository->Search($request);
            
            if($recordSearch) {
                return $this->sendResponse($recordSearch, 'Search record successfully.');
            }

        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }

    /**
     *  @param RecordRequest $request
     */
    public function store(RecordRequest $request)
    {
        try {
            $request->validated();
            // dd($request->begin);
            $input = $request->all();
            $input['type'] = RECORD_DEFAULT_VALUE;
            $input['begin'] = Carbon::now();
            $input['end'] = Carbon::now();
            $input['user'] = Auth::user()->id;
            $input['visible'] = VISIBLE_INVALID_VALUE;
            $input['chg'] = CHG_INVALID_VALUE;
            $input['new_by'] = Auth::user()->id;
            $input['new_ts'] = Carbon::now();
            $input['upd_by'] = Auth::user()->id;
            $input['upd_ts'] = Carbon::now();
   
            $record = $this->recordRepository->create($input);
            // dd($record);

            // $recordId = $record->id;
            // $recordItem = app('App\Http\Controllers\V1\RecordItemController')->storeItem($recordId);

            // $recordItemId = $recordItem->id;
            // $media = app('App\Http\Controllers\V1\MediaController')->storeMedia($recordItemId);

            if ($record) {
                return $this->sendResponse($record, 'Create record successfully.');
            }
        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }


/**
     *  @param MediaRequest $request
     */
    public function import(MediaRequest $request)
    {
        try {
            $file = $request->file('file');

            $request->validated();
            $input = $request->all();
            $input['title'] = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);;
            $input['type'] = RECORD_DEFAULT_VALUE;
            $input['begin'] = Carbon::now();
            $input['end'] = Carbon::now();
            $input['user'] = Auth::user()->id;
            $input['visible'] = VISIBLE_VALID_VALUE;
            $input['chg'] = CHG_VALID_VALUE;
            $input['new_by'] = Auth::user()->id;
            $input['new_ts'] = Carbon::now();
            $input['upd_by'] = Auth::user()->id;
            $input['upd_ts'] = Carbon::now();
   
            $record = $this->recordRepository->create($input);
            // dd($record);

            $recordId = $record->id;
            
            $recordItem = app('App\Http\Controllers\V1\RecordItemController')->storeItem($recordId);
            //dd($recordItem->getData()->data->id);
     
            $recordItemId = $recordItem->getData()->data->id;
            
            $media = app('App\Http\Controllers\V1\MediaController')->storeMedia($file, $recordItemId);
            // dd($media);
            $data = ['record' => $record, 'recordItem' => $recordItem->getData()->data->content, 'media' => $media->getData()->data->fpath];
            if ($record) {
                return $this->sendResponse($data, 'Import record successfully.');
            }
        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }

    /**
     *  @param RecordRequest $request
     */
    public function save(RecordRequest $request)
    {
        try {
            $record = $this->recordRepository->findBy(['id' => $request->id]);

            if (!$record) {
                return $this->sendError("Record not found with ID : $request->id!", 404);
            }
            
            $request->validated();
            $input['end'] = Carbon::now();
            $input['visible'] = VISIBLE_VALID_VALUE;
            $input['chg'] = CHG_VALID_VALUE;
            $input['user'] = Auth::user()->id;
            $input['new_by'] = Auth::user()->id;
            $input['new_ts'] = Carbon::now();
            $input['upd_by'] = Auth::user()->id;
            $input['upd_ts'] = Carbon::now();

            $record->update($input);

            if ($record) {
                return $this->sendResponse($record, 'Update record successfully.');
            }
        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }

    /**
     *  @param RecordRequest $request
     */
    public function update(RecordRequest $request)
    {
        try {
            $record = $this->recordRepository->findById($request->id);
            if (!$record) {
                return $this->sendError("Record not found with ID : $request->id!", 404);
            }
            $request->validated();
            $input = $request->all();
            $input['user'] = Auth::user()->id;
            $input['new_by'] = Auth::user()->id;
            $input['new_ts'] = Carbon::now();
            $input['upd_by'] = Auth::user()->id;
            $input['upd_ts'] = Carbon::now();

            $record = $this->recordRepository->update($request->id, $input);

            if ($record) {
                return $this->sendResponse($record, 'Update record successfully.');
            }
        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }



    public function updateImage($recordId, $mediaId)
    {
        try {
            $record = $this->recordRepository->findById($recordId);
            if (!$record) {
                return $this->sendError("Record not found with ID : $recordId", 404);
            }
            $input['media'] = $mediaId;
            $input['user'] = Auth::user()->id;
            $input['new_by'] = Auth::user()->id;
            $input['new_ts'] = Carbon::now();
            $input['upd_by'] = Auth::user()->id;
            $input['upd_ts'] = Carbon::now();

            $record = $this->recordRepository->update($recordId, $input);

            if ($record) {
                return $this->sendResponse($record, 'Update record successfully.');
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
            $record = $this->recordRepository->findById($request->id);

            if (!$record) {
                return $this->sendError("Record not found with ID : $request->id!", 404);
            }

            $this->recordRepository->deleteById($request->id);

            return $this->sendResponse([], 'Delete record successfully.');
        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }

    public function hideAndShow($id) 
    {
        try {
            $record = $this->recordRepository->findById($id);

            if (!$record) {
                return $this->sendError("Record not found with ID : $id!", 404);
            }
            $record->visible = $record->visible == VISIBLE_VALID_VALUE ? VISIBLE_INVALID_VALUE: VISIBLE_VALID_VALUE;
            $record->save();
                       
            if($record) {
                return $this->sendResponse($record, 'Get recordItem detail successfully.');
            }

            return $this->sendError("RecordItem not found with ID : $id!", 404);
        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }
}
