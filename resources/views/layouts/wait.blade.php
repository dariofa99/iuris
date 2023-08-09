<div id="wait"  style="display:none; position: absolute; width: 100%;min-height: 100%;height: auto;position: fixed;top:0; left:0;background-color: rgba(236, 240, 245, 0.8);">
        <div class="container" style="margin-top:15%;padding:2px;">
            <div class="row justify-content-md-center">
                <div class="col col-lg-2">

                </div>
                <div class="col-md-auto text-center ">
                    <img src="{{ asset('img/logo2.png') }}" id="load" width="67" height="71" /><br>
                    <span style="color:#515151;font-size: 16px;">Cargando...</span>

                </div>
                <div class="col col-lg-2">

                </div>
            </div>
            <div class="row justify-content-md-center">
                <div class="col col-lg-2">

                </div>
                <div class="col-md-auto text-center justify-content-center">
                    <div class="progress" id="progressbarwait"
                        style="min-width: 350px; height: 21px; background-color: #a5a5a5; display: none; ">
                        <div class="progress-bar progress-bar-striped" id="progressGeneral"
                            style="width:0%; height: 21px;">0%</div>
                    </div>
                </div>
                <div class="col col-lg-2">

                </div>
            </div>
        </div>

    </div>