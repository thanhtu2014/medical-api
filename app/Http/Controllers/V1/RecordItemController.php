<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\V1\RecordItemRequest;
use App\Http\Requests\V1\ListRecordItemRequest;

use App\Repositories\Interfaces\RecordItemRepositoryInterface;
use App\Repositories\Interfaces\MediaRepositoryInterface;

use Carbon\Carbon;


class RecordItemController extends BaseController
{
    /**
     * @var RecordItemRepositoryInterface

     */
    protected $recordItemRepository;


    /**
     * RecordItemController constructor.
     * @param RecordItemRepository $recordItemRepository
     */
    public function __construct(RecordItemRepositoryInterface $recordItemRepository)
    {
        $this->recordItemRepository = $recordItemRepository;
    }

    /**
     * @param null
     */
    public function index()
    {
        try {
            $recordItems = $this->recordItemRepository->all();

            return $this->sendResponse($recordItems, 'Get recordItem list successfully.');
        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }

    /**
     *  @param RecordItemRequest $request
     */
    public function update(RecordItemRequest $request)
    {
        try {
            $recordItem = $this->recordItemRepository->findById($request->id);

            if (!$recordItem) {
                return $this->sendError("RecordItem not found with ID : $request->id!", 404);
            }
            $request->validated();

            $input = $request->all();
            $input['user'] = Auth::user()->id;
            $input['new_by'] = Auth::user()->id;
            $input['new_ts'] = Carbon::now();
            $input['upd_by'] = Auth::user()->id;
            $input['upd_ts'] = Carbon::now();

            $recordItem = $this->recordItemRepository->update($request->id, $input);

            if ($recordItem) {
                return $this->sendResponse($recordItem, 'Update recordItem successfully.');
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
            $recordItem = $this->recordItemRepository->findById($request->id);

            if (!$recordItem) {
                return $this->sendError("RecordItem not found with ID : $request->id!", 404);
            }

            $this->recordItemRepository->deleteById($request->id);

            return $this->sendResponse([], 'Delete recordItem successfully.');
        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }
    /**
     *  @param RecordItemRequest $request
     */
    public function store(RecordItemRequest $request)
    {
        try {

            $request->validated();
            // dd($request->begin);
            $input = $request->all();
            $input['type'] = RECORD_DEFAULT_VALUE;
            $input['begin'] = Carbon::createFromFormat('Y-m-d H:i:s', $request->begin);
            $input['end'] = Carbon::createFromFormat('Y-m-d H:i:s', $request->end);
            $input['content'] = 'demo test';
            $input['chg'] = CHG_VALID_VALUE;
            $input['new_by'] = Auth::user()->id;
            $input['new_ts'] = Carbon::now();
            $input['upd_by'] = Auth::user()->id;
            $input['upd_ts'] = Carbon::now();
            // dd($input['begin']);


            $recordItem = $this->recordItemRepository->create($input);
            // dd($recordItem);

            $recordItemId = $recordItem->id;
            $file = $request->file('file');
            $media = app('App\Http\Controllers\V1\MediaController')->storeMedia($file, $recordItemId);
            // dd($media);

            if ($recordItem) {
                return $this->sendResponse($recordItem, $media, 'Create recordItem successfully.');
            }
        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }

    /**
     * @param ListRecordItemRequest $request
     */
    public function getItem(ListRecordItemRequest $request)
    {
        try {
            $recordId = $request->record;
            // dd($recordId = $request->record);
            $recordItem = $this->recordItemRepository->getItem($recordId);
            if ($recordItem) {
                return $this->sendResponse($recordItem, 'Get recordItem on day successfully.');
            }
            return $this->sendError("Not found!", 404);
        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }
}
