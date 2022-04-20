<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interfaces\ScheduleRepositoryInterface;
use App\Http\Requests\V1\ScheduleRequest;
use App\Http\Controllers\BaseController;
use App\Models\Hospital;
use Carbon\Carbon;
// use DateTime;

class ScheduleController extends BaseController
{
    /**
     * @var ScheduleRepositoryInterface
     */
    protected $scheduleRepository;

    /**
     * ScheduleController constructor.
     * @param ScheduleRepository $scheduleRepository
     */
    public function __construct(ScheduleRepositoryInterface $scheduleRepository) 
    {
        $this->scheduleRepository = $scheduleRepository;
    }

    /**
     * @param null
     */
    public function index() 
    {
        try {
            $schedules = $this->scheduleRepository->getAll();
            // dd($schedules);
            return $this->sendResponse($schedules, 'Get schedule list successfully.');
        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }

    /**
     * @param Request $request
     */
    public function getScheduleDetail(Request $request) 
    {
        try {
            $schedule = $this->scheduleRepository->getDetail($request->id);

            if($schedule) {
                return $this->sendResponse($schedule, 'Get schedule detail successfully.');
            }
            return $this->sendError("Not found!", 404);
        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }

    /**
     *  @param HospitalRequest $request
     */
    public function store(ScheduleRequest $request)
    {
        try {
            $request->validated();

            $input = $request->all();
            $input['type'] = '';
            $input['date']= Carbon::createFromFormat('Y-m-d H:i', $request->date);
            $input['color'] = '';
            $input['new_by'] = Auth::user()->id;
            $input['new_ts'] = Carbon::now();
            $input['upd_by'] = Auth::user()->id;
            $input['upd_ts'] = Carbon::now();

            $schedule = $this->scheduleRepository->create($input);

            if($schedule) {
                return $this->sendResponse($schedule, 'Create hospital successfully.');
            }

        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }

    /**
     *  @param HospitalRequest $request
     */
    public function update(ScheduleRequest $request)
    {
        try {
            $schedule = $this->scheduleRepository->getDetail($request->id);

            if(!$schedule) {
                return $this->sendError("Schedule not found with ID : $request->id!", 404);
            }
            $request->validated();

            $input = $request->all();
            $input['new_by'] = Auth::user()->id;
            $input['new_ts'] = Carbon::now();
            $input['upd_by'] = Auth::user()->id;
            $input['upd_ts'] = Carbon::now();

            $schedule = $this->scheduleRepository->update($request->id, $input);

            if($schedule) {
                return $this->sendResponse($schedule, 'Update schedule successfully.');
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
            $schedule = $this->scheduleRepository->getDetail($request->id);

            if(!$schedule) {
                return $this->sendError("Schedule not found with ID : $request->id!", 404);
            }

            $this->scheduleRepository->delete($request->id);

            return $this->sendResponse([], 'Delete schedule successfully.');

        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }
}
