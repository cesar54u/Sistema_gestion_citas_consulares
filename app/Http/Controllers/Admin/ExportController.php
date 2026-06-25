<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HistorialSolicitud;
use App\Models\Cita;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ExportController extends Controller
{
    // ──────────────────────────────────────────────────────────
    // EXPORTAR HISTORIAL
    // ──────────────────────────────────────────────────────────

    /** Vista previa PDF del historial (imprimible desde el navegador) */
    public function historialPdf(Request $request)
    {
        $query = $this->buildHistorialQuery($request);
        $historial = $query->get();
        $usuario   = $request->filled('usuario_id') ? User::find($request->usuario_id) : null;

        return view('admin.exports.historial-pdf', compact('historial', 'usuario', 'request'));
    }

    /** Descarga Excel (SpreadsheetML) del historial */
    public function historialExcel(Request $request)
    {
        $query = $this->buildHistorialQuery($request);
        $historial = $query->get();

        $rows = [
            ['#', 'Fecha Modificación', 'Usuario', 'Cédula', 'Servicio', 'Fecha Cita', 'Hora', 'Acción', 'Estado', 'Admin', 'Descripción'],
        ];

        foreach ($historial as $i => $item) {
            $rows[] = [
                $i + 1,
                $item->fecha_modificacion?->format('d/m/Y H:i') ?? '-',
                $item->usuario->nombre_completo ?? '-',
                $item->usuario->cedula ?? '-',
                $item->servicio->nombre_producto ?? '-',
                $item->fecha_cita?->format('d/m/Y') ?? '-',
                $item->hora_inicio ? substr($item->hora_inicio, 0, 5) : '-',
                ucfirst($item->accion ?? '-'),
                ucfirst($item->estado ?? '-'),
                $item->admin->nombre_completo ?? '-',
                $item->descripcion ?? '-',
            ];
        }

        $filename = 'historial_' . now()->format('Ymd_His') . '.xlsx';
        return $this->downloadXlsx($rows, 'Historial', $filename);
    }

    // ──────────────────────────────────────────────────────────
    // EXPORTAR CITAS
    // ──────────────────────────────────────────────────────────

    /** Vista previa PDF de citas */
    public function citasPdf(Request $request)
    {
        $query = $this->buildCitasQuery($request);
        $citas   = $query->get();
        $usuario = $request->filled('usuario_id') ? User::find($request->usuario_id) : null;

        return view('admin.exports.citas-pdf', compact('citas', 'usuario', 'request'));
    }

    /** Descarga Excel de citas */
    public function citasExcel(Request $request)
    {
        $query = $this->buildCitasQuery($request);
        $citas = $query->get();

        $rows = [
            ['#', 'Solicitada', 'Usuario', 'Cédula', 'Teléfono', 'Correo', 'Servicio', 'Tipo', 'Precio', 'Fecha Cita', 'Hora', 'Estado', 'Gestionada por', 'Notas'],
        ];

        foreach ($citas as $i => $cita) {
            $rows[] = [
                $cita->id,
                $cita->created_at->format('d/m/Y'),
                $cita->usuario->nombre_completo ?? '-',
                $cita->usuario->cedula ?? '-',
                $cita->usuario->telefono ?? '-',
                $cita->usuario->correo_electronico ?? '-',
                $cita->servicio->nombre_producto ?? '-',
                $cita->servicio->tipo ?? '-',
                '$' . number_format($cita->servicio->precio ?? 0, 2),
                $cita->fecha_cita->format('d/m/Y'),
                substr($cita->hora_inicio, 0, 5),
                ucfirst($cita->estado),
                $cita->admin->nombre_completo ?? '-',
                $cita->notas ?? '-',
            ];
        }

        $filename = 'citas_' . now()->format('Ymd_His') . '.xlsx';
        return $this->downloadXlsx($rows, 'Citas', $filename);
    }

    // ──────────────────────────────────────────────────────────
    // BUILDERS
    // ──────────────────────────────────────────────────────────

    private function buildHistorialQuery(Request $request)
    {
        $query = HistorialSolicitud::with(['usuario', 'servicio', 'admin'])
            ->orderByDesc('fecha_modificacion');

        if ($request->filled('usuario_id')) {
            $query->where('usuario_id', $request->usuario_id);
        }
        if ($request->filled('accion')) {
            $query->where('accion', $request->accion);
        }
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_cita', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_cita', '<=', $request->fecha_hasta);
        }
        return $query;
    }

    private function buildCitasQuery(Request $request)
    {
        $query = Cita::with(['usuario', 'servicio', 'admin'])
            ->orderByDesc('fecha_cita');

        if ($request->filled('usuario_id')) {
            $query->where('usuario_id', $request->usuario_id);
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_cita', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_cita', '<=', $request->fecha_hasta);
        }
        if ($request->filled('servicio_id')) {
            $query->where('servicio_id', $request->servicio_id);
        }
        return $query;
    }

    // ──────────────────────────────────────────────────────────
    // XLSX GENERATOR (SpreadsheetML — no external dependencies)
    // ──────────────────────────────────────────────────────────

    private function downloadXlsx(array $rows, string $sheetName, string $filename): Response
    {
        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"';
        $xml .= ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">';
        $xml .= '<Styles>';
        $xml .= '<Style ss:ID="header"><Font ss:Bold="1"/><Interior ss:Color="#C60B1E" ss:Pattern="Solid"/><Font ss:Color="#FFFFFF" ss:Bold="1"/></Style>';
        $xml .= '<Style ss:ID="even"><Interior ss:Color="#F9FAFB" ss:Pattern="Solid"/></Style>';
        $xml .= '</Styles>';
        $xml .= '<Worksheet ss:Name="' . htmlspecialchars($sheetName) . '">';
        $xml .= '<Table>';

        foreach ($rows as $ri => $row) {
            $styleAttr = $ri === 0 ? ' ss:StyleID="header"' : ($ri % 2 === 0 ? ' ss:StyleID="even"' : '');
            $xml .= "<Row{$styleAttr}>";
            foreach ($row as $cell) {
                $type  = is_numeric($cell) ? 'Number' : 'String';
                $value = htmlspecialchars((string) $cell);
                $xml  .= "<Cell><Data ss:Type=\"{$type}\">{$value}</Data></Cell>";
            }
            $xml .= '</Row>';
        }

        $xml .= '</Table></Worksheet></Workbook>';

        return response($xml, 200, [
            'Content-Type'        => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control'       => 'max-age=0',
        ]);
    }
}
