@component('components.b4.modal_medium')
    @slot('trigger')
        myModal_actualizar_incidencia
    @endslot

    @slot('title')
        <label id="lbl_title_fract">Actualizando incidencia</label>
    @endslot


    @slot('body')
        <div class="row">
            <div class="col-md-12">
                <form id="form_act_incidencia">
                    <input type="hidden" id="estado_id" name="estado_id">
                    <input type="hidden" id="id" name="id">
                    <!-- Categoría -->



                    <!-- Comentario -->
                    <div class="form-group">
                        <label for="comentario" class="font-weight-bold">Comentario</label>
                        <textarea maxlength="200" class="form-control" id="motivo" name="motivo" rows="4"
                            placeholder="Describa brevemente el motivo..." required></textarea>
                        <small class="form-text text-muted">
                            Sea lo más específico posible. Ejemplo: “Eliminar nota de cero relacionada a la
                            actuación
                            'Demanda X'”. <span class="char_count">0/200</span>
                        </small>
                    </div>

                    <!-- Botón de envío -->
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary"
                            style="                   
                    color: white;
                    font-weight: 600;
                    padding: 10px 20px;
                    border: none;
                    border-radius: 8px;
                    cursor: pointer;
                    font-size: 15px;
                    transition: all 0.2s ease-in-out;
                    box-shadow: 0 4px 8px rgb(214, 214, 214);
                "
                            onmouseover="this.style.transform='translateY(-2px)'"
                            onmouseout="this.style.transform='translateY(0)'">
                            🚀 Enviar actualización
                        </button>
                    </div>
                </form>
            </div>




        </div>
    @endslot
@endcomponent
<!-- /modal -->
