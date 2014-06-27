@extends('master')

@section('content')

<div id="content">
    <h3>Profile Information</h3>

    <div style="float:left">
        {{ $profile->src }}
        <p style="margin-top:10px">
            {{ link_to(URL::to('edit/'.$profile->id), 'Edit', array('class'=> 'btn btn-default btn-xs')) }}
            {{ link_to(URL::to('destroy/'.$profile->id), 'Delete', array('class'=> 'btn btn-primary btn-xs')) }}
        </p>
    </div>

    <div style="float:left; margin-left:20px; width:600px">
        <table class="table table-striped table-hovered table-bordered">
            <thead><tr><th><strong>Column</strong></th><th><strong>Value</strong></th></tr></thead>
            <tbody>
            @foreach(get_object_vars($profile) as $property => $val)
                @if($property != 'src' && strlen(strstr($property, '_id')) == 0)
                <tr><td><strong>{{ strtoupper($property) }}</strong></td><td>{{ ucwords($val) }}</td></tr>
                @endif
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="clear:both"></div>

</div>
@stop