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
    <h2>Post your profile</h2>
    {{ Form::open(array('name' =>'add-profile-form', 'url' => URL::to('store'), 'files'=>true, 'class'=> 'form-horizontal', 'role'=>'form')) }}
    <div class="form-group">
        <label for="first_name" class="">First Name</label>
        <span style="margin-left:4px; font-weight:bold" class="color-red">{{ $errors->first('first_name') }}</span>
        <input type="text" name="first_name" class="form-control" id="" value="{{ Input::old('first_name') }}" maxlength="32">
    </div>

    <div class="form-group">
        <label for="last_name" class="">Last Name</label>
        <span style="margin-left:4px; font-weight:bold" class="color-red">{{ $errors->first('last_name') }}</span>
        <input type="text" name="last_name" class="form-control" id="" value="{{ Input::old('last_name') }}" maxlength="32">
    </div>
    <div class="form-group col-md-6">
        <label for="gender" class="">Gender</label>
        <select name="gender" class="form-control">
            <option value="M">Male</option>
            <option value="F">Female</option>
        </select>
    </div>
    <div class="form-group  col-md-6" style="margin-left: 25px">
        <label for="dob" class="">Date of Birth</label>
        <span style="margin-left:4px; font-weight:bold" class="color-red">{{ $errors->first('dob') }}</span>
        <input type="text" name="dob" class="datepicker span2 form-control" id="" placeholder="MM/DD/YYYY" value="{{ Input::old('dob') }}" maxlength="10">
        <span class="add-on"><i class="icon-th"></i></span>
    </div>

    <div class="form-group">
        <label for="country" class="">Country</label>
        <select name="country" id="country" class="form-control">
            <option value="" disabled></option>
            @foreach($countries as $country)
            <option value="{{ $country->id }}">{{ $country->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="region" class="">Region</label>
        <select name="region" id="region" class="form-control">
            <option value="" disabled></option>
        </select>
    </div>

    <div class="form-group">
        <label for="city" class="">City</label>
        <select name="city" id="city" class="form-control">
            <option value="" disabled></option>
        </select>
    </div>

    <div class="form-group">
        <label for="occupation" class="">Occupation</label>
        <select name="occupation" id="occupation" class="form-control">
            <option value="" disabled></option>
            @foreach($occupations as $occupation)
            <option value="{{ $occupation->id }}">{{ $occupation->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="homepage" class="">Homepage</label>
        <span style="margin-left:4px; font-weight:bold" class="color-red">{{ $errors->first('homepage') }}</span>
        <input type="text" name="homepage" class="form-control" placeholder="http://www.google.com" id="" value="{{ Input::old('homepage') }}" maxlength="32">
    </div>

    <div class="form-group">
        <label for="summary">Summary</label>
        <textarea name="summary" class="form-control">{{ Input::old('summary') }}</textarea>
    </div>

    <div class="form-group">
        <label for="photo" class="">Photo</label>
        <span style="margin-left:4px; font-weight:bold" class="color-red">{{ $errors->first('photo') }}</span>
        <input type="file" name="photo" class="btn btn-default" id="">
    </div>
        <button type="submit" name="post-btn" class="btn btn-default">Post</button>
    {{ Form::close() }}

</div>
@stop