<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\V1\RecordItemRequest;
use App\Http\Requests\V1\ListRecordItemRequest;

use App\Repositories\Interfaces\RecordItemRepositoryInterface;
use App\Repositories\Interfaces\MediaRepositoryInterface;
use App\Repositories\Interfaces\RecordRepositoryInterface;

use Carbon\Carbon;


class RecordItemController extends BaseController
{
    /**
     * @var RecordItemRepositoryInterface
     * @var MediaRepositoryInterface
     * @var RecordRepositoryInterface
     */
    protected $rcordItemRepository;
    protected $mediaRepository;
    protected $recordRepository;

    /**
     * RecordItemController constructor.
     * @param RecordItemRepository $recordItemRepository
     */
    public function __construct(RecordItemRepositoryInterface $recordItemRepository, MediaRepositoryInterface $mediaRepository, RecordRepositoryInterface $recordRepository)
    {
        $this->recordItemRepository = $recordItemRepository;
        $this->mediaRepository = $mediaRepository;
        $this->recordRepository = $recordRepository;
    }

    /**
     * @param null
     */
    public function index()
    {
        try {
            $recordItems = $this->recordItemRepository->allBy([
                'chg' => CHG_VALID_VALUE
            ]);

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


    public function storeItem($recordID)
    {
        try {
            $input['record'] = $recordID;
            $input['type'] = RECORD_DEFAULT_VALUE;
            $input['begin'] = Carbon::now();
            $input['end'] = Carbon::now();
            $input['content'] = '日本国民は、正当に選挙された国会における代表者を通じて行動し、われらとわれらの子孫のために、諸国民との協和による成果と、わが国全土にわたつて自由のもたらす恵沢を確保し、政府の行為によつて再び戦争の惨禍が起ることのないやうにすることを決意し、ここに主権が国民に存することを宣言し、この憲法を確定する。そもそも国政は、国民の厳粛な信託によるものであつて、その権威は国民に由来し、その権力は国民の代表者がこれを行使し、その福利は国民がこれを享受する。これは人類普遍の原理であり、この憲法は、かかる原理に基くものである。われらは、これに反する一切の憲法、法令及び詔勅を排除する。日本国民は、恒久の平和を念願し、人間相互の関係を支配する崇高な理想を深く自覚するのであつて、平和を愛する諸国民の公正と信義に信頼して、われらの安全と生存を保持しようと決意した。われらは、平和を維持し、専制と隷従、圧迫と偏狭を地上か';
            $input['chg'] = CHG_VALID_VALUE;
            $input['new_by'] = Auth::user()->id;
            $input['new_ts'] = Carbon::now();
            $input['upd_by'] = Auth::user()->id;
            $input['upd_ts'] = Carbon::now();
            // dd($input['begin']);


            $recordItem = $this->recordItemRepository->create($input);
            // dd($recordItem);

            if ($recordItem) {
                return $this->sendResponse($recordItem, 'Create recordItem successfully.');
            }
        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }
 
    public function getItem($id)
    {
        try {
            $recordId = $id;
            // dd($recordId);
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

    public function getItemVisible($id)
    {
        try {
            
            $record = $this->recordRepository->getRecordVisible($id);
            if ($record->toArray() != null) {
                $recordItem = $this->recordItemRepository->getItem($id);
                $data = ['recordItem' => $recordItem];
            } else{
                $recordItem = $this->recordItemRepository->getItem($id);
                $data = ['recordItem' => $recordItem->makeHidden(['begin', 'end'])];
            }
            
            if ($data) {
                return $this->sendResponse($data, 'Get recordItem on day successfully.');
            }
            return $this->sendError("Not found!", 404);
        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }

    public function detail($id) 
    {
        try {
            $recordItem = $this->recordItemRepository->findById($id);
            $media = $this->mediaRepository->getMedia($id);
            $data = ['recordItem' => $recordItem, 'media' => $media];
            if($data) {
                return $this->sendResponse($data, 'Get recordItem detail successfully.');
            }

            return $this->sendError("RecordItem not found with ID : $id!", 404);
        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }

    
}
