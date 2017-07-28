<?php

namespace App\Http\Controllers;

use App\Models\Language\Language;
use App\Transformers\LanguageTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\APIController;

class LanguagesController extends APIController
{
    /**
     * Display a listing of the resource.
     * Fetches the records from the database > passes them through fractal for transforming and
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$country = $_GET['country'] ?? false;

    	if($this->api) {
			$languages = Language::with("translations","primaryCountry")
				->when($country, function ($query) use ($country) {
					return $query->where('country_id', $country);
				})->get();
    		return $this->reply(fractal()->collection($languages)->transformWith(new LanguageTransformer())->toArray());
	    }
        return view('languages.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('languages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

	/**
	 * @param $id
	 * @param Language $language
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
	 */
	public function show($id)
    {
    	if($this->api) {
		    $language = new Language();
		    $language = $language->fetchByID($id);
		    return $this->reply(fractal()->item($language)->transformWith(new LanguageTransformer())->toArray());
	    }
        return view('languages.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
		$language = Language::find($id);
	    return view('languages.edit',compact('language'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
