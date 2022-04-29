<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\V1\MediaRequest;
use App\Repositories\Interfaces\MediaRepositoryInterface;
use Carbon\Carbon;


class MediaController extends BaseController
{
    /**
     * @var MediaRepositoryInterface
     */
    protected $mediaRepository;

    /**
     * ScheduleController constructor.
     * @param ScheduleRepository $scheduleRepository
     */
    public function __construct(MediaRepositoryInterface $mediaRepository)
    {
        $this->mediaRepository = $mediaRepository;
    }

     /**
     *  @param MediaRequest $request
     */
    public function storeMedia($file, $recordItemId)
    {
        try {
       
            // $audio = $request->file('fpath');
            $fileName = time().'_'.preg_replace('/\s+/', '', $file->getClientOriginalName());
            $filePath = $file->storeAs('uploads', $fileName, 'public');
            $input['record_item'] = $recordItemId;
            $input['name'] = time().'_'.$file->getClientOriginalName();
            $input['fpath'] = '/storage/' . $filePath;
            $input['chg'] = CHG_VALID_VALUE;
            $input['new_by'] = Auth::user()->id;
            $input['new_ts'] = Carbon::now();
            $input['upd_by'] = Auth::user()->id;
            $input['upd_ts'] = Carbon::now();

            $media = $this->mediaRepository->create($input);

            if ($media) {
                return $this->sendResponse($media, 'Create media successfully.');
            }
        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }
}
