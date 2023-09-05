{!! Form::open(['url' => '/turnos/', 'method' => 'get', 'id' => 'myFormSearchEstudiante']) !!}
<div class="row mb-3">
    <div class="col-md-3">
        <input type="text" placeholder="No Identificación" class="form-control" name="data_search">
    </div>
    <div class="col-md-4">
        <button type="submit" class="btn btn-success" id="btn_search_estu">
            Buscar
        </button>
        <a href="/turnos/" class="btn btn-default" id="btn_seeall">
            Ver Todo
        </a>
    </div>
</div>
{!! Form::close() !!}
<div class="row">
    <div class="col-md-12">
        <div id="table_list_model">
            @include('myforms.frm_turnos_students_list_ajax')
        </div>
        <hr>

    </div>
</div>