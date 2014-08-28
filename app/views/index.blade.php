@extends('master')

@section('content')

<script type="text/javascript">
    $(document).ready(function(){

        $('#gallery-container').hide();

        $('#show-list').on('click', function(){
            $('#table-container').show();
            $('#gallery-container').hide();

        });

        $('#show-img').on('click', function(){
            $('#table-container').hide();
            $('#gallery-container').show();

        });


    });

</script>

    <h2 style="text-align: center">Post Your Profile.  Get Liked.  Become Famous.  History.</h2>
    <div id="content">
        <span title="Show List" id="show-list"><i class="fa fa-bars"></i></span>
        <span title="Show Gallery" id="show-img"><i class="fa fa-picture-o"></i></span>

        <div id="table-container">
            <table class="table table-striped table-hovered table-bordered">
                <thead>
                    <tr style="background-color:black">
                        <th style="color:white">ID</th>
                        <th style="color:white"><strong>Name</strong></th>
                        <th style="color:white"><strong><abbr title="Date of Birth">DOB</abbr></strong></th>
                        <th style="color:white"><strong>Gender</strong></th>
                        <th style="color:white"><strong>Location</strong></th>
                        <th style="color:white"><strong>Occupation</strong></th>
                        <th style="color:white"><strong>Homepage</strong></th>
                        <th style="color:white"><strong>Summary</strong></th>
                        <th style="color:white"><strong>Likes</strong></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(array_chunk($profiles->getCollection()->all(), 15) as $profile_chunk)
                        @foreach($profile_chunk as $profile)
                        <tr>
                            <td>{{ $profile->id }}</td>
                            <td> {{ link_to(URL::to('show/'.$profile->id), $profile->first_name.' '.$profile->last_name) }} </td>
                            <td>{{ $profile->dob }}</td>
                            <td>{{ $profile->gender }}</td>
                            <td>{{ $profile->city }}, {{ $profile->region }}, {{ $profile->country }}</td>
                            <td>{{ ucwords($profile->occupation) }}</td>
                            <td>{{ link_to($profile->homepage, $profile->homepage) }}</td>
                            <td><em>{{ str_limit($profile->summary, 50, '...') }}</em></td>
                            <td>{{ $profile->likes }}</td>
                        </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>

        <div id="gallery-container">
            <?php foreach(array_chunk($profiles->getCollection()->all(), 5) as $profile_chunk){
                echo '<div class="row">';
                foreach($profile_chunk as $profile){?>
                    <article style="float:left; margin-left:15px">
                        <img src="{{ $profile->src }}" width="200" height="150">
                        <p style="text-align: center; margin-top:5px"><a href="{{ URL::to('show/'.$profile->id) }}">{{ $profile->first_name }}</a></p>
                    </article>
                <?php } echo '</div>'; } ?>
        </div>

        {{ link_to(URL::to('auto'), 'Auto Generate a profile', array('class' => 'btn btn-default', 'title' => 'Click here to automatically add 10 profiles.')) }}

        <div style="text-align: center">
            {{ $profiles->appends(Request::except('page', '_token', 'btn', 'page'))->links() }}
        </div>

    </div>
</div>
@stop