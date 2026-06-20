@extends('layouts.app')
@section('title', 'Agendar Cita | Portal Consular')

@push('styles')
<style>
    .step-indicator { display: flex; gap: 0; margin-bottom: 2rem; }
    .step { flex: 1; text-align: center; position: relative; }
    .step::after { content: ''; position: absolute; top: 18px; left: 50%; width: 100%; height: 2px; background: #e5e7eb; z-index: 0; }
    .step:last-child::after { display: none; }
    .step-circle { width: 36px; height: 36px; border-radius: 50%; background: #e5e7eb; color: #9ca3af; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.5rem; font-size: 0.85rem; font-weight: 700; position: relative; z-index: 1; transition: all 0.3s; }
    .step.active .step-circle { background: #c60b1e; color: white; box-shadow: 0 4px 12px rgba(198,11,30,0.4); }
    .step.done .step-circle { background: #059669; color: white; }
    .step-label { font-size: 0.72rem; color: #9ca3af; font-weight: 500; }
    .step.active .step-label { color: #c60b1e; font-weight: 600; }
    .horario-btn { padding: 0.6rem 1rem; border: 1.5px solid #e5e7eb; border-radius: 10px; background: white; cursor: pointer; font-size: 0.85rem; font-weight: 500; transition: all 0.2s; }
    .horario-btn:hover { border-color: #c60b1e; color: #c60b1e; }
    .horario-btn.selected { background: #c60b1e; border-color: #c60b1e; color: white; }
    .horario-btn:disabled { opacity: 0.4; cursor: not-allowed; }
    #horariosContainer { display: flex; flex-wrap: wrap; gap: 0.5rem; }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <h1 class="page-title">Agendar Nueva Cita</h1>
        <p class="page-breadcrumb">Selecciona el servicio, fecha y horario de tu preferencia</p>
    </div>
    <a href="{{ route('dashboard') }}" class="btn btn-sm" style="border:1.5px solid #e5e7eb;border-radius:10px;font-size:0.85rem;">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>
</div>

<!-- Indicador de pasos -->
<div class="step-indicator mb-4">
    <div class="step active" id="step1-ind">
        <div class="step-circle">1</div>
        <div class="step-label">Servicio</div>
    </div>
    <div class="step" id="step2-ind">
        <div class="step-circle">2</div>
        <div class="step-label">Fecha</div>
    </div>
    <div class="step" id="step3-ind">
        <div class="step-circle">3</div>
        <div class="step-label">Horario</div>
    </div>
    <div class="step" id="step4-ind">
        <div class="step-circle">4</div>
        <div class="step-label">Confirmar</div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-body p-4">
                <form action="{{ route('citas.store') }}" method="POST" id="citaForm">
                    @csrf

                    <!-- PASO 1: Servicio -->
                    <div id="step1">
                        <h6 class="fw-bold mb-3"><i class="bi bi-briefcase-fill me-2" style="color:#c60b1e;"></i>Selecciona el Servicio Consular</h6>
                        <div class="row g-3">
                            @foreach($servicios as $servicio)
                            <div class="col-md-6">
                                <label class="d-block" style="cursor:pointer;">
                                    <input type="radio" name="servicio_id" value="{{ $servicio->id }}" class="d-none servicio-radio" {{ old('servicio_id') == $servicio->id ? 'checked' : '' }}>
                                    <div class="servicio-card p-3" style="border:2px solid #e5e7eb;border-radius:14px;transition:all 0.2s;">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <span class="fw-600" style="font-size:0.9rem;">{{ $servicio->nombre_producto }}</span>
                                            <span class="badge" style="background:#fef2f2;color:#c60b1e;font-size:0.7rem;">{{ $servicio->tipo }}</span>
                                        </div>
                                        <p class="text-muted mb-2" style="font-size:0.78rem;line-height:1.4;">{{ Str::limit($servicio->descripcion, 80) }}</p>
                                        <div class="d-flex gap-3" style="font-size:0.75rem;color:#6b7280;">
                                            <span><i class="bi bi-clock me-1"></i>{{ $servicio->duracion }} min</span>
                                            <span><i class="bi bi-tag me-1"></i>{{ $servicio->precio > 0 ? '$' . number_format($servicio->precio, 2) : 'Gratuito' }}</span>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            <button type="button" class="btn btn-primary text-white px-4" onclick="goStep(2)">
                                Continuar <i class="bi bi-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </div>

                    <!-- PASO 2: Fecha -->
                    <div id="step2" style="display:none;">
                        <h6 class="fw-bold mb-3"><i class="bi bi-calendar3 me-2" style="color:#c60b1e;"></i>Selecciona la Fecha</h6>
                        <div class="mb-3">
                            <label class="form-label">Fecha de la Cita</label>
                            <input type="date" name="fecha_cita" id="fechaCita" class="form-control"
                                   min="{{ date('Y-m-d') }}"
                                   value="{{ old('fecha_cita') }}"
                                   onchange="cargarHorarios()">
                            <div class="form-text">Disponible de lunes a viernes</div>
                        </div>
                        <div class="d-flex gap-2 mt-4">
                            <button type="button" class="btn btn-sm" style="border:1.5px solid #e5e7eb;border-radius:10px;" onclick="goStep(1)">
                                <i class="bi bi-arrow-left me-1"></i>Atrás
                            </button>
                            <button type="button" class="btn btn-primary text-white px-4" onclick="goStep(3)">
                                Continuar <i class="bi bi-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </div>

                    <!-- PASO 3: Horario -->
                    <div id="step3" style="display:none;">
                        <h6 class="fw-bold mb-3"><i class="bi bi-clock me-2" style="color:#c60b1e;"></i>Selecciona el Horario</h6>
                        <input type="hidden" name="hora_inicio" id="horaSeleccionada">

                        <div id="horariosContainer">
                            <p class="text-muted" style="font-size:0.875rem;">
                                <i class="bi bi-info-circle me-1"></i>Selecciona primero una fecha para ver los horarios disponibles.
                            </p>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="button" class="btn btn-sm" style="border:1.5px solid #e5e7eb;border-radius:10px;" onclick="goStep(2)">
                                <i class="bi bi-arrow-left me-1"></i>Atrás
                            </button>
                            <button type="button" class="btn btn-primary text-white px-4" onclick="goStep(4)">
                                Continuar <i class="bi bi-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </div>

                    <!-- PASO 4: Confirmar -->
                    <div id="step4" style="display:none;">
                        <h6 class="fw-bold mb-3"><i class="bi bi-check-circle me-2" style="color:#c60b1e;"></i>Confirma tu Cita</h6>

                        <div class="p-3 mb-3" style="background:#f8fafd;border-radius:12px;border:1px solid #e5e7eb;">
                            <div class="row g-2" style="font-size:0.875rem;">
                                <div class="col-6"><span class="text-muted">Servicio:</span></div>
                                <div class="col-6 fw-600" id="resumen-servicio">-</div>
                                <div class="col-6"><span class="text-muted">Fecha:</span></div>
                                <div class="col-6 fw-600" id="resumen-fecha">-</div>
                                <div class="col-6"><span class="text-muted">Horario:</span></div>
                                <div class="col-6 fw-600" id="resumen-hora">-</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notas adicionales <span class="text-muted fw-normal">(opcional)</span></label>
                            <textarea name="notas" class="form-control" rows="3" placeholder="Información adicional que el consulado deba conocer...">{{ old('notas') }}</textarea>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="button" class="btn btn-sm" style="border:1.5px solid #e5e7eb;border-radius:10px;" onclick="goStep(3)">
                                <i class="bi bi-arrow-left me-1"></i>Atrás
                            </button>
                            <button type="submit" class="btn btn-primary text-white px-4">
                                <i class="bi bi-calendar-check me-2"></i>Confirmar Cita
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Panel informativo -->
    <div class="col-lg-5">
        <div class="card" style="background:linear-gradient(135deg,#1a1a2e,#0f3460);border:none;">
            <div class="card-body p-4 text-white">
                <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2" style="color:#ffc400;"></i>Información Importante</h6>
                <ul class="list-unstyled" style="font-size:0.82rem;line-height:2;">
                    <li><i class="bi bi-check2 me-2" style="color:#ffc400;"></i>Llega 10 minutos antes de tu cita</li>
                    <li><i class="bi bi-check2 me-2" style="color:#ffc400;"></i>Trae original y copia de tus documentos</li>
                    <li><i class="bi bi-check2 me-2" style="color:#ffc400;"></i>La cita será confirmada por el consulado</li>
                    <li><i class="bi bi-check2 me-2" style="color:#ffc400;"></i>Recibirás notificación por correo</li>
                    <li><i class="bi bi-x me-2" style="color:#f87171;"></i>No se permiten citas duplicadas por día</li>
                </ul>
                <hr style="border-color:rgba(255,255,255,0.2);">
                <p style="font-size:0.78rem;opacity:0.7;" class="mb-0">
                    <i class="bi bi-geo-alt me-1"></i>
                    Av. Constitución, Maracay, Estado Aragua · Venezuela
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentStep = 1;

// Marcar servicios seleccionados visualmente
document.querySelectorAll('.servicio-radio').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.servicio-card').forEach(c => {
            c.style.borderColor = '#e5e7eb';
            c.style.background = 'white';
        });
        this.nextElementSibling.style.borderColor = '#c60b1e';
        this.nextElementSibling.style.background = '#fef2f2';
    });

    // Restaurar al cargar si hay valor antiguo
    if (radio.checked) {
        radio.nextElementSibling.style.borderColor = '#c60b1e';
        radio.nextElementSibling.style.background = '#fef2f2';
    }
});

function goStep(n) {
    // Validar paso actual
    if (n > currentStep) {
        if (currentStep === 1 && !document.querySelector('.servicio-radio:checked')) {
            alert('Por favor selecciona un servicio.');
            return;
        }
        if (currentStep === 2 && !document.getElementById('fechaCita').value) {
            alert('Por favor selecciona una fecha.');
            return;
        }
        if (currentStep === 3 && !document.getElementById('horaSeleccionada').value) {
            alert('Por favor selecciona un horario.');
            return;
        }
    }

    document.getElementById('step' + currentStep).style.display = 'none';
    document.getElementById('step' + n).style.display = 'block';

    // Actualizar indicadores
    for (let i = 1; i <= 4; i++) {
        const ind = document.getElementById('step' + i + '-ind');
        ind.classList.remove('active', 'done');
        if (i < n) ind.classList.add('done');
        if (i === n) ind.classList.add('active');
    }

    if (n === 4) actualizarResumen();
    currentStep = n;
}

function cargarHorarios() {
    const fecha = document.getElementById('fechaCita').value;
    const servicioId = document.querySelector('.servicio-radio:checked')?.value;

    if (!fecha || !servicioId) return;

    const container = document.getElementById('horariosContainer');
    container.innerHTML = '<div class="text-muted" style="font-size:0.85rem;"><i class="bi bi-arrow-clockwise me-1"></i>Cargando horarios...</div>';
    document.getElementById('horaSeleccionada').value = '';

    fetch(`/citas/horarios-disponibles?fecha=${fecha}&servicio_id=${servicioId}`)
        .then(r => r.json())
        .then(data => {
            container.innerHTML = '';
            if (!data.horarios || data.horarios.length === 0) {
                container.innerHTML = '<p class="text-muted" style="font-size:0.85rem;"><i class="bi bi-calendar-x me-1"></i>' + (data.mensaje || 'No hay horarios disponibles para este día.') + '</p>';
                return;
            }
            data.horarios.forEach(h => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'horario-btn';
                btn.textContent = h.hora;
                btn.onclick = function() {
                    document.querySelectorAll('.horario-btn').forEach(b => b.classList.remove('selected'));
                    this.classList.add('selected');
                    document.getElementById('horaSeleccionada').value = h.hora;
                };
                container.appendChild(btn);
            });
        })
        .catch(() => {
            container.innerHTML = '<p class="text-danger" style="font-size:0.85rem;">Error al cargar horarios. Intente de nuevo.</p>';
        });
}

function actualizarResumen() {
    const servicioRadio = document.querySelector('.servicio-radio:checked');
    const fecha = document.getElementById('fechaCita').value;
    const hora = document.getElementById('horaSeleccionada').value;

    if (servicioRadio) {
        const card = servicioRadio.nextElementSibling;
        document.getElementById('resumen-servicio').textContent = card.querySelector('.fw-600').textContent;
    }

    if (fecha) {
        const d = new Date(fecha + 'T00:00:00');
        document.getElementById('resumen-fecha').textContent = d.toLocaleDateString('es-VE', { weekday:'long', day:'numeric', month:'long', year:'numeric' });
    }

    document.getElementById('resumen-hora').textContent = hora || '-';
}
</script>
@endpush
