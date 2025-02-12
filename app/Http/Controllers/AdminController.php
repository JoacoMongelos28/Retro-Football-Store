<?php

namespace App\Http\Controllers;

use App\Models\AdminModel;
use Illuminate\Http\Request;
use App\Models\LoginModel;
use App\Http\Requests\AgregarCamisetaRequest;
use App\Http\Requests\EditarCamisetaRequest;

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

    public function guardarCamiseta(AgregarCamisetaRequest $request)
    {
        if ($request->fails()) {
            return redirect('admin/agregar')->withErrors($request)->withInput();
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

    public function editar(AdminModel $camiseta)
    {
        $usuarioId = session('usuarioId');

        if ($usuarioId == null || $usuarioId <= 0) {
            return redirect('login');
        }

        $usuarioObtenido = $this->loginModel->obtenerUsuarioPorId($usuarioId);

        if ($usuarioObtenido->tipo_usuario != 1) {
            return redirect('home');
        }

        if (!is_numeric($camiseta->id) || $camiseta->id <= 0) {
            return redirect('admin/listado')->with('error', 'El id de la camiseta no es válido.');
        }

        $camisetaObtenida = $this->adminModel->obtenerCamisetaPorId($camiseta->id);

        if ($camisetaObtenida != null) {
            $data['estado_destacado'] = $camisetaObtenida['estado'] == 1 ? 'selected' : '';
            $data['estado_oferta'] = $camisetaObtenida['estado'] == 2 ? 'selected' : '';
            $data['camiseta'] = $camisetaObtenida;
        } else {
            return redirect('admin/listado')->with('error', 'No se encontró la camiseta.');
        }

        return view("EditarCamiseta", $data);
    }

    public function editarCamiseta(EditarCamisetaRequest $request, $id)
    {
        $resultado = $this->edicion($request, $id);

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

        return $this->adminModel->guardarCamiseta($nombre, $descripcion, $precio, $estado, $imagen, $cantidadXS, $cantidadS, $cantidadM, $cantidadL, $cantidadXL, $cantidadXXL);
    }

    private function edicion(Request $request, $id) {
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

        return $this->adminModel->actualizarCamiseta($id, $nombre, $descripcion, $precio, $estado, $imagen, $cantidadXS, $cantidadS, $cantidadM, $cantidadL, $cantidadXL, $cantidadXXL);
    }
}