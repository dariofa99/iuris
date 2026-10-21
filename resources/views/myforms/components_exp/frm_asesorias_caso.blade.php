@php
    $esEstudiante = currentUser()->hasRole('estudiante');
    $puedeVerTodasAsesorias = currentUser()->hasRole('dirgral') ||
        currentUser()->hasRole('diradmin') ||
        currentUser()->hasRole('docente') ||
        currentUser()->hasRole('coordprac') ||
        currentUser()->hasRole('amatai');
    $asesoriasVisibles = $expediente->asesorias_docente()
        ->where('estado', true)
        ->get()
        ->filter(function ($asesoria) use ($esEstudiante, $puedeVerTodasAsesorias) {
            return ($esEstudiante && $asesoria->apl_shared) || $puedeVerTodasAsesorias;
        });
@endphp

<section class="iuris-advisories" aria-labelledby="iuris-advisories-title">
    <header class="iuris-advisories-header">        

        @if (currentUser()->hasRole('docente') ||
            currentUser()->hasRole('amatai') ||
            currentUser()->hasRole('diradmin') ||
            currentUser()->hasRole('coordprac') ||
            currentUser()->hasRole('dirgral'))
            <button
                type="button"
                class="btn iuris-advisories-add"
                data-toggle="modal"
                data-target="#myModal_add_asesoria_docente"
            >
                <i class="fa fa-plus" aria-hidden="true"></i>
                Agregar asesoría
            </button>
        @endif
    </header>

    @if ($asesoriasVisibles->isEmpty())
        <div class="iuris-advisories-empty">
            <span class="iuris-advisories-empty-icon" aria-hidden="true">
                <i class="fa fa-comment"></i>
            </span>
            <div>
                <strong>No hay asesorías para mostrar</strong>
                <p>Las nuevas orientaciones registradas aparecerán aquí.</p>
            </div>
        </div>
    @else
        <div class="iuris-advisories-list">
            @foreach ($asesoriasVisibles as $asesoria)
                <article class="iuris-advisory-card">
                    <header class="iuris-advisory-card-header">
                        <div class="iuris-advisory-author">
                            <span class="iuris-advisory-avatar" aria-hidden="true">
                                <i class="fa fa-user"></i>
                            </span>
                            <div>
                                <span class="iuris-advisory-label">Docente</span>
                                <h4>{{ $asesoria->docente->name }} {{ $asesoria->docente->lastname }}</h4>
                            </div>
                        </div>

                        <div class="iuris-advisory-meta">
                            @if ($asesoria->apl_shared)
                                <span class="iuris-advisory-status is-shared">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                    Compartido con estudiante
                                </span>
                            @else
                                <span class="iuris-advisory-status is-private">
                                    <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                    Solo equipo docente
                                </span>
                            @endif

                            <time class="iuris-advisory-date" datetime="{{ $asesoria->created_at }}">
                                <i class="fa fa-clock-o" aria-hidden="true"></i>
                                {{ \Carbon\Carbon::parse($asesoria->created_at)->format('d/m/Y H:i') }}
                            </time>
                        </div>

                        @if (!$readonly && $asesoria->docente->idnumber == Auth::user()->idnumber)
                            <div class="dropdown iuris-advisory-actions">
                                <button
                                    type="button"
                                    class="btn iuris-advisory-menu-toggle"
                                    id="advisory-actions-{{ $asesoria->id }}"
                                    data-toggle="dropdown"
                                    aria-haspopup="true"
                                    aria-expanded="false"
                                    aria-label="Acciones para la asesoría de {{ $asesoria->docente->name }}"
                                >
                                    <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right iuris-advisory-menu"
                                    aria-labelledby="advisory-actions-{{ $asesoria->id }}">
                                    <a href="#" class="dropdown-item btn_edit_asesoria_caso" data-id="{{ $asesoria->id }}">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                        Editar asesoría
                                    </a>
                                    <a href="#" class="dropdown-item btn_delete_asesoria_caso" data-id="{{ $asesoria->id }}">
                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                        Eliminar asesoría
                                    </a>
                                </div>
                            </div>
                        @endif
                    </header>

                    <div class="iuris-advisory-content">
                        <p>{{ $asesoria->comentario }}</p>
                    </div>

                    @if (!$readonly && $asesoria->docente->idnumber == Auth::user()->idnumber)
                        <footer class="iuris-advisory-footer">
                            <span class="iuris-advisory-share-label">
                                Compartir esta asesoría con el estudiante
                            </span>
                            <button
                                type="button"
                                class="iuris-advisory-share-button chk_change_shared"
                                data-id="{{ $asesoria->id }}"
                                data-status="{{ $asesoria->apl_shared }}"
                                aria-label="{{ $asesoria->apl_shared ? 'Dejar de compartir asesoría' : 'Compartir asesoría con estudiante' }}"
                            >
                                <i
                                    id="switch_edit{{ $asesoria->id }}"
                                    class="fa {{ $asesoria->apl_shared ? 'fa-toggle-on switch-on' : 'fa-toggle-off switch-off' }}"
                                    aria-hidden="true"
                                ></i>
                                <span>{{ $asesoria->apl_shared ? 'Visible' : 'Privada' }}</span>
                            </button>
                        </footer>
                    @endif
                </article>
            @endforeach
        </div>
    @endif
</section>
