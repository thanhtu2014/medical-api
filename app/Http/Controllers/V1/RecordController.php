<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\BaseController;
use App\Http\Requests\V1\RecordRequest;
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
            $records = $this->recordRepository->all();

            return $this->sendResponse($records, 'Get record list successfully.');
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
     *  @param RecordRequest $request
     */
    public function submitRecord(RecordRequest $request)
    {
        try {
            $record = $this->recordRepository->findById($request->id);

            if (!$record) {
                return $this->sendError("Record not found with ID : $request->id!", 404);
            }
            $request->validated();
            $input['end'] = Carbon::now();
            $input['chg'] = CHG_VALID_VALUE;
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
}
