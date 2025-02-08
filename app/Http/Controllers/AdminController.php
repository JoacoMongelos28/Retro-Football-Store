<?php

namespace App\Http\Controllers;

use App\Models\AdminModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\LoginModel;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{

    private $adminModel;
    private $loginModel;

    public function __construct()
    {
        $this->adminModel = new AdminModel();
        $this->loginModel = new LoginModel();
    }

    public function admin()
    {
        $usuarioId = session('usuarioId');

        if ($usuarioId == null || $usuarioId <= 0) {
            return redirect('login');
        }

        $usuarioObtenido = $this->loginModel->obtenerUsuarioPorId($usuarioId);

        if ($usuarioObtenido->tipo_usuario != 1) {
            return redirect('home');
        }

        return view("AdminHome");
    }

    public function agregar()
    {
        $usuarioId = session('usuarioId');

        if ($usuarioId == null || $usuarioId <= 0) {
            return redirect('login');
        }

        $usuarioObtenido = $this->loginModel->obtenerUsuarioPorId($usuarioId);

        if ($usuarioObtenido->tipo_usuario != 1) {
            return redirect('home');
        }

        return view("AgregarCamiseta");
    }

    public function guardarCamiseta(Request $request)
    {
        $data = $this->validarCamiseta($request);

        if ($data->fails()) {
            return redirect('admin/agregar')->withErrors($data)->withInput();
        }

        if ($request->hasFile('imagen') && $request->file('imagen')->isValid()) {
            if ($request->file('imagen')->getSize() > 2048 * 1024) {
                return redirect('admin/agregar')->with('error', 'La imagen no puede ser mayor a 2MB.');
            }

            $resultado = $this->guardar($request);
        }

        if ($resultado) {
            return redirect('admin')->with('exitoso', 'La camiseta se agregó correctamente!');
        } else {
            return redirect('admin/agregar')->with('error', 'Hubo un problema al agregar la camiseta.');
        }
    }

    public function listado()
    {
        $usuarioId = session('usuarioId');

        if ($usuarioId == null || $usuarioId <= 0) {
            return redirect('login');
        }

        $usuarioObtenido = $this->loginModel->obtenerUsuarioPorId($usuarioId);

        if ($usuarioObtenido->tipo_usuario != 1) {
            return redirect('home');
        }

        $data['camisetas'] = $this->adminModel->obtenerTodasLasCamisetas();
        return view("AdminListado", $data);
    }

    public function editar($id)
    {

        $usuarioId = session('usuarioId');

        if ($usuarioId == null || $usuarioId <= 0) {
            return redirect('login');
        }

        $usuarioObtenido = $this->loginModel->obtenerUsuarioPorId($usuarioId);

        if ($usuarioObtenido->tipo_usuario != 1) {
            return redirect('home');
        }

        if (!is_numeric($id) || $id <= 0) {
            return redirect('admin/listado')->with('error', 'El id de la camiseta no es válido.');
        }

        $camisetaObtenida = $this->adminModel->obtenerCamisetaPorId($id);

        if ($camisetaObtenida != null) {
            $data['estado_destacado'] = $camisetaObtenida['estado'] == 1 ? 'selected' : '';
            $data['estado_oferta'] = $camisetaObtenida['estado'] == 2 ? 'selected' : '';
            $data['camiseta'] = $camisetaObtenida;
        } else {
            return redirect('admin/listado')->with('error', 'No se encontró la camiseta.');
        }

        return view("EditarCamiseta", $data);
    }

    public function editarCamiseta(Request $request, $id)
    {
        $data = $this->validacionesParaEditarLaCamiseta($request);

        if ($data->fails()) {
            return redirect('admin/editar/' . $id)->withErrors($data)->withInput();
        }

        $nombre = $request->nombre;
        $descripcion = $request->descripcion;
        $precio = $request->precio;
        $estado = $request->estado;
        $cantidadXS = $request->input('cantidadXS') ? $request->input('cantidadXS') : 0;
        $cantidadS = $request->input('cantidadS') ? $request->input('cantidadS') : 0;
        $cantidadM = $request->input('cantidadM') ? $request->input('cantidadM') : 0;
        $cantidadL = $request->input('cantidadL') ? $request->input('cantidadL') : 0;
        $cantidadXL = $request->input('cantidadXL') ? $request->input('cantidadXL') : 0;
        $cantidadXXL = $request->filled('cantidadXXL') ? $request->input('cantidadXXL') : 0;

        if ($request->hasFile('imagen') && $request->file('imagen')->isValid()) {
            $imagen = file_get_contents($request->file('imagen')->getRealPath());
        } else {
            $imagen = null;
        }

        $resultado = $this->adminModel->actualizarCamiseta($id, $nombre, $descripcion, $precio, $estado, $imagen, $cantidadXS, $cantidadS, $cantidadM, $cantidadL, $cantidadXL, $cantidadXXL);

        if ($resultado) {
            return redirect('admin/listado')->with('exitoso', 'La camiseta se editó correctamente!');
        } else {
            return redirect('admin/listado')->with('error', 'Hubo un problema al editar la camiseta.');
        }
    }

    public function eliminarCamiseta($id)
    {
        $usuarioId = session('usuarioId');

        if ($usuarioId == null || $usuarioId <= 0) {
            return redirect('login');
        }

        $usuarioObtenido = $this->loginModel->obtenerUsuarioPorId($usuarioId);

        if ($usuarioObtenido->tipo_usuario != 1) {
            return redirect('home');
        }
        
        if (!is_numeric($id) || $id <= 0) {
            return redirect('admin/listado')->with('error', 'El id de la camiseta no es válido.');
        }

        $resultado = $this->adminModel->eliminarCamiseta($id);

        if ($resultado) {
            return redirect('admin/listado')->with('exitoso', 'La camiseta se eliminó correctamente!');
        } else {
            return redirect('admin/listado')->with('error', 'Hubo un problema al eliminar la camiseta.');
        }
    }

    private function validarCamiseta(Request $request)
    {
        return Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric',
            'estado' => 'required|integer',
            'imagen' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'cantidadXS' => 'nullable|integer|min:0',
            'cantidadS' => 'nullable|integer|min:0',
            'cantidadM' => 'nullable|integer|min:0',
            'cantidadL' => 'nullable|integer|min:0',
            'cantidadXL' => 'nullable|integer|min:0',
            'cantidadXXL' => 'nullable|integer|min:0'
        ], [
            'imagen.required' => 'No subiste ninguna imagen. Por favor, selecciona una imagen.',
            'imagen.image' => 'El archivo que subiste no es una imagen. Por favor, selecciona una imagen válida (JPEG, PNG o JPG).',
            'imagen.mimes' => 'La imagen debe ser de tipo JPEG, PNG o JPG.',
            'imagen.max' => 'La imagen no puede ser mayor a 2MB. Por favor, sube una imagen más pequeña.',
            'nombre.required' => 'El nombre de la camiseta es requerido.',
            'descripcion.required' => 'La descripción de la camiseta es requerida.',
            'precio.required' => 'El precio de la camiseta es requerido.'
        ]);
    }

    private function guardar(Request $request)
    {
        $nombre = $request->input('nombre');
        $descripcion = $request->input('descripcion');
        $precio = $request->input('precio');
        $estado = $request->input('estado');
        $imagen = file_get_contents($request->file('imagen')->getRealPath());
        $cantidadXS = $request->input('cantidadXS') ? $request->input('cantidadXS') : 0;
        $cantidadS = $request->input('cantidadS') ? $request->input('cantidadS') : 0;
        $cantidadM = $request->input('cantidadM') ? $request->input('cantidadM') : 0;
        $cantidadL = $request->input('cantidadL') ? $request->input('cantidadL') : 0;
        $cantidadXL = $request->input('cantidadXL') ? $request->input('cantidadXL') : 0;
        $cantidadXXL = $request->filled('cantidadXXL') ? $request->input('cantidadXXL') : 0;

        Log::info("XXL: " . $cantidadXXL);

        return $this->adminModel->guardarCamiseta($nombre, $descripcion, $precio, $estado, $imagen, $cantidadXS, $cantidadS, $cantidadM, $cantidadL, $cantidadXL, $cantidadXXL);
    }

    private function validacionesParaEditarLaCamiseta(Request $request)
    {
        return Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric',
            'estado' => 'required|integer',
            'imagen' => 'image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'imagen.image' => 'El archivo que subiste no es una imagen. Por favor, selecciona una imagen válida (JPEG, PNG o JPG).',
            'imagen.mimes' => 'La imagen debe ser de tipo JPEG, PNG o JPG.',
            'imagen.max' => 'La imagen no puede ser mayor a 2MB. Por favor, sube una imagen más pequeña.',
            'nombre.required' => 'El nombre de la camiseta es requerido.',
            'descripcion.required' => 'La descripción de la camiseta es requerida.',
            'precio.required' => 'El precio de la camiseta es requerido.'
        ]);
    }
}