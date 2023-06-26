@extends('layouts.dashboard')
@section('area_forms')

@include('msg.success')
<div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
               
                
                        <a href="{{url('thumbnail/create')}}" class="btn-xs btn-primary pull-right" role="button">Agregar</a>
                   
                    <div class="panel-body">
                        <div class="row">
                            @foreach($thumbnails as $thumbnail)
                            <div class="col-sm-6 col-md-4">
                                <div class="thumbnail">
                                    <img src="thumbnails/{{$thumbnail->image}}" alt="{{$thumbnail->name}}">
                                    <div class="caption">
                                        <h3>{{$thumbnail->name}}</h3>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
               
            </div>
        </div>
    </div>
@endsection