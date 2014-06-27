<?php

class nclusive extends BaseController {

    protected $profile_model;
    //protected $layout = 'layouts/master';

    public function __construct()
    {
        $this->profile_model = new profile_model();
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
        $data = array();
        $data['title'] = 'Home Page';

        $data['profiles'] = $this->profile_model->get_all();
        return View::make('index', $data);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $data = array();
        $data['title']       = 'Create Profile';
        $data['countries']   = $this->profile_model->lookup('country');
        $data['regions']     = $this->profile_model->lookup('region');
        $data['cities']      = $this->profile_model->lookup('city');
        $data['occupations'] = $this->profile_model->lookup('occupation');

        return View::make('create', $data);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		// store values

        if(!$this->profile_model->is_valid(Input::all()))
            return Redirect::back()->withInput()->withErrors($this->profile_model->errors);

        $this->profile_model->first_name    = trim(Input::get('first_name'));
        $this->profile_model->last_name     = trim(Input::get('last_name'));
        $this->profile_model->dob           = date('Y-m-d', strtotime(Input::get('dob')));
        $this->profile_model->gender        = strtoupper(Input::get('gender'));
        $this->profile_model->country_id    = intval(Input::get('country'));
        $this->profile_model->region_id     = intval(Input::get('region'));
        $this->profile_model->city_id       = intval(Input::get('city'));
        $this->profile_model->occupation_id = intval(Input::get('occupation'));
        $this->profile_model->summary       = trim(Input::get('summary'));
        $this->profile_model->homepage      = trim(Input::get('homepage'));
        $this->profile_model->status        = TRUE;

        // use default image
        $PATH = base_path().'/profiles/';

        if(Input::hasFile('photo'))
        {
            $photo     = Input::file('photo');
            $file_name = strtolower(str_random(8).'.'.$photo->getClientOriginalExtension());

            $photo->move($PATH, $file_name);
            $photo_path = $PATH.$file_name;
        }
        else
        {
            $file_name  = str_random(8).'.jpg';
            $photo_path = strval($PATH.$file_name);

            if($this->profile_model->gender == 'M')
                $default_path  = base_path().'/assets/images/default_m.jpg';

            else $default_path = base_path().'assets/images/default_f.jpg';

            if(!copy($default_path, $photo_path))
                echo 'can not copy';
        }

        chmod($photo_path, 0755);

        $this->profile_model->path = $photo_path;
        $this->profile_model->save();

        // crop
        $this->profile_model->crop($photo_path);

        $data['jgrowl']   = 'Your profile has been saved.';
        $data['duration'] = 3000;

        return Redirect::to('/')->with($data);
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        $data            = array();
        $data['profile'] = $this->profile_model->get_details($id);
        $data['title']   = 'Profile of '.$data['profile']->first_name.' '.$data['profile']->last_name;

        return View::make('show', $data);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$data                = array();
        $data['countries']   = $this->profile_model->lookup('country');
        $data['regions']     = $this->profile_model->lookup('region');
        $data['cities']      = $this->profile_model->lookup('city');
        $data['occupations'] = $this->profile_model->lookup('occupation');

        $data['profile'] = $this->profile_model->get_details($id);

        if(!empty($data['profile']))
            $data['title']   = 'Edit Profile of '.$data['profile']->first_name.' '.$data['profile']->last_name;
        else $data['title'] = 'Edit Profile';

        return View::make('edit', $data);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update()
	{
        $id = intval(Input::get('id'));

        if(!$this->profile_model->is_valid(Input::all()))
            return Redirect::back()->withInput()->withErrors($this->profile_model->errors);

        $profile                = profile_model::find($id);
        $profile->first_name    = trim(Input::get('first_name'));
        $profile->last_name     = trim(Input::get('last_name'));
        $profile->dob           = date('Y-m-d', strtotime(Input::get('dob')));
        $profile->gender        = strtoupper(Input::get('gender'));
        $profile->country_id    = intval(Input::get('country'));
        $profile->region_id     = intval(Input::get('region'));
        $profile->city_id       = intval(Input::get('city'));
        $profile->occupation_id = intval(Input::get('occupation'));
        $profile->summary       = trim(Input::get('summary'));
        $profile->homepage      = trim(Input::get('homepage'));
        $profile->status        = TRUE;

        // use default image
        $PATH = base_path().'/profiles/';

        if(Input::hasFile('photo'))
        {
            $photo     = Input::file('photo');
            $file_name = strtolower(str_random(8).'.'.$photo->getClientOriginalExtension());

            // remove old file
            if(file_exists($profile->path))
                unlink($profile->path);

            $photo->move($PATH, $file_name);
            $photo_path = $PATH.$file_name;

            // crop
            $profile->crop($PATH.$file_name);
        }
        else
        {
            $file_name  = str_random(8).'.jpg';
            $photo_path = strval($PATH.$file_name);

            if($profile->gender == 'M')
                $default_path  = base_path().'/assets/images/default_m.jpg';

            else $default_path = base_path().'/assets/images/default_f.jpg';

            if(!copy($default_path, $photo_path))
            {
                $data['duration'] = 4000;
                $data['jgrowl']   = 'Your image did not save!';
                echo 'did not copy';exit();
                return Redirect::back()->with($data);
            }
        }

        chmod($photo_path, 0755);

        $profile->path = $photo_path;
        $profile->save();

        $data['duration'] = 3000;
        $data['jgrowl'] = $profile->first_name.' '.$profile->last_name.' has been updated.';
        return Redirect::to('/')->with($data);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        if(isset($id))
        {
            $profile = profile_model::find(intval($id));
           // echo $profile->path;exit();
            if(file_exists($profile->path))
                unlink($profile->path);

            // delete
            $this->profile_model->delete_profile($id);

            $data = array('duration' => 3000, 'jgrowl' => $profile->first_name.' has been deleted!');
            return Redirect::to('/')->with($data);
        }
	}

    public function search()
    {
        $USA_ID = 254;
        $data['title']       = 'Search Profiles';
        $data['countries']   = $this->profile_model->lookup('country');
        $data['occupations'] = $this->profile_model->lookup('occupation');
        $data['regions']     = $this->profile_model->get_regions($USA_ID);

        return View::make('search', $data);
    }

    public function search_results()
    {
        $data['profiles'] = $this->profile_model->search(Input::all());
        $data['title']    = 'Search Results';

        return View::make('search_results')->with($data);
    }

    public function fetch($mode)
    {
        //$event = new event_model();

        switch($mode)
        {
            case 'region':
                $loc = $this->profile_model->get_regions(intval(Input::get('country')));
                break;

            case 'city':
                $loc = $this->profile_model->get_cities(intval(Input::get('region')));
                break;
        }

        return Response::json($loc);
    }

    public function auto()
    {
        $profile = new profile_model();

        $num_inserts = 1;

        for($z=0; $z<$num_inserts; $z++)
        {
            $c = 0;
            while($c == 0)
            {
                $country_id = mt_rand(1, 274);
                $regions    = $this->profile_model->get_regions($country_id);
                $r 			= count($regions);

                $region_id = $regions[mt_rand(0, ($r-1))]->id;
                $cities    = $this->profile_model->get_cities($region_id);
                $c 		   = count($cities);

                if($c>0)
                    $city_id = $cities[mt_rand(0, ($c-1))]->id;
            }

            $lorem = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis eu porttitor lectus, in aliquam dolor. Pellentesque odio diam, malesuada sed libero id, accumsan vehicula purus. Proin ornare facilisis ligula, ut semper velit vehicula a. Donec non volutpat lectus. Suspendisse tempor. ';

            $word_bank      = explode(' ', $lorem);
            $num_words      = count($word_bank);
            $summary_length = mt_rand(3, $num_words);
            $summary        = '';

            for($i=0; $i<$summary_length; $i++)
                $summary .= $lorem[mt_rand(0, $num_words-1)];

            $gender_data = array('M', 'F');
            $name_data   = array('John', 'Jane');

            $profile->first_name    = $name_data[mt_rand(0,1)];
            $profile->last_name     = 'Doe';
            $profile->dob           = strval(mt_rand(1900, 2014).'-'.mt_rand(1,12).'-'.mt_rand(1,28));
            $profile->gender        = $gender_data[mt_rand(0,1)];
            $profile->country_id    = $country_id;
            $profile->region_id     = $region_id;
            $profile->city_id       = $city_id;
            $profile->occupation_id = mt_rand(1,12);
            $profile->summary       = trim($summary);
            $profile->homepage      = 'http://'.mt_rand(0,255).'.'.mt_rand(0,255).'.'.mt_rand(0,255).'.'.mt_rand(0,255);
            $profile->status        = TRUE;

            $PATH       = base_path().'/profiles/';
            $filename   = strtolower(str_random(8).'.jpg');
            $photo_path = $PATH.$filename;

            if($profile->gender == 'M')
                $default_path  = base_path().'/assets/images/default_m.jpg';

            else $default_path = base_path().'/assets/images/default_f.jpg';

            if(!copy($default_path, $photo_path))
                echo 'DID NOT COPY!';

            chmod($photo_path, 0777);

            $profile->path = $photo_path;

            $profile->save();

            // redirect back
            $data['duration'] = 3000;
            $data['jgrowl']   = 'You have automatically created a profile(s).';
        }

        return Redirect::to('/')->with($data);
    }
}