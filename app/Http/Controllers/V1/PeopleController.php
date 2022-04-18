<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interfaces\PeopleRepositoryInterface;
use App\Http\Requests\V1\PeopleRequest;
use App\Http\Controllers\BaseController;
use Carbon\Carbon;

class PeopleController extends BaseController
{
    /**
     * @var PeopleRepositoryInterface
     */
    protected $peopleRepository;

    /**
     * @var type
     */
    private $type;

    /**
     * PeopleController constructor.
     * @param PeopleRepositoryInterface $peopleRepository
     */
    public function __construct(PeopleRepositoryInterface $peopleRepository) 
    {
        $this->peopleRepository = $peopleRepository;
        $this->type = get_current_action_view_type();
    }

    /**
     * @param null
     */
    public function index()
    {
        try {
            $people = $this->peopleRepository->getListByType($this->type);

            return $this->sendResponse($people, 'Get people list successfully.');
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
            $people = $this->peopleRepository->getDetail($id, $this->type);

            if($people) {
                return $this->sendResponse($people, 'Get people detail successfully.');
            }

            return $this->sendError("Not found!", 404);
        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }

    /**
     *  @param PeopleRequest $request
     */
    public function store(PeopleRequest $request)
    {
        try {
            $request->validated();

            $input = $request->all();
            $input['type'] = get_current_action_view_type();
            $input['user'] = Auth::user()->id;
            $input['new_by'] = Auth::user()->id;
            $input['upd_by'] = Auth::user()->id;
            $input['upd_ts'] = Carbon::now();

            $doctor = $this->peopleRepository->create($input);

            if($doctor) {
                return $this->sendResponse($doctor, 'Create doctor/family successfully.');
            }

        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }

    /**
     *  @param PeopleRequest $request
     */
    public function update(PeopleRequest $request)
    {
        try {
            $hospital = $this->peopleRepository->getDetail($request->id);

            if(!$hospital) {
                return $this->sendError("Hospital not found with ID : $request->id!", 404);
            }
            $request->validated();

            $input = $request->all();
            $input['new_by'] = Auth::user()->id;
            $input['upd_by'] = Auth::user()->id;
            $input['upd_ts'] = Carbon::now();

            $hospital = $this->peopleRepository->update($request->id, $input);

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
            $currentType = get_current_action_view_type();
            $people = $this->peopleRepository->getDetail($request->id, $this->type);

            if(!$people) {
                return $this->sendError("Doctor/Family not found with ID : $request->id!", 404);
            }

            $this->peopleRepository->delete($request->id);

            return $this->sendResponse([], 'Delete Doctor/Family successfully.');

        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }
    
}
