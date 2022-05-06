<?php

namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\RecordRepositoryInterface;
use App\Models\Record;
use Illuminate\Http\Request;
use DB;

class RecordRepository extends BaseRepository implements RecordRepositoryInterface
{
/**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Record $model)
    {
        $this->model = $model;
    }

    
    public function Search(Request $request)
    {
        $param = $request->q;

        $account =DB::table('records')
            ->where(['chg' => CHG_VALID_VALUE])
            ->where(function ($query) use ($param) {
                $query->orwhere('title', 'like', '%'.$param. '%' );
                $query->orwhere('hospital', 'like', '%'.$param. '%' );
                $query->orwhere('folder', 'like', '%'.$param. '%' );
                $query->orwhere('people', 'like', '%'.$param. '%' );
                $query->orwhere('media', 'like', '%'.$param. '%' );
            })->get(); 
            
        return $account;
    }
    public function getRecordVisible($id)
    {
        return Record::where(['id' =>  $id, 'visible' => VISIBLE_VALID_VALUE ,  'chg' => CHG_VALID_VALUE])->get();
    }
}