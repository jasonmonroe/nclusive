@extends('master')

@section('content')

<script type="text/javascript">
    $(document).ready(function(){

        $('#show-list').on('click', function(){
            console.log('list');

        });
        $('#show-img').on('click', function(){
            console.log('img');

        });

    });

</script>

    <h2 style="text-align: center">Post Your Profile.  Get Liked.  Become Famous.  History.</h2>

    <div id="content">
        <span title="Show List" id="show-list"><i class="fa fa-bars"></i></span>
        <span title="Show Gallery" id="show-img"><i class="fa fa-picture-o"></i></span>
        <table class="table table-striped table-hovered table-bordered">
            <thead>
                <tr>
                    <th><strong>Name</strong></th>
                    <th><strong><abbr title="Date of Birth">DOB</abbr></strong></th>
                    <th><strong>Gender</strong></th>
                    <th><strong>Location</strong></th>
                    <th><strong>Occupation</strong></th>
                    <th><strong>Homepage</strong></th>
                    <th><strong>Summary</strong></th>
                </tr>
            </thead>
            <tbody>
                @foreach(array_chunk($profiles->getCollection()->all(), 15) as $profile_chunk)
                    @foreach($profile_chunk as $profile)
                    <tr>
                        <td> {{ link_to(URL::to('show/'.$profile->id), $profile->first_name.' '.$profile->last_name) }} </td>
                        <td>{{ $profile->dob }}</td>
                        <td>{{ $profile->gender }}</td>
                        <td>{{ $profile->city }}, {{ $profile->region }}, {{ $profile->country }}</td>
                        <td>{{ ucwords($profile->occupation) }}</td>
                        <td>{{ link_to($profile->homepage, $profile->homepage) }}</td>
                        <td><em>{{ str_limit($profile->summary, 50, '...') }}</em></td>
                    </tr>
                    @endforeach
                @endforeach

            </tbody>
        </table>

        <div class="" style="margin:0 auto; text-align: center">
            {{ $profiles->appends(Request::except('page', '_token', 'search-btn', 'page'))->links() }}
        </div>

    </div>
</div>
@stop