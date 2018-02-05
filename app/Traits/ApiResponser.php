<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Response;
//Cache
use Illuminate\Support\Facades\Cache;

//Pagination
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponser 
{
	private function successResponse($data, $code){		
		return Response::json($data, $code);	
	}	

	protected function errorResponse($message, $code){
		return Response::json([
			'error' => $message, 
			'code' => $code
		], $code);	
	}

	protected function showAll(Collection $collection, $code = 200)
	{
		if ($collection->isEmpty()) {
			return $this->successResponse(['data' => $collection], $code);
		}
		$transformer = $collection->first()->transformer;

		$collection = $this->filterData($collection, $transformer);
		$collection = $this->sortData($collection, $transformer);
		$collection = $this->paginate($collection, $transformer);

		$collection = $this->transformData($collection, $transformer);

		$collection = $this->cacheResponse($collection);
				
		return $this->successResponse($collection, $code);
	}

	protected function showOne(Model $instance, $code = 200)
	{
		$transformer = $instance->transformer;
		$instance = $this->transformData($instance, $transformer);
		return $this->successResponse($instance, $code);
	}

	protected function showMessage($message, $code = 200){
		return 	$this->successResponse(['data' => $message], $code);
	}

	/**
	 * Transform data
	 * @param null|mixed $data
	 * @param null|callable|\League\Fractal\TransformerAbstract $transformer
	 *
	 * @return array
 	 */
	protected function transformData($data, $transformer)
	{
		$transformation = fractal($data, new $transformer);
		return $transformation->toArray();
	}

	/**
	 * Sorting DataBy return datas ex: {{urlApi}}/users?sort_by=name
	 * @param \Illuminate\Support\Collection
	 * @param \League\Fractal\TransformerAbstract $transformer
 	 */
	protected function sortData(Collection $collection, $transformer)
	{
		// if we have a sort_by params in url we can proceed
		if (request()->has('sort_by')) {
			// App\Transformers\*Transformer::originalAttribute($index)
			$attribute = $transformer::originalAttribute(request()->sort_by); 
			$collection = $collection->sortBy->{$attribute};
		}
		return $collection;
	}

	/**
	 * Filter Data return datas ex: {{urlApi}}/users?isVerifed=0
	 * @param \Illuminate\Support\Collection
	 * @param \League\Fractal\TransformerAbstract $transformer
 	 */
	protected function filterData(Collection $collection, $transformer)
	{
		foreach (request()->query() as $query => $value) {
			$attribute = $transformer::originalAttribute($query);
			// if attribute and value are set 
			if (isset($attribute, $value)) {
				$collection = $collection->where($attribute, $value);
			}
		}
		return $collection;
	}

	/**
	 * Filter Data return datas ex: {{urlApi}}/users?per_page=8
	 * @param \Illuminate\Support\Collection
	 * @param \League\Fractal\TransformerAbstract $transformer
 	 */
	protected function paginate(Collection $collection)
	{
		$rules = [
			'per_page' => 'integer|min:2|max:50',
		];
		Validator::validate(request()->all(), $rules);

		//resolveCurrentPage current page
		$page = LengthAwarePaginator::resolveCurrentPage();
		
		$perPage = 15;
		if (request()->has('per_page')) {
			$perPage = (int) request()->per_page;
		}

		$results = $collection->slice(($page - 1) * $perPage, $perPage)->values();

		$paginated = new LengthAwarePaginator($results, $collection->count(), $perPage, $page, [
			'path' => LengthAwarePaginator::resolveCurrentPath(),
		]);

		$paginated->appends(request()->all());

		return $paginated;
	}


	protected function cacheResponse($data)
	{
		// url = http://restfullapi.local/api/users
		$url = request()->url();
		// queryParams = ["isVerified" => "0","per_page" => "2"]
		$queryParams = request()->query();

		// Permet de recupérer le cache pour peu importe l'ordre des paramètres dans l'url ex : ?per_page=8&isVerified=0 == ?isVerified=0&per_page=8
		ksort($queryParams);

		//Génère une chaîne de requête en encodage URL array to foo=bar&baz=boom&cow=milk&php=hypertext+processor
		$queryString = http_build_query($queryParams);

		//On recrée l'url
		$fullUrl = "{$url}?{$queryString}";

		//On ajoute au cache url et data puis on retourne
		return Cache::remember($fullUrl, 30/60, function() use($data) {
			return $data;
		});
	}
}