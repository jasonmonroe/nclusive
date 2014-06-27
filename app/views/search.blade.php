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
                        var options = '<option value="" class="">Select State/Province</option>';

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
                        var options = '<option value="" class="">Select City</option>';

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

<div id="content" class="col-md-8" style="margin: 0 auto">
    <h2>Search Profiles</h2>
    {{ Form::open(array('name' =>'add-profile-form', 'url' => URL::to('search_results'), 'files'=>true, 'class'=> 'form-horizontal', 'role'=>'form')) }}
        <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" name="first_name" class="form-control" id="" value="{{ Input::old('first_name')  }}" maxlength="32">
        </div>
        <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" name="last_name" class="form-control" id="" value="{{ Input::old('last_name') }}" maxlength="32">
        </div>

        <div class="form-group">
            <label for="gender">Gender</label>
            <select name="gender" class="form-control">
                <option value="" selected>Both</option>
                <option value="M">Male</option>
                <option value="F">Female</option>
            </select>
        </div>

        <div class="form-group col-md-6">
            <label for="op">Operand</label>
            <select name="op" class="form-control">
                <option value="=" selected>on</option>
                <option value="<">Before</option>
                <option value="<=">Before or on</option>
                <option value=">">After</option>
                <option value=">=">After or on</option>
            </select>
        </div>
        <div class="form-group col-md-6" style="margin-left: 20px">
            <label for="dob">Date of Birth</label>
            <input type="text" name="dob" class="datepicker span2 form-control" id="" placeholder="MM-DD-YYYY" value="" maxlength="10">
        </div>



        <div class="form-group">
            <label for="country">Country</label>
            <select name="country" id="country" class="form-control">
                <option value="" selected></option>
                @foreach($countries as $country)
                <option value="{{ $country->id }}">{{ $country->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="region">Region</label>
            <select name="region" id="region" class="form-control">
                <option value="" selected></option>
            </select>
        </div>

        <div class="form-group">
            <label for="city">City</label>
            <select name="city" id="city" class="form-control">
                <option value="" selected></option>
            </select>
        </div>

        <div class="form-group">
            <label for="occupation">Occupation</label>
            <select name="occupation" id="occupation" class="form-control">
                <option value="" selected></option>
                @foreach($occupations as $occupation)
                <option value="{{ $occupation->id }}">{{ $occupation->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="homepage">Homepage</label>
            <input type="text" name="homepage" class="form-control" placeholder="http://www.google.com" id="" value="" maxlength="32">
        </div>
        <div class="form-group">
            <label for="summary">Summary</label>
            <textarea name="summary" class="form-control"></textarea>
        </div>

        <button type="submit" name="search-btn" class="btn btn-default">Search</button>
    {{ Form::close() }}
</div>


@stop