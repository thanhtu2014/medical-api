<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interfaces\DoctorRepositoryInterface;
use App\Http\Requests\V1\DoctorRequest;
use App\Http\Controllers\BaseController;
use Carbon\Carbon;

class DoctorController extends BaseController
{
    /**
     * @var DoctorRepositoryInterface
     */
    protected $doctorRepository;

    /**
     * DoctorController constructor.
     * @param DoctorRepositoryInterface $doctorRepository
     */
    public function __construct(DoctorRepositoryInterface $doctorRepository) 
    {
        $this->doctorRepository = $doctorRepository;
    }

    /**
     * @param null
     */
    public function index()
    {
        try {
            $doctor = $this->doctorRepository->getAll();

            return $this->sendResponse($doctor, 'Get doctor list successfully.');
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
            $doctor = $this->doctorRepository->getDetail($id);

            if($doctor) {
                return $this->sendResponse($doctor, 'Get people detail successfully.');
            }

            return $this->sendError("Not found!", 404);
        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }

    /**
     *  @param DoctorRequest $request
     */
    public function store(DoctorRequest $request)
    {
        try {
            $request->validated();

            $input = $request->all();
            $input['type'] = HOSPITAL_OR_DOCTOR_KEY_VALUE;
            $input['user'] = Auth::user()->id;
            $input['new_by'] = Auth::user()->id;
            $input['upd_by'] = Auth::user()->id;
            $input['upd_ts'] = Carbon::now();

            $doctor = $this->doctorRepository->create($input);

            if($doctor) {
                return $this->sendResponse($doctor, 'Create doctor/family successfully.');
            }

        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }

    /**
     *  @param DoctorRequest $request
     */
    public function update(DoctorRequest $request)
    {
        try {
            $doctor = $this->doctorRepository->getDetail($request->id);

            if(!$doctor) {
                return $this->sendError("Doctor not found with ID : $request->id!", 404);
            }
            $request->validated();

            $input = $request->all();
            $input['new_by'] = Auth::user()->id;
            $input['upd_by'] = Auth::user()->id;
            $input['upd_ts'] = Carbon::now();

            $doctor = $this->doctorRepository->update($request->id, $input);

            if($doctor) {
                return $this->sendResponse($doctor, 'Update doctor successfully.');
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
            $doctor = $this->doctorRepository->getDetail($request->id);

            if(!$doctor) {
                return $this->sendError("Doctor not found with ID : $request->id!", 404);
            }

            $this->doctorRepository->delete($request->id);

            return $this->sendResponse([], 'Delete Doctor successfully.');
        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }
    
}
