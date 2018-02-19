<?php

namespace App\Http\Controllers;

use App\Models\Bible\Bible;
use App\Models\Bible\BibleBook;
use App\Models\Bible\BibleEquivalent;
use App\Models\Bible\BibleFileset;
use App\Models\Bible\Book;
use App\Models\Language\Alphabet;
use App\Models\Language\Language;
use App\Models\Organization\OrganizationTranslation;
use App\Models\User\Access;
use App\Transformers\BibleTransformer;
use App\Transformers\BooksTransformer;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;

class BiblesController extends APIController
{


	/**
	 *
	 * Display a listing of the bibles.
	 *
	 * @deprecated status (optional): [live|disabled|incomplete|waiting_review|in_review|discontinued] Publishing status of volume. The default is 'live'.
	 * @deprecated dbp_agreement (optional): [true|false] Whether or not a DBP Agreement has been executed between FCBH and the organization to whom the volume belongs.
	 * @deprecated expired (optional): [true|false] Whether the volume as passed its expiration or not.
	 * @deprecated resolution (optional): [lo|med|hi] Currently used for video volumes as they can be available in different resolutions, basically conforming to the loose general categories of low, medium, and high resolution. Low resolution is geared towards devices with smaller screens.
	 * @deprecated delivery (optional): [web|web_streaming|download|download_text|mobile|sign_language|streaming_url|local_bundled|podcast|mp3_cd|digital_download| bible_stick|subsplash|any|none] a criteria for approved delivery method. It is possible to OR these methods together using '|', such as "delivery=streaming_url|mobile".  'any' means any of the supported methods (this list may change over time) i.e. approved for something. 'none' means volumes that are not approved for any of the supported methods. All volumes are returned by default.
	 * @param dam_id (optional): the volume internal DAM ID. Can be used to restrict the response to only DAM IDs that contain with 'N2' for example
	 * @param fcbh_id (optional): the volume FCBH DAM ID. Can be used to restrict the response to only FCBH DAM IDs that contain with 'N2' for example
	 * @param media (optional): [text|audio|video] the format of assets the caller is interested in. This specifies if you only want volumes available in text or volumes available in audio.
	 * @param language (optional): Filter the versions returned to a specified native or English language language name. For example return all the 'English' volumes.
	 * @param full_word (optional): [true|false] Consider the language name as being a full word. For instance, when false, 'new' will return volumes where the string 'new' is anywhere in the language name, like in "Newari" and "Awa for Papua New Guinea". When true, it will only return volumes where the language name contains the full word 'new', like in "Awa for Papua New Guinea". Default is false.
	 * @param language_code (optional): the three letter language code.
	 * @param language_family_code (optional): the three letter language code for the language family.
	 * @param updated (optional): YYYY-MM-DD. This is used to get volumes that were modified since the specified date.
	 * @param organization_id (optional): Organization id of volumes to return.
	 * @param sort_by (optional): [ dam_id | volume_name | language_name | language_english | language_family_code | language_family_name | version_code | version_name | version_english ] Primary criteria by which to sort.  The default is 'dam_id'.
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
	 */
	public function index()
    {
	    // Return the documentation if it's not an API request
	    if(!$this->api) return view('bibles.index');

	    $dam_id = checkParam('dam_id', null, 'optional') ?? checkParam('fcbh_id', null,'optional');
	    $media = checkParam('media', null, 'optional');
	    $language = checkParam('language', null, 'optional');
	    $full_word = checkParam('full_word', null, 'optional');
	    $iso = checkParam('language_family_code', null, 'optional') ?? checkParam('language_code', null, 'optional');
	    $updated = checkParam('updated', null, 'optional');
	    $organization = checkParam('organization_id', null, 'optional');
		$sort_by = checkParam('sort_by', null, 'optional');
		// hide cache
		//\Cache::forget($this->v.'_bibles_'.$dam_id.$media.$language.$full_word.$iso.$updated.$organization.$sort_by);
	    //return \Cache::remember($this->v.'_bibles_'.$dam_id.$media.$language.$full_word.$iso.$updated.$organization.$sort_by, 2400, function () use ($dam_id, $media, $language, $full_word, $iso, $updated, $organization, $sort_by) {
			$access = Access::where('key_id',$this->key)->where('access_type','access_api')->where('access_granted',true)->get()->pluck('bible_id');

	        $bibles = Bible::with('currentTranslation','vernacularTranslation','filesets.meta','language')
		        ->has('filesets.files')
		        ->when($iso, function($q) use ($iso){
			        $q->where('iso', $iso);
		        })
				->where('open_access', 1)->orWhereIn('id',$access)
			    ->when($organization, function($q) use ($organization) {
				    $q->where('organization_id', '>=', $organization);
			    })->when($dam_id, function($q) use ($dam_id) {
				    $q->where('id', $dam_id);
			    })->when($media, function($q) use ($media) {
				    switch ($media) {
					    case "video": {$q->has('filesetFilm'); break;}
					    case "audio": {$q->has('filesetAudio');break;}
					    case "text":  {$q->has('filesetText'); break;}
				    }
			    })->when($updated, function($q) use ($updated) {
				    $q->where('updated_at', '>', $updated);
			    })->when($sort_by, function($q) use ($sort_by){
				    $q->orderBy($sort_by);
			    })
		        ->orderBy('priority','desc')
	            ->get();

	        // Filter by $language
			if(isset($language)) {
				$bibles->load('language.alternativeNames');
				$bibles = $bibles->filter(function($bible) use ($language,$full_word) {
					$altNameList = [];
					if(isset($bible->language->alternativeNames)) $altNameList = $bible->language->alternativeNames->pluck('name')->toArray();
					if(isset($full_word)) return ($bible->language->name == $language) || in_array($language, $altNameList);
					return (stripos($bible->language->name, $language) || ($bible->language->name == $language) || stripos(implode($altNameList), $language));
				});
			}

			if($this->v == 2) $bibles->load('language.parent.parentLanguage','alphabet','organizations');
			return $this->reply(fractal()->collection($bibles)->transformWith(new BibleTransformer())->serializeWith($this->serializer)->toArray());
	    //});
    }


	/**
	 *
	 * A Route to Review The Last 500 Recent Changes to The Bible Resources
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
	 */
	public function history()
    {
    	if(!$this->api) return view('bibles.history');

		$limit = checkParam('limit', null, 'optional') ?? 500;
		$bibles = Bible::select(['id','updated_at'])->take($limit)->get();
		return $this->reply(fractal()->collection($bibles)->transformWith(new BibleTransformer())->serializeWith($this->serializer)->toArray());
    }

	/**
	 *
	 * Get the list of versions defined in the system
	 *
	 * @param code (optional): Get the entry for a three letter version code.
	 * @param name (optional): Get the entry for a part of a version name in either native language or English.
	 * @param sort_by (optional): [code|name|english] Primary criteria by which to sort. 'name' refers to the native language name. The default is 'english'.
	 *
	 * @return json
	 */
	public function libraryVersion()
	{
		$code = checkParam('code', null, 'optional');
		$name = checkParam('name', null, 'optional');
		$sort = checkParam('sort_by', null, 'optional');
		$versions = collect(json_decode(file_get_contents(public_path('static/version_listing.json'))));
		if(isset($code)) $versions = $versions->where('version_code',$code)->flatten();
		if(isset($name)) $versions = $versions->filter(function ($item) use ($name) { return false !== stristr($item->version_name, $name);})->flatten();
		if(isset($sort)) $versions = $versions->sortBy($sort);
		return $this->reply($versions);
	}

	/**
	 * @return mixed
	 */
	public function libraryMetadata()
	{
		$dam_id = checkParam('dam_id', null, 'optional');

		if($dam_id == null) {
			$bibles = Bible::with('organizations')->get();
			return $this->reply(fractal()->collection($bibles)->serializeWith($this->serializer)->transformWith(new BibleTransformer())->toArray());
		}

		$bible = Bible::with('organizations')->find($dam_id);
		return $this->reply(fractal()->item($bible)->serializeWith($this->serializer)->transformWith(new BibleTransformer())->toArray());

	}

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {

	    request()->validate([
		    'id'                      => 'required|unique:bibles,id|max:24',
		    'iso'                     => 'required|exists:languages,iso',
		    'translations.*.name'     => 'required',
		    'translations.*.iso'      => 'required|exists:languages,iso',
		    'date'                    => 'integer',
	    ]);

	    $bible = \DB::transaction(function () {
		    $bible = new Bible();
		    $bible = $bible->create(request()->only(['id','date','script','portions','copyright','derived','in_progress','notes','iso']));
		    $bible->translations()->createMany(request()->translations);
		    $bible->organizations()->attach(request()->organizations);
		    $bible->equivalents()->createMany(request()->equivalents);
		    $bible->links()->createMany(request()->links);
		    return $bible;
	    });

	    return redirect()->route('view_bibles.show', ['id' => $bible->id]);
    }

    /**
     * Description:
     * Display the bible meta data for the specified ID.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
	    $bible = Bible::with('filesets','translations','books')->find($id);
	    if(!$bible) return $this->setStatusCode(404)->replyWithError("Bible not found for ID: $id");
    	if(!$this->api) return view('bibles.show',compact('bible'));

		return $this->reply(fractal()->item($bible)->serializeWith($this->serializer)->transformWith(new BibleTransformer())->toArray());
    }

	public function podcast($id)
	{
		$fileset = BibleFileset::with('files.currentTitle','bible')->find($id);
		if(!$fileset) return $this->replyWithError("No Fileset exists for this ID");
		$bible = $fileset->bible->first();
		if(!$bible) return $this->replyWithError("No Bible has been attached to this fileset");

		$site_url = env('APP_URL_PODCAST') ?? "http://www.faithcomesbyhearing.com";
		$site_contact = 'alan@fcbhmail.com';
		$meta = null;

		$rootElementName = 'rss';
		$rootAttributes = [
			'xmlns:itunes' => "http://www.itunes.com/dtds/podcast-1.0.dtd",
			'xmlns:atom'   => "http://www.w3.org/2005/Atom",
			'xmlns:media'  => "http://search.yahoo.com/mrss/",
			'version'      => "2.0"
		];
		$meta['channel']['title'] = $bible->translations->where('iso',$bible->iso)->first()->name ?? $bible->where('iso',"eng")->first()->name;
		$meta['channel']['description'] = $bible->translations->where('iso',$bible->iso)->first()->description ?? $bible->where('iso',"eng")->first()->description;
		$meta['channel']['link'] = $site_url;
		$meta['channel']['atom:link']['_attributes'] = [
			'href'  => 'http://www.faithcomesbyhearing.com/feeds/audio-bibles/'.$bible->id.'.xml',
			'rel'   => 'self',
			'type'  => 'application/rss+xml'
		];
		$meta['channel']['language'] = $bible->language->iso;
		$meta['channel']['copyright'] = $bible->copyright;
		//$meta['channel']['lastBuildDate'] = ($bible->last_updated) ? $bible->last_updated->toRfc2822String() : "";
		//$meta['channel']['pubDate'] = ($bible->date) ? $bible->date->toRfc2822String() : "";
		$meta['channel']['docs'] = 'http://blogs.law.harvard.edu/tech/rss';
		$meta['channel']['webMaster'] = $site_contact;
		$meta['channel']['itunes:keywords'] = 'Bible, Testament, Jesus, Scripture, Holy, God, Heaven, Hell, Gospel, Christian, Bible.is, Church';
		$meta['channel']['itunes:author'] = 'Faith Comes By Hearing';
		$meta['channel']['itunes:subtitle'] = 'Online Audio Bible Recorded by Faith Comes By Hearing';
		$meta['channel']['itunes:explicit'] = 'no';

		$meta['channel']['managingEditor'] = $site_contact;
		$meta['channel']['image']['url'] = 'http://bible.is/ImageSize300X300.jpg';
		$meta['channel']['image']['title'] = 'Title or description of your logo';
		$meta['channel']['image']['link'] = 'http://bible.is';
		$meta['channel']['itunes:owner']['itunes:name'] = 'Faith Comes By Hearing';
		$meta['channel']['itunes:owner']['itunes:email'] = 'your@email.com';

		//$meta['channel']['itunes:image href="http://bible.is/ImageSize300X300.jpg" /'] = '';
		//$meta['channel']['atom:link href="http://bible.is/feed.xml" rel="self" type="application/rss+xml" /'] = '';
		//$meta['channel']['pubDate'] = 'Sun, 01 Jan 2012 00:00:00 EST';

		$meta['channel']['itunes:summary'] = 'Duplicate of above verbose description.';
		$meta['channel']['itunes:subtitle'] = 'Short description of the podcast - 255 character max.';

		$items = [];
		foreach($fileset->files as $file) {
			$items[] = [
				'title'       => 'Matthew 1',
				'link'        => 'http://podcastdownload.faithcomesbyhearing.com/mp3.php/ENGESVC2DA/B01___01_Matthew_____ENGESVC2DA.mp3',
				'guid'        => 'http://podcastdownload.faithcomesbyhearing.com/mp3.php/ENGESVC2DA/B01___01_Matthew_____ENGESVC2DA.mp3',
				//'description' => ($file->currentTitle) ? htmlspecialchars($file->currentTitle->title) : "",
				'enclosure'   => [
					'name'   => "name",
					'_attributes' => [
						'url'    => 'http://podcastdownload.faithcomesbyhearing.com/mp3.php/ENGESVC2DA/'. $file->file_name .'.mp3',
						'length' => '1703936',
						'type'   => 'audio/mpeg'
					],
				],
				'pubDate'              => 'Wed, 30 Dec 2009 22:22:16 -0700',
				'itunes:author'        => 'Faith Comes By Hearing',
				'itunes:explicit'      => 'no',
				//'itunes:subtitle'      =>  ($file->currentTitle) ? htmlentities($file->currentTitle->title,ENT_XML1) : "",
				//'itunes:summary'       =>  ($file->currentTitle) ? htmlentities($file->currentTitle->title,ENT_XML1) : "",
				'itunes:duration'      => '3:15',
				'itunes:keywords'      => 'Bible, Testament, Jesus, Scripture, Holy, God, Heaven, Hell, Gospel, Christian, Bible.is, Church'
			];
		}
		$meta['channel']['item'] = $items;
		return $this->reply($meta, ['rootElementName' => $rootElementName, 'rootAttributes' => $rootAttributes]);
		//if(!$bible) return $this->setStatusCode(404)->replyWithError("Bible not found for ID: $id");
		//if(!$this->api) return view('bibles.show',compact('bible'));
		//return $this->reply(fractal()->item($bible)->serializeWith($this->serializer)->transformWith(new BibleTransformer())->toArray());
	}

	public function manage($id)
	{
		$bible = Bible::with('filesets')->find($id);
		if(!$bible) return $this->setStatusCode(404)->replyWithError("Bible not found for ID: $id");

		return view('bibles.manage',compact('bible'));
	}

	/**
	 *  Query books with the optional constraints of bible_id, book_id and language translations
	 *
	 * @param string $bible_id
	 * @param string|null $book_id
	 *
	 * @return APIController::reply()
	 */
	public function books($bible_id, $book_id = null)
	{
		if(!$this->api) return view('bibles.books.index');

		$book_id = checkParam('book_id',$book_id,'optional');

		$translation_languages = checkParam('language_codes',null,'optional');
		if($translation_languages) $translation_languages = explode('|',$translation_languages);

		$bible_books = BibleBook::where('bible_id',$bible_id)->first();
		$books = Book::when($translation_languages, function($q) use ($translation_languages){
			$q->with(['translations' => function ($query) use($translation_languages) {
				$query->whereIn('iso', $translation_languages);
			}]);
		})->when($bible_id, function($q) use ($bible_id,$bible_books){
			if(isset($bible_books)) $q->whereHas('bible', function ($query) use($bible_id) { $query->where('bible_id', $bible_id); });
			$q->with(['bible' => function ($query) use($bible_id) {
				$query->where('bible_id', $bible_id)->select('id');
			}]);
		})->when($book_id, function($q) use ($book_id){
			$q->where('id',$book_id);
		})->orderBy('book_order')->get();

		return $this->reply(fractal()->collection($books)->transformWith(new BooksTransformer)->toArray());
	}

	public function edit($id)
	{
		$bible = Bible::with('translations.language')->find($id);
		if(!$this->api) {
			$languages = Language::select(['iso','name'])->orderBy('iso')->get();
			$organizations = OrganizationTranslation::select(['name','organization_id'])->where('language_iso','eng')->get();
			$alphabets = Alphabet::select('script')->get();
			return view('bibles.edit',compact('languages', 'organizations', 'alphabets','bible'));
		}

		return $this->reply(fractal()->collection($bible)->transformWith(new BibleTransformer())->toArray());
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$languages = Language::select(['iso','name'])->get();
		$organizations = OrganizationTranslation::select(['name','organization_id'])->where('language_iso','eng')->get();
		$alphabets = Alphabet::select('script')->get();
		return view('bibles.create',compact('languages', 'organizations', 'alphabets'));
	}


    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {

	    request()->validate([
		    'id'                      => 'required|max:24',
		    'iso'                     => 'required|exists:languages,iso',
		    'translations.*.name'     => 'required',
		    'translations.*.iso'      => 'required|exists:languages,iso',
		    'date'                    => 'integer',
	    ]);

	    $bible = \DB::transaction(function () use($id) {
		    $bible = Bible::with('translations','organizations','equivalents','links')->find($id);
		    $bible->update(request()->only(['id','date','script','portions','copyright','derived','in_progress','notes','iso']));

			if(request()->translations) {
				foreach ($bible->translations as $translation) $translation->delete();
				foreach (request()->translations as $translation) if($translation['name']) $bible->translations()->create($translation);
			}

		    if(request()->organizations) $bible->organizations()->sync(request()->organizations);

		    if(request()->equivalents) {
			    foreach ($bible->equivalents as $equivalent) $equivalent->delete();
			    foreach (request()->equivalents as $equivalent) if($equivalent['equivalent_id']) $bible->equivalents()->create($equivalent);
		    }

		    if(request()->links) {
			    foreach ($bible->links as $link) $link->delete();
			    foreach (request()->links as $link) if($link['url']) $bible->links()->create($link);
		    }

		    return $bible;
	    });

	    return redirect()->route('view_bibles.show', ['id' => $bible->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // TODO: Generate Delete Model for Bible
    }
}
