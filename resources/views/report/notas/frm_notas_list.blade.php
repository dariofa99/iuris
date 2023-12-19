@extends('layouts.dashboard')



@section('navbar')
    <!-- aqui va el menu de cada vista -->
    @include('content.navbar')
@endsection

@section('titulo_area')
    Reporte de notas
@endsection


@section('area_forms')

    @include('msg.success')
    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab"
                        aria-controls="pills-home" aria-selected="true">General</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab"
                        aria-controls="pills-profile" aria-selected="false">Individual</a>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                    {!! Form::open(['url' => 'notas/search/', 'method' => 'post', 'id' => 'myFormNotasSearch']) !!}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="checkbox-inline">
                                    <input type="radio" checked name="type_repor" value="periodo"> Por periodo
                                </label>
                                <label class="checkbox-inline">
                                    <input type="radio" name="type_repor" value="corte"> Por corte
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="email">Periodo:</label>
                                <select name="periodo_id" id="periodo_id" class="form-control required">
                                    <option value="">Seleccione...</option>
                                    @foreach ($periodos as $periodo)
                                        <option value="{{ $periodo->id }}">{{ $periodo->prddes_periodo }} (<i
                                                style="font-size:10px !important">{{ $periodo->prdfecha_inicio }} /
                                                {{ $periodo->prdfecha_fin }} </i> )</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="email">Corte:</label>
                                <select name="segmento_id" disabled id="segmento_id" class="form-control required">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        {{-- <div class="col-md-3">
			 <div class="form-group">
				 <a href="/excel/notas/download" class="btn btn-primary">General</a>
			 </div>
		 </div> --}}
                    </div>

                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <br>
                                <input type="submit" value="Buscar" class="btn btn-success">
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                    {!! Form::open(['url' => 'notas/search/', 'method' => 'post', 'id' => 'myFormNotasSearchInd']) !!}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="checkbox-inline">
                                    <input type="radio" checked name="type_repor" value="periodo"> Por periodo
                                </label>
                                <label class="checkbox-inline">
                                    <input type="radio" name="type_repor" value="corte"> Por corte
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="email">No documento:</label>
                                <input type="text" class="form-control required" name="idnumber">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="email">Periodo:</label>
                                <select name="periodo_id" class="form-control required">
                                    <option value="">Seleccione...</option>
                                    @foreach ($periodos as $periodo)
                                        <option value="{{ $periodo->id }}">{{ $periodo->prddes_periodo }} (<i
                                                style="font-size:10px !important">{{ $periodo->prdfecha_inicio }} /
                                                {{ $periodo->prdfecha_fin }} </i> )</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="email">Corte:</label>
                                <select name="segmento_id" disabled class="form-control required">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        {{-- <div class="col-md-3">
								<div class="form-group">
									<a href="/excel/notas/download" class="btn btn-primary">General</a>
								</div>
							</div> --}}
                    </div>

                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <br>
                                <input type="submit" value="Buscar" class="btn btn-success">
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>







    {{-- <div class="row">
<div class="col-md-12">
<div class="box-body table-responsive no-padding">
	<table>
		<tr>
			<td>
				Nombres
			</td>
		</tr>
	</table>
	</div>
</div>
</div> --}}

@stop
@push('scripts')
    <!-- aqui van los scripts de cada vista -->
    <!-- Latest compiled and minified JavaScript -->
    <script type="module" src={{ asset('js/admin_reportes.js') }}></script>
@endpush
