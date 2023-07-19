
@component('components.b4.modal_medium')
    @slot('size')
        modal-dialog modal-dialog
    @endslot

    @slot('trigger')
        myModal_show_details_searchEstadoCaso
    @endslot

    @slot('title')
        Detalles
    @endslot


    @slot('body')
        <div class="box-body table-responsive no-padding">
            <table class="table">
                <tr>
                    <td width="20%">
                        <label>Usuario</label>
                    </td>
                    <td>
                        <label id="nombre_usuario_details"></label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Estado del Caso</label>
                    </td>
                    <td>
                        <label id="nombre_estado_details"></label>
                    </td>
                </tr>

                <tr>
                    <td>
                        <label>Motivo</label>
                    </td>
                    <td>
                        <label id="nombre_motivo_details"></label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Comentario</label>
                    </td>
                    <td>
                        <textarea name="comen_details" id="comen_details" class="textareaLastComentario"></textarea>
                    </td>
                </tr>
            </table>

        </div>
    @endslot
@endcomponent