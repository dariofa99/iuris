@if($pie_conf!=null)
<footer style="width: 100%;text-align:{{$pie_conf->pie_align}}">
    <img width="{{$pie_conf->pie_width}}px" height="{{$pie_conf->pie_height}}px" src="{{asset('/storage/'.$pie->path)}}" alt="">
</footer>
@endif