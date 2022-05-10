<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\BaseController;
use App\Http\Requests\V1\ImageRequest;
use App\Http\Requests\V1\MediaRequest;
use App\Repositories\Interfaces\MediaRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use URL;

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
            $fileMime = $file->extension();
            $fileName = time() . '.' . $fileMime;
            $filePath = $file->storeAs('audios', $fileName, 'public');
            $input['record_item'] = $recordItemId;
            $input['name'] = time() . '_' . $file->getClientOriginalName();
            $input['fpath'] = URL::to('/') . '/storage/' . $filePath;
            $input['mime'] = MIME_AUDIO_VALUE;
            $input['fdisk'] = URL::to('/') . '/storage/' . 'audios/';
            $input['name'] = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $input['fname'] = $fileName;
            $input['fext'] = $fileMime;
            $input['chg'] = CHG_VALID_VALUE;
            $input['new_by'] = Auth::user()->id;
            $input['new_ts'] = Carbon::now();
            $input['upd_by'] = Auth::user()->id;
            $input['upd_ts'] = Carbon::now();
            // dd($input['fpath']);
            $media = $this->mediaRepository->create($input);

            if ($media) {
                return $this->sendResponse($media, 'Create media successfully.');
            }

        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }

    /**
     *  @param ImageRequest $request
     */
    public function storeImage(ImageRequest $request)
    {
        try {
            $request->validated();
            $file = $request->file('file');
            $fileMime = $file->extension();
            $fileName = time() . '.' . $fileMime;
            $input['mime'] = MIME_IMAGE_VALUE;
            $filePath = $file->storeAs('images', $fileName, 'public');
            $input['fdisk'] = URL::to('/') . '/storage/' . '/' . 'images/';
            $input['fname'] = $fileName;
            $input['fext'] = $fileMime;
            // $input['record_item'] = $recordItemId;
            $input['name'] = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $input['fpath'] = URL::to('/') . '/storage/' . '/' . $filePath;
            $input['chg'] = CHG_VALID_VALUE;
            $input['new_by'] = Auth::user()->id;
            $input['new_ts'] = Carbon::now();
            $input['upd_by'] = Auth::user()->id;
            $input['upd_ts'] = Carbon::now();
            $media = $this->mediaRepository->create($input);
            $recordId = $request->id;
            $mediaId = $media->id;
            $record = app('App\Http\Controllers\V1\RecordController')->updateImage($recordId, $mediaId);
            if ($media) {
                return $this->sendResponse($media, 'Create media successfully.');
            }
        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }

    /**
     *  @param MediaRequest $request
     */
    public function store(MediaRequest $request)
    {
        try {

            $file = $request->file('file');

            $fileMime = $file->extension();
            $fileName = time() . '.' . $fileMime;
            // dd($fileMime);
            if ($fileMime == 'mp3' || $fileMime == 'm4a' || $fileMime == 'mpeg' || $fileMime == 'mpga' || $fileMime == 'wav' || $fileMime == 'aac') {
                $input['mime'] = MIME_AUDIO_VALUE;
                $filePath = $file->storeAs('audios', $fileName, 'public');
                $input['fdisk'] = URL::to('/') . '/storage/' . '/' . 'audios/';

            };
            if ($fileMime == 'jpg' || $fileMime == 'jpeg' || $fileMime == 'png' || $fileMime == 'gif' || $fileMime == 'svg') {
                $input['mime'] = MIME_IMAGE_VALUE;
                $filePath = $file->storeAs('images', $fileName, 'public');
                $input['fdisk'] = URL::to('/') . '/storage/' . '/' . 'images/';
            };
            $input['fname'] = $fileName;
            $input['fext'] = $fileMime;

            // $input['record_item'] = $recordItemId;
            $input['name'] = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $input['fpath'] = URL::to('/') . '/storage/' . '/' . $filePath;
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

    /**
     * @param Request $request
     */
    public function getMedia($recordItemId)
    {
        try {
            $media = $this->mediaRepository->getMedia($recordItemId);
            if ($media) {
                return $this->sendResponse($media, 'Get media successfully.');
            }
            return $this->sendError("Not found!", 404);
        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }

}
