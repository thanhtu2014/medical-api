<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Repositories\Interfaces\FamilyRepositoryInterface;
use App\Http\Requests\V1\FamilyRequest;
use App\Http\Controllers\BaseController;
use Carbon\Carbon;

class FamilyController extends BaseController
{
    /**
     * @var FamilyRepositoryInterface
     */
    protected $familyRepository;

    /**
     * FamilyController constructor.
     * @param FamilyRepositoryInterface $familyRepository
     */
    public function __construct(FamilyRepositoryInterface $familyRepository) 
    {
        $this->familyRepository = $familyRepository;
    }

    /**
     * @param null
     */
    public function index()
    {
        try {
            $family = $this->familyRepository->getAll();

            return $this->sendResponse($family, 'Get family list successfully.');
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
            $family = $this->familyRepository->getDetail($id);

            if($family) {
                return $this->sendResponse($family, 'Get family detail successfully.');
            }

            return $this->sendError("Not found!", 404);
        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }

    /**
     *  @param FamilyRequest $request
     */
    public function store(Request $request)
    {
        try {
            if(exist($request->ID)) {
                $request->validate([
                    'title' => 'required|min:3|max:128',
                    'id' => 'required|min:3|max:1024',
                    'remark' => 'min:3|max:1024',
                ]);
            }
            // $validated = $request->validated();
            $validated = $request->accepted('email');
            dd($validated);
            $request->validated()->except('email');

            $input = $request->all();
            dd($input);
            $input['type'] = FAMILY_KEY_VALUE;
            $input['user'] = Auth::user()->id;
            $input['new_by'] = Auth::user()->id;
            $input['upd_by'] = Auth::user()->id;
            $input['upd_ts'] = Carbon::now();

            $family = $this->familyRepository->create($input);

            if($family) {
                return $this->sendResponse($family, 'Create family successfully.');
            }
        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }

    /**
     *  @param FamilyRequest $request
     */
    public function update(FamilyRequest $request)
    {
        try {
            $hospital = $this->familyRepository->getDetail($request->id);

            if(!$hospital) {
                return $this->sendError("Hospital not found with ID : $request->id!", 404);
            }
            $request->validated();

            $input = $request->all();
            $input['new_by'] = Auth::user()->id;
            $input['upd_by'] = Auth::user()->id;
            $input['upd_ts'] = Carbon::now();

            $hospital = $this->familyRepository->update($request->id, $input);

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
            $family = $this->familyRepository->getDetail($request->id);

            if(!$family) {
                return $this->sendError("Family not found with ID : $request->id!", 404);
            }

            $this->familyRepository->delete($request->id);

            return $this->sendResponse([], 'Delete family successfully.');
        } catch (\Exception $e) {
            throw $e;
            return $this->sendError("Something when wrong!", 500);
        }
    }
    
}
