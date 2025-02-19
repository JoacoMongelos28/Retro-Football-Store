<?php

namespace App\Http\Controllers;

use App\Models\AdminModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\LoginModel;
use App\Http\Requests\AgregarCamisetaRequest;
use App\Http\Requests\EditarCamisetaRequest;
use Exception;
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
        try {
            $usuarioObtenido = $this->verificarUsuario();

            if ($usuarioObtenido instanceof RedirectResponse) {
                return $usuarioObtenido;
            }

            if (session('filtro') === null) {
                $data['camisetas'] = $this->adminModel->obtenerTodasLasCamisetas();
                $data['filtro'] = '';
            } else {
                $data['camisetas'] = $this->adminModel->filtrarCamisetas(session('filtro'));
                $data['filtro'] = session('filtro');
            }
            return view("AdminHome", $data);
        } catch (Exception $e) {
            Log::error("Error en admin(): " . $e->getMessage());
            return redirect()->route('error')->with('mensaje', 'Ocurrió un problema inesperado.');
        }
    }

    public function agregar()
    {
        try {
            $usuarioObtenido = $this->verificarUsuario();

            if ($usuarioObtenido instanceof RedirectResponse) {
                return $usuarioObtenido;
            }

            return view("AgregarCamiseta");
        } catch (Exception $e) {
            Log::error("Error en agregar(): " . $e->getMessage());
            return redirect()->route('error')->with('mensaje', 'Ocurrió un problema inesperado.');
        }
    }

    public function guardarCamiseta(AgregarCamisetaRequest $request)
    {
        try {
            $usuarioObtenido = $this->verificarUsuario();

            if ($usuarioObtenido instanceof RedirectResponse) {
                return $usuarioObtenido;
            }

            if ($request->hasFile('imagen') || $request->hasFile('imagen_trasera')) {
                foreach (['imagen', 'imagen_trasera'] as $img) {
                    if ($request->hasFile($img) && $request->file($img)->isValid()) {
                        if ($request->file($img)->getSize() > 2048 * 1024) {
                            return redirect('admin/agregar')->with('error', 'La imagen no puede ser mayor a 2MB.');
                        }
                    }
                }

                $resultado = $this->guardar($request);
            } else {
                return redirect('admin/agregar')->with('error', 'Debe subir una imagen.');
            }

            if ($resultado) {
                return redirect('admin')->with('exitoso', 'La camiseta se agregó correctamente!');
            } else {
                return redirect('admin/agregar')->with('error', 'Hubo un problema al agregar la camiseta.');
            }
        } catch (Exception $e) {
            Log::error("Error en guardarCamiseta(): " . $e->getMessage());
            return redirect('admin/agregar')->with('error', 'Ocurrió un problema inesperado al agregar la camiseta.');
        }
    }

    public function editar($slug)
    {
        try {
            $usuarioObtenido = $this->verificarUsuario();

            if ($usuarioObtenido instanceof RedirectResponse) {
                return $usuarioObtenido;
            }

            $camiseta = AdminModel::where('slug', $slug)->first();

            if (!$camiseta) {
                return redirect('/admin')->with('error', 'No se encontró la camiseta.');
            }

            $data = [
                'estado_destacado' => $camiseta->estado == 1 ? 'selected' : '',
                'estado_oferta' => $camiseta->estado == 2 ? 'selected' : '',
                'camiseta' => $camiseta
            ];

            return view("EditarCamiseta", $data);
        } catch (Exception $e) {
            return redirect('/admin')->with('error', 'Ocurrió un error inesperado: ' . $e->getMessage());
        }
    }

    public function editarCamiseta(EditarCamisetaRequest $request, $id)
    {
        try {
            $usuarioObtenido = $this->verificarUsuario();

            if ($usuarioObtenido instanceof RedirectResponse) {
                return $usuarioObtenido;
            }

            if (!is_numeric($id) || $id <= 0) {
                return redirect('admin')->with('error', 'El id de la camiseta no es válido.');
            }

            $resultado = $this->edicion($request, $id);

            if ($resultado) {
                return redirect('admin')->with('exitoso', 'La camiseta se editó correctamente!');
            } else {
                return redirect('admin')->with('error', 'Hubo un problema al editar la camiseta.');
            }
        } catch (Exception $e) {
            Log::error("Error en editarCamiseta(): " . $e->getMessage());
            return redirect('admin')->with('error', 'Ocurrió un problema inesperado al editar la camiseta.');
        }
    }

    public function eliminarCamiseta($id)
    {
        try {
            $usuarioObtenido = $this->verificarUsuario();

            if ($usuarioObtenido instanceof RedirectResponse) {
                return $usuarioObtenido;
            }

            if (!is_numeric($id) || $id <= 0) {
                return redirect('admin')->with('error', 'El id de la camiseta no es válido.');
            }

            $resultado = $this->adminModel->eliminarCamiseta($id);

            if ($resultado) {
                return redirect('admin')->with('exitoso', 'La camiseta se eliminó correctamente!');
            } else {
                return redirect('admin')->with('error', 'Hubo un problema al eliminar la camiseta.');
            }
        } catch (Exception $e) {
            Log::error("Error en eliminarCamiseta(): " . $e->getMessage());
            return redirect('admin')->with('error', 'Ocurrió un problema inesperado al eliminar la camiseta.');
        }
    }

    private function guardar(Request $request)
    {
        $nombre = $request->input('nombre');
        $descripcion = $request->input('descripcion');
        $precio = $request->input('precio');
        $estado = $request->input('estado');
        $cantidadXS = $request->input('cantidadXS') ? $request->input('cantidadXS') : 0;
        $cantidadS = $request->input('cantidadS') ? $request->input('cantidadS') : 0;
        $cantidadM = $request->input('cantidadM') ? $request->input('cantidadM') : 0;
        $cantidadL = $request->input('cantidadL') ? $request->input('cantidadL') : 0;
        $cantidadXL = $request->input('cantidadXL') ? $request->input('cantidadXL') : 0;
        $cantidadXXL = $request->input('cantidadXXL') ? $request->input('cantidadXXL') : 0;

        $imagen = $request->hasFile('imagen') && $request->file('imagen')->isValid()
            ? file_get_contents($request->file('imagen')->getRealPath())
            : null;

        $imagenTrasera = $request->hasFile('imagen_trasera') && $request->file('imagen_trasera')->isValid()
            ? file_get_contents($request->file('imagen_trasera')->getRealPath())
            : null;

        return $this->adminModel->guardarCamiseta($nombre, $descripcion, $precio, $estado, $imagen, $imagenTrasera, $cantidadXS, $cantidadS, $cantidadM, $cantidadL, $cantidadXL, $cantidadXXL);
    }

    private function edicion(Request $request, $id)
    {
        $nombre = $request->nombre;
        $descripcion = $request->descripcion;
        $precio = $request->precio;
        $estado = $request->estado;
        $cantidadXS = $request->input('cantidadXS') ? $request->input('cantidadXS') : 0;
        $cantidadS = $request->input('cantidadS') ? $request->input('cantidadS') : 0;
        $cantidadM = $request->input('cantidadM') ? $request->input('cantidadM') : 0;
        $cantidadL = $request->input('cantidadL') ? $request->input('cantidadL') : 0;
        $cantidadXL = $request->input('cantidadXL') ? $request->input('cantidadXL') : 0;
        $cantidadXXL = $request->input('cantidadXXL') ? $request->input('cantidadXXL') : 0;

        $imagen = $request->hasFile('imagen') && $request->file('imagen')->isValid()
            ? file_get_contents($request->file('imagen')->getRealPath())
            : null;

        $imagenTrasera = $request->hasFile('imagen_trasera') && $request->file('imagen_trasera')->isValid()
            ? file_get_contents($request->file('imagen_trasera')->getRealPath())
            : null;

        return $this->adminModel->actualizarCamiseta($id, $nombre, $descripcion, $precio, $estado, $imagen, $imagenTrasera, $cantidadXS, $cantidadS, $cantidadM, $cantidadL, $cantidadXL, $cantidadXXL);
    }

    public function filtrarCamisetas($filtrado)
    {
        $usuarioObtenido = $this->verificarUsuario();

        if ($usuarioObtenido instanceof RedirectResponse) {
            return $usuarioObtenido;
        }

        $camisetas = $this->adminModel->filtrarCamisetas($filtrado);
        $filtro = $filtrado;
        session(['filtro' => $filtro]);

        return view('adminHome', compact('camisetas', 'filtro'));
    }

    public function verCamiseta(AdminModel $camiseta)
    {
        $usuarioObtenido = $this->verificarUsuario();

        if ($usuarioObtenido instanceof RedirectResponse) {
            return $usuarioObtenido;
        }

        $camisetaObtenida = $this->adminModel->obtenerCamisetaConImagenPorId($camiseta->id);

        return view('AdminVerCamiseta', compact('camisetaObtenida'));
    }

    private function verificarUsuario()
    {
        $usuarioId = session('usuarioId');

        if ($usuarioId == null || $usuarioId <= 0) {
            return redirect('login');
        }

        $usuarioObtenido = $this->loginModel->obtenerUsuarioPorId($usuarioId);

        if ($usuarioObtenido->tipo_usuario != 1) {
            return redirect('home');
        }

        return $usuarioObtenido;
    }
}