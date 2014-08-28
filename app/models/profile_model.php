<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class profile_model extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'profile';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
    //protected $hidden = array('password', 'remember_token');

    public $timestamps = true;

    public $errors;

    public $rules = array(
        'first_name' => 'Alpha|min:3|required',
        'last_name'  => 'Alpha|min:3|required',
        'dob'        => 'date|before:date|required',
        'gender'     => 'Alpha|required',
        'homepage'   => 'url',
        'photo'      => 'image'
    );

    public function is_valid($data)
    {
        // adjust rules
        $data['dob'] = date('Y-m-d', strtotime($data['dob']));

        $today              = date('Y-m-d');
        $this->rules['dob'] = 'date|before:'.$today.'|required';
        $validation         = Validator::make($data, $this->rules);

        if($validation->fails())
        {
            $this->errors = $validation->messages();
            return FALSE;
        }
        else return TRUE;
    }

    public function lookup($table)
    {
        return DB::table($table)->select('id', 'name')->orderby('name', 'ASC')->get();
    }

    public function get_src($path)
    {
        // Read image path, convert to base64 encoding
        $image_data = base64_encode(file_get_contents($path));

        // Format the path SRC:  data:{mime};base64,{data};
        $src = strval('data: '.finfo_file(finfo_open(FILEINFO_MIME_TYPE), $path).';base64, '.$image_data);

        return $src;
    }

    private function _clean($data)
    {
        unset($data['_token']);

        if(!empty($data['dob']))
        {
            $data['dob'] = date('Y-m-d', strtotime($data['dob']));

            if($data['dob'] != '1970-01-01')
                $data['dob'] = $data['op'].' '.$data['dob'];
            else
            {
                echo 'unset dob and op';
                unset($data['dob']);
                unset($data['op']);
            }
        }

        if(isset($data['page']))
            unset($data['page']);

        foreach($data as $key => $value)
        {
            $value = trim($value);

            if(empty($value))
                unset($data[$key]);
        }

        return $data;
    }

    public function search($data)
    {
        $data = $this->_clean($data);

        $search_results = DB::table('profile')
            ->join('occupation', 'profile.occupation_id', '=', 'occupation.id')
            ->join('country', 'profile.country_id', '=', 'country.id')
            ->join('region',  'profile.region_id',  '=', 'region.id')
            ->join('city',    'profile.city_id',    '=', 'city.id')
            ->where(function($query) use($data)
            {
                foreach($data as $key => $value)
                {
                    if($key == 'country' || $key == 'region' || $key == 'city' || $key == 'occupation')
                        $query->where('profile.'.$key."_id", '=', $value);

                    // append op to the value and parse
                    else if($key == 'dob')
                    {
                        $dob_arr = explode(' ', $value);
                        $op      = strval($dob_arr[0]);
                        $value   = $dob_arr[1];

                        $query->where('profile.'.$key, $op, date('Y-m-d', strtotime($value)));
                    }

                    else if($key == 'homepage' || $key == 'summary' || $key == 'first_name' || $key == 'last-name')
                        $query->where('profile.'.str_replace('-','_',$key), 'LIKE', '%'.$value.'%');

                    else if($key != 'op')
                        $query->where('profile.'.str_replace('-','_',$key), '=', $value);
                }
            })
            ->select('profile.id', 'profile.first_name', 'profile.last_name', 'profile.dob', 'profile.gender', 'country.name AS country', 'region.name AS region', 'city.name AS city', 'occupation.name AS occupation', 'homepage', 'summary', 'path')
            ->orderby('created_at', 'ASC')->paginate(12);


        foreach($search_results->all() as $result)
        {
            $result->src = $this->get_src($result->path);
            $result->dob = date('F j, Y', strtotime($result->dob));
        }

        return $search_results;
    }

    public function get_countries()
    {
        return DB::table('country')->select('id', 'name')->orderby('name', 'ASC')->get();
    }

    public function get_regions($country_id)
    {
        return DB::table('region')->select('id', 'name')->where('country_id', '=', $country_id)->get();
    }

    public function get_cities($region_id)
    {
        return DB::table('city')->select('id', 'name')->where('region_id', '=', $region_id)->get();
    }

    public function get_all($limit=15)
    {

        $results = DB::table($this->table)
            ->join('country', 'profile.country_id', '=', 'country.id')
            ->join('region', 'profile.region_id', '=', 'region.id')
            ->join('city', 'profile.city_id', '=', 'city.id')
            ->join('occupation', 'profile.occupation_id', '=', 'occupation.id')
            ->select('profile.id', 'profile.first_name', 'profile.last_name', 'profile.dob', 'profile.gender', 'profile.likes', 'country.name AS country', 'region.name AS region', 'city.name AS city', 'occupation.name AS occupation', 'homepage', 'summary', 'path')
            ->paginate($limit);

        foreach($results->all() as $result)
        {
            $result->src = $this->get_src($result->path);
            $result->dob = date('F j, Y', strtotime($result->dob));
        }

        return $results;
    }

    public function set_like($id)
    {
        DB::statement('UPDATE profile SET likes = likes + 1 WHERE id = '.intval($id));

        return $this->get_likes($id);
    }

    protected function get_likes($id)
    {
        $result = DB::table('profile')->where('id', '=', $id)->select('likes')->get();
        //print_r(DB::getQueryLog());
        //print_r($result);exit();
        return $result[0]->likes;
    }

    public function get_details($id)
    {
        $results = DB::table($this->table)
            ->join('country', 'profile.country_id', '=', 'country.id')
            ->join('region', 'profile.region_id', '=', 'region.id')
            ->join('city', 'profile.city_id', '=', 'city.id')
            ->join('occupation', 'profile.occupation_id', '=', 'occupation.id')
            ->where('profile.id', '=', $id)
            ->select('profile.id', 'profile.first_name', 'profile.last_name', 'profile.dob', 'profile.gender', 'profile.likes', 'country.id AS country_id', 'country.name AS country', 'region.id AS region_id', 'region.name AS region', 'city.id AS city_id', 'city.name AS city', 'occupation.id AS occupation_id', 'occupation.name AS occupation', 'homepage', 'summary', 'path', 'created_at', 'updated_at')
            ->get();

        foreach($results as $result)
        {
            $result->src        = '<img alt="profile-'.$result->id.'" src="'.$this->get_src($result->path).'">';
            $result->dob        = date('F j, Y', strtotime($result->dob));
            $result->created_at = date('F j, Y g:i:s a', strtotime($result->created_at));
            $result->updated_at = date('F j, Y g:i:s a', strtotime($result->updated_at));
        }

        if(count($results) == 1)
            $results = $results[0];

        return $results;
    }

    public function crop($path)
    {
        // https://stackoverflow.com/questions/1855996/crop-image-in-php

        $photo_data = getimagesize($path);
        $width      = intval($photo_data[0]);
        $height     = intval($photo_data[1]);

        if($height > 300 && $width > 400)
        {
            $dst_x = 0;   // X-coordinate of destination point.
            $dst_y = 0;   // Y --coordinate of destination point.
            $src_x = 100; // Crop Start X position in original image
            $src_y = 100; // Crop Srart Y position in original image
            $dst_w = 400; // Thumb width
            $dst_h = 300; // Thumb height
            $src_w = 260; // $src_x + $dst_w Crop end X position in original image
            $src_h = 220; // $src_y + $dst_h Crop end Y position in original image

            // Creating an image with true colors having thumb dimensions.( to merge with the original image )
            $dst_image = imagecreatetruecolor($dst_w,$dst_h);

            // Get original image
            $src_image = imagecreatefromjpeg($path);

            // Cropping
            imagecopyresampled($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);

            // Saving
            imagejpeg($dst_image, $path);
        }

    }

    public function delete_profile($id)
    {
        return DB::table('profile')->where('id', '=', $id)->delete();
    }

}
