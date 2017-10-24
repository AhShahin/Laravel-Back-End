<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

trait ApiResponser {

    protected function successResponse($data, $code) {
        return response()->json($data, $code);
    }

    protected function showAll(Collection $collection, $code = 200){
        if ($collection->isEmpty()) {
            return $this->successResponse(['data' => $collection], $code);
        }

        return $this->successResponse($collection, $code);
    }

    protected function showOne(Model $instance, $code = 200){
        return $this->successResponse($instance, $code);
    }

    protected function paginate(Collection $collection) {
        $rules = [
          'per_page' => 'integer|min:2|max:50'
        ];

        Validator::validate(request()->all(), $rules);

        $page = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;
        if (request()->has('per_page')){
            $perPage = (int)request()->per_page;
        }

        $result = $collection->slice(($page - 1) * $perPage, $perPage)->values();
        $paginated = new LengthAwarePaginator($result, $collection->count(), $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);

        $paginated->appends(request()->all());
        return $paginated;
    }
}