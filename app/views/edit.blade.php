@extends('master')

@section('content')

<script type="text/javascript">
    $(document).ready(function(){
        $('.datepicker').datepicker();

        $('#country').on('change', function(){
            var country_id = $(this).val();

            if(country_id > 0)
            {
                $.ajax({
                    data:     {'country':country_id},
                    dataType: 'JSON',
                    type:     'POST',
                    url:      '<?php echo URL::to('fetch/region') ?>',
                    success:  function(data){
                        var regions = data;
                        var options = '<option value="" class="disabled">Select State/Province</option>';

                        for(var i=0; i<regions.length; i++)
                            options = options.concat('<option value="'+regions[i].id+'">'+regions[i].name+'</option>');

                        options = options.concat('</select>');

                        // populate regions
                        $('#region').html(options);
                    },

                    error: function(err){
                        console.log(err.responseText);
                        alert('ERROR! Could not retrieve countries.');
                    }
                });
            }
        });

        // regions
        $('#region').on('change', function(){
            var region_id = $(this).val();

            if(region_id > 0)
            {
                $.ajax({
                    data:     {'region':region_id},
                    dataType: 'JSON',
                    type:     'POST',
                    url:      '<?php echo URL::to('fetch/city') ?>',
                    success:  function(data){

                        var cities = data;
                        var options = '<option value="" class="disabled">Select City</option>';

                        for(var i=0; i<cities.length; i++)
                            options = options.concat('<option value="'+cities[i].id+'">'+cities[i].name+'</option>');

                        options = options.concat('</select>');

                        // populate cities
                        $('#city').html(options);
                    },

                    error: function(err){
                        console.log(err.responseText);
                        alert('ERROR! Could not retrieve regions.');
                    }
                });
            }
        });
    });


</script>

<div id="content" class="col-md-8">
    <h2>Edit profile</h2>
    {{ Form::open(array('name' =>'edit-profile-form', 'url' => URL::to('update'), 'files'=>true, 'class'=> 'form-horizontal', 'role'=>'form') ) }}
        <input type="hidden" name="id" value="{{ $profile->id }}">

        <div class="form-group">
            <label for="first_name">First Name</label>
            <span style="margin-left:4px; font-weight:bold" class="color-red">{{ $errors->first('first_name') }}</span>
            <input type="text" name="first_name" class="form-control" id="" value="{{ $profile->first_name }}" maxlength="32">
        </div>

        <div class="form-group">
            <label for="last_name">Last Name</label>
            <span style="margin-left:4px; font-weight:bold" class="color-red">{{ $errors->first('first_name') }}</span>
            <input type="text" name="last_name" class="form-control" id="" value="{{ $profile->last_name }}" maxlength="32">
        </div>

        <div class="form-group col-md-6">
            <label for="gender">Gender</label>
            <select name="gender" class="form-control">
                <option value="M" <?php if($profile->gender == 'M'){ echo 'selected';}?>>Male</option>
                <option value="F" <?php if($profile->gender == 'F'){ echo 'selected';}?>>Female</option>
            </select>
        </div>

        <div class="form-group   col-md-6" style="margin-left: 25px">
            <label for="dob">Date of Birth</label>
            <span style="margin-left:4px; font-weight:bold" class="color-red">{{ $errors->first('dob') }}</span>
            <input type="text" name="dob" class="datepicker span2 form-control " id="" placeholder="MM/DD/YYYY" value="{{ date('m/d/Y', strtotime($profile->dob)) }}" maxlength="10">
        </div>

        <div class="form-group">
            <label for="country">Country</label>
            <select name="country" id="country" class="form-control">
                <option value="{{ $profile->country_id }}">{{ $profile->country }}</option>
                @foreach($countries as $country)
                <option value="{{ $country->id }}">{{ $country->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="region">Region</label>
            <select name="region" id="region" class="form-control">
                <option value="{{ $profile->region_id }}">{{ $profile->region }}</option>
            </select>
        </div>

        <div class="form-group">
            <label for="city">City</label>
            <select name="city" id="city" class="form-control">
                <option value="{{ $profile->city_id }}">{{ $profile->city }}</option>
            </select>
        </div>

        <div class="form-group">
            <label for="occupation">Occupation</label>
            <select name="occupation" id="occupation" class="form-control">
                <option value="{{ $profile->occupation_id }}">{{ $profile->occupation }}</option>
                @foreach($occupations as $occupation)
                <option value="{{ $occupation->id }}">{{ $occupation->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="homepage">Homepage</label>
            <span style="margin-left:4px; font-weight:bold" class="color-red">{{ $errors->first('homepage') }}</span>
            <input type="text" name="homepage" class="form-control" id="" placeholder="http://www.google.com" value="{{ $profile->homepage }}" maxlength="32">
        </div>

        <div class="form-group">
            <label for="summary">Summary</label>
            <textarea name="summary" class="form-control">{{ $profile->summary }}</textarea>
        </div>

        <div class="form-group">
            <label for="photo">Photo</label>
            <span style="margin-left:4px; font-weight:bold" class="color-red">{{ $errors->first('photo') }}</span>
            <input type="file" name="photo" class="btn btn-default" id="">
        </div>
        <button type="submit" name="edit-btn" class="btn btn-default">Edit</button>
    {{ Form::close() }}

</div>
@stop