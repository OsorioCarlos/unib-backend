<?php

namespace App\Http\Controllers;

use App\Models\Catalogue;
use App\Models\Organization;
use App\Models\Resource;
use App\Jobs\CargaMasivaJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CargaMasivaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tipo_carga = $request->get('tipo');

        // Crear una instancia de Spreadsheet
        $spreadsheet = new Spreadsheet();

        // Agregar datos a la hoja 1 de cálculo
        $hoja1 = $spreadsheet->getActiveSheet();
        $hoja1->setTitle('Carga');

        switch ($tipo_carga) {
            case 'usuario_director_carrera':
                $hoja1->setCellValue('A1', 'CEDULA');
                $hoja1->setCellValue('B1', 'NOMBRE COMPLETO');
                $hoja1->setCellValue('C1', 'EMAIL');
                $hoja1->setCellValue('D1', 'CONTRASEÑA');
                $hoja1->setCellValue('E1', 'CARRERA');
                $hoja1->setCellValue('A2', '1111111111');
                $hoja1->setCellValue('B2', 'USUARIO EJEMPLO');
                $hoja1->setCellValue('C2', 'usuario_ejemplo@test.com');
                $hoja1->setCellValue('D2', '1234567890');
                $hoja1->setCellValue('E2', '28');

                // Hoja 2
                $hoja2 = $spreadsheet->createSheet();
                $hoja2->setTitle('Carreras');
                $hoja2->setCellValue('A1', 'CODIGO');
                $hoja2->setCellValue('B1', 'NOMBRE');
                $recurso = Resource::where('nombre', 'CARRERAS')->first();
                if ($recurso) {
                    $catalogos = Catalogue::where('resource_id', $recurso->id)->get();
                    foreach ($catalogos as $key => $catalogo) {
                        $hoja2->setCellValue([1, $key + 2], $catalogo['id']);
                        $hoja2->setCellValue([2, $key + 2], $catalogo['nombre']);
                    }
                }
                break;
            case 'usuario_representante_practicas':
                $hoja1->setCellValue('A1', 'CEDULA');
                $hoja1->setCellValue('B1', 'NOMBRE COMPLETO');
                $hoja1->setCellValue('C1', 'EMAIL');
                $hoja1->setCellValue('D1', 'CONTRASEÑA');
                $hoja1->setCellValue('E1', 'ORGANIZACION');
                $hoja1->setCellValue('A2', '1111111111');
                $hoja1->setCellValue('B2', 'USUARIO EJEMPLO');
                $hoja1->setCellValue('C2', 'usuario_ejemplo@test.com');
                $hoja1->setCellValue('D2', '1234567890');
                $hoja1->setCellValue('E2', '1');

                // Hoja 2
                $hoja2 = $spreadsheet->createSheet();
                $hoja2->setTitle('Orgnaizaciones');
                $hoja2->setCellValue('A1', 'CODIGO');
                $hoja2->setCellValue('B1', 'RAZON SOCIAL');
                $organizaciones = Organization::select(['id', 'razon_social'])->get();
                    foreach ($organizaciones as $key => $organizacion) {
                        $hoja2->setCellValue([1, $key + 2], $organizacion['id']);
                        $hoja2->setCellValue([2, $key + 2], $organizacion['razon_social']);
                    }
                break;
            case 'usuario_estudiante':
                $hoja1->setCellValue('A1', 'CEDULA');
                $hoja1->setCellValue('B1', 'NOMBRE COMPLETO');
                $hoja1->setCellValue('D1', 'CONTRASEÑA');
                $hoja1->setCellValue('C1', 'EMAIL');
                $hoja1->setCellValue('E1', 'CARRERA');
                $hoja1->setCellValue('F1', 'NIVEL');
                $hoja1->setCellValue('A2', '1111111111');
                $hoja1->setCellValue('B2', 'USUARIO EJEMPLO');
                $hoja1->setCellValue('C2', 'usuario_ejemplo@test.com');
                $hoja1->setCellValue('D2', '1234567890');
                $hoja1->setCellValue('E2', '28');
                $hoja1->setCellValue('F2', '6');

                // Hoja 2
                $hoja2 = $spreadsheet->createSheet();
                $hoja2->setTitle('Carreras');
                $hoja2->setCellValue('A1', 'CODIGO');
                $hoja2->setCellValue('B1', 'NOMBRE');
                $recurso = Resource::where('nombre', 'CARRERAS')->first();
                if ($recurso) {
                    $catalogos = Catalogue::where('resource_id', $recurso->id)->get();
                    foreach ($catalogos as $key => $catalogo) {
                        $hoja2->setCellValue([1, $key + 2], $catalogo['id']);
                        $hoja2->setCellValue([2, $key + 2], $catalogo['nombre']);
                    }
                }

                // Hoja 3
                $hoja3 = $spreadsheet->createSheet();
                $hoja3->setTitle('Niveles');
                $hoja3->setCellValue('A1', 'CODIGO');
                $hoja3->setCellValue('B1', 'NOMBRE');
                $recurso = Resource::where('nombre', 'NIVELES')->first();
                if ($recurso) {
                    $catalogos = Catalogue::where('resource_id', $recurso->id)->get();
                    foreach ($catalogos as $key => $catalogo) {
                        $hoja3->setCellValue([1, $key + 2], $catalogo['id']);
                        $hoja3->setCellValue([2, $key + 2], $catalogo['nombre']);
                    }
                }
                break;
            case 'organizacion':
                $hoja1->setCellValue('A1', 'RUC');
                $hoja1->setCellValue('B1', 'RAZON SOCIAL');
                $hoja1->setCellValue('C1', 'REPRESENTANTE LEGAL');
                $hoja1->setCellValue('D1', 'DIRECCION');
                $hoja1->setCellValue('E1', 'TELEFONO');
                $hoja1->setCellValue('F1', 'EMAIL');
                $hoja1->setCellValue('G1', 'AREA DEDICACION');
                $hoja1->setCellValue('H1', 'HORARIO');
                $hoja1->setCellValue('I1', 'DIAS LABORABLES');
                $hoja1->setCellValue('A2', '1111111111001');
                $hoja1->setCellValue('B2', 'ORGANIZACION EJEMPLO');
                $hoja1->setCellValue('C2', 'NOMBRE REPRESENTANTE');
                $hoja1->setCellValue('D2', 'DIRECCION...');
                $hoja1->setCellValue('E2', '023456789');
                $hoja1->setCellValue('F2', 'organizacion_ejemplo@test.com');
                $hoja1->setCellValue('G2', 'EMPRESA DE SOFTWARE');
                $hoja1->setCellValue('H2', '8AM A 5PM');
                $hoja1->setCellValue('I2', 'LUNES A VIERNES');
                break;
            default:
                break;
        }

        // Definir el nombre del archivo
        $archivo = 'formato_carga.xlsx';

        // Crear un objeto de Writer para guardar la hoja de cálculo en un archivo
        $writer = new Xlsx($spreadsheet);
        $writer->save($archivo);


        return response()->json([
            'data' => $archivo,
            'mensaje' => 'OK'
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->hasFile('archivo')) {
            $tipo_carga = $request->get('tipo');
            $archivo = $request->file('archivo');

            $headerTotal = 0;
            $headerText = '';
            $jsonResponse = null;
            switch ($tipo_carga) {
                case 'usuario_administrador':
                    $headerTotal = 4;
                    $headerText = 'CEDULA;NOMBRE COMPLETO;EMAIL;CONTRASEÑA';
                    break;
                case 'usuario_area_vinculacion':
                    $headerTotal = 4;
                    $headerText = 'CEDULA;NOMBRE COMPLETO;EMAIL;CONTRASEÑA';
                    break;
                case 'usuario_director_carrera':
                    $headerTotal = 5;
                    $headerText = 'CEDULA;NOMBRE COMPLETO;EMAIL;CONTRASEÑA;CARRERA';
                    break;
                case 'usuario_representante_practicas':
                    $headerTotal = 5;
                    $headerText = 'CEDULA;NOMBRE COMPLETO;EMAIL;CONTRASEÑA;ORGANIZACION';
                    break;
                case 'usuario_estudiante':
                    $headerTotal = 5;
                    $headerText = 'CEDULA;NOMBRE COMPLETO;EMAIL;CONTRASEÑA;CARRERA;NIVEL';
                    break;
                case 'organizacion':
                    $headerTotal = 9;
                    $headerText = 'RUC;RAZON SOCIAL;REPRESENTANTE LEGAL;DIRECCION;TELEFONO;EMAIL;AREA DEDICACION;HORARIO;DIAS LABORABLES';
                    break;
                default:
                    $jsonResponse = [
                        'mensaje' => 'ERROR',
                        'data' => 'No existe la configuración para esta carga'
                    ];
                    break;
            }

            // Cargar el archivo Excel
            $spreadsheet = IOFactory::load($archivo);
            // Obtener la primera hoja de cálculo
            $hoja = $spreadsheet->getActiveSheet();
            // Obtener todas las filas como un array
            $datos = $hoja->toArray();
            if (count($datos[0]) === $headerTotal && (implode(';', $datos[0])) === $headerText) {
                foreach ($datos as $key => $dato) {
                    if ($key > 0) {
                        CargaMasivaJob::dispatch($tipo_carga, $dato);
                    }
                }

                $jsonResponse = [
                    'mensaje' => 'OK',
                    'data' => 'Archivo cargado con éxito'
                ];
            } else {
                $jsonResponse = [
                    'mensaje' => 'ERROR',
                    'data' => 'La estructura del archivo es incorrecta'
                ];
            }

            return response()->json($jsonResponse, 200);
        } else {
            return response()->json([
                'mensaje' => 'ERROR',
                'data' => 'No existe el archivo cargado'
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    
}
