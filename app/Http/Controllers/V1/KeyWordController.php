<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interfaces\KeyWordRepositoryInterface;
use App\Http\Requests\V1\KeyWordRequest;
use App\Http\Controllers\BaseController;
use Carbon\Carbon;

class KeyWorkController extends BaseController
{
    /**
     * @var KeyWordRepositoryInterface
     */
    private $peopleRepository;

    private $type;

    /**
     * KeyWorkController constructor.
     * @param KeyWordRepositoryInterface $peopleRepository
     */
    public function __construct(KeyWordRepositoryInterface $peopleRepository) 
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
            $currentType = get_current_action_view_type();
            
            $people = $this->peopleRepository->getPeopleListByType($currentType);

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
            $currentType = get_current_action_view_type();

            $people = $this->peopleRepository->getDetail($id, $currentType);

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
     *  @param KeyWordRequest $request
     */
    public function store(KeyWordRequest $request)
    {
        try {
            $request->validated();

            $input = $request->all();
            $input['type'] = HOSPITAL_TYPE_VALUE;
            $input['new_by'] = Auth::user()->id;
            $input['upd_by'] = Auth::user()->id;
            $input['upd_ts'] = Carbon::now();

            $hospital = $this->peopleRepository->create($input);

            if($hospital) {
                return $this->sendResponse($hospital, 'Create hospital successfully.');
            }

        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }

    /**
     *  @param KeyWordRequest $request
     */
    public function update(KeyWordRequest $request)
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
            $hospital = $this->peopleRepository->getDetail($request->id);

            if(!$hospital) {
                return $this->sendError("Hospital not found with ID : $request->id!", 404);
            }

            $this->peopleRepository->delete($request->id);

            return $this->sendResponse([], 'Delete hospital successfully.');

        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }
    
}
