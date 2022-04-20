<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interfaces\HospitalRepositoryInterface;
use App\Http\Requests\V1\HospitalRequest;
use App\Http\Controllers\BaseController;
use Carbon\Carbon;

class HospitalController extends BaseController
{
    /**
     * @var HospitalRepositoryInterface
     */
    private $hospitalRepository;

    /**
     * @var type
     */
    private $type;

    /**
     * HospitalController constructor.
     * @param HospitalRepositoryInterface $hospitalRepository
     */
    public function __construct(HospitalRepositoryInterface $hospitalRepository) 
    {
        $this->hospitalRepository = $hospitalRepository;
        $this->type = get_current_action_view_type();
    }

    /**
     * @param null
     */
    public function index() 
    {
        try {
            $hospitals = $this->hospitalRepository->getHospitalListByType($this->type);

            return $this->sendResponse($hospitals, 'Get hospital list successfully.');
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
            $hospital = $this->hospitalRepository->getDetail($id, $this->type);

            if($hospital) {
                return $this->sendResponse($hospital, 'Get hospital detail successfully.');
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
    public function store(HospitalRequest $request)
    {
        try {
            $request->validated();

            $input = $request->all();
            $input['type'] = HOSPITAL_OR_DOCTOR_KEY_VALUE;
            $input['user'] = Auth::user()->id;
            $input['new_by'] = Auth::user()->id;
            $input['upd_by'] = Auth::user()->id;
            $input['upd_ts'] = Carbon::now();

            $hospital = $this->hospitalRepository->create($input);

            if($hospital) {
                return $this->sendResponse($hospital, 'Create hospital successfully.');
            }

        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }

    /**
     *  @param HospitalRequest $request
     */
    public function update(HospitalRequest $request)
    {
        try {
            $hospital = $this->hospitalRepository->getDetail($request->id);

            if(!$hospital) {
                return $this->sendError("Hospital not found with ID : $request->id!", 404);
            }
            $request->validated();

            $input = $request->all();
            $input['user'] = Auth::user()->id;
            $input['new_by'] = Auth::user()->id;
            $input['upd_by'] = Auth::user()->id;
            $input['upd_ts'] = Carbon::now();

            $hospital = $this->hospitalRepository->update($request->id, $input);

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
            $hospital = $this->hospitalRepository->getDetail($request->id);

            if(!$hospital) {
                return $this->sendError("Hospital not found with ID : $request->id!", 404);
            }

            $this->hospitalRepository->delete($request->id);

            return $this->sendResponse([], 'Delete hospital successfully.');

        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }
    
}
