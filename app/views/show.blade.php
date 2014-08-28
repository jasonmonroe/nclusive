@extends('master')
@section('content')

<script type="text/javascript">

    $(document).ready(function(){
        $('#like-lnk').on('click', function(){

            $.ajax({
                data: { id: <?php echo $profile->id ?> },
                type:'POST',
                dataType:'JSON',
                url:'{{ URL::to('like') }}',
                success:function(data)
                {
                    //console.log(data);
                    var like_ctr = data;
                    // increment like counter
                    $('#like-ctr').text(like_ctr);
                },
                error:function(msg)
                {
                    console.log(msg.responseText);
                }
            });
        });
    });
</script>

<div id="content">
    <h3>Profile Information</h3>

    <div style="float:left">
        {{ $profile->src }}
        <p style="margin-top:10px">
            {{ link_to(URL::to('edit/'.$profile->id), 'Edit', array('class'=> 'btn btn-default btn-xs')) }}
            {{ link_to(URL::to('destroy/'.$profile->id), 'Delete', array('class'=> 'btn btn-danger btn-xs')) }}
            <a id="like-lnk" class="btn btn-primary btn-xs" href="#"><i class="fa fa-thumbs-o-up color-green"></i> Like </a>
            <span id="like-ctr">{{ $profile->likes }}</span>
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