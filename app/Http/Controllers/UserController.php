<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function __construct() {
        $this->middleware("can:usuarios.index")->only('index', 'create', 'store', 'edit', 'update', 'destroy');
    }

    public function usuarios() {
        return view('admin.usuarios.index');
    } 

    public function perfil() {
        return view('profile.perfil');
    }
    
    public function usuariosDT(Request $request) {
        $users = User::all();

        $dt = DataTables::of($users)
            ->editColumn('status', function(User $user) {
                $status = ($user->status != 1) ? 'Inactivo' : 'Activo' ;
                return $status;
            })
            ->addColumn('opciones', function (User $user) {
                return view('admin.datatables.actions_users', [
                    'nombre'    => $user->name,
                    'id'    => $user->id,
                    'status'    => $user->status,
                    'rol'       => $user->getRoleNames()->first(),
                ]);
            })
            ->addColumn('rol', function(User $user) {
                return $user->getRoleNames()->first();
            })
            ->toJson();

        return $dt;
    }

    public function create() {
        $roles = Role::all();
        $permissions = Permission::where('id', '>', 1)->get();

        return view('admin.usuarios.create', [
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }

    public function store(Request $request) {
        $request->validate(
            [
                'nombre'    => 'required',
                'usuario'  => 'required|unique:users,username',
                'clave'  => [Password::min(8), 'required', 'confirmed'],
                'rol'  => ['required', 'exists:roles,id'],
            ],
            [
                'usuario.required' => 'El usuario es requerido',
                'usuario.unique'   => 'Este nombre de usuario ya está en uso',
            ]
        );

        //return $request;
        try {
            DB::beginTransaction();

            $user = new User();
            
            $user->name = $request->nombre;
            $user->username = $request->usuario;
            $user->password = Hash::make($request->clave);
            $user->profile_photo_path = 'user-stock.png';
            $user->save();

            $user->roles()->sync($request->rol);

            if ($request->rol == 3) {
                $user->givePermissionTo($request->permisos);
            }

            DB::commit();

            return redirect(route('usuarios.index'))->with('success','Usuario creado exitosamente');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

    }

    public function edit(User $usuario) {
        return view('admin.usuarios.edit', [
            'usuario' => $usuario,
            'roles' => Role::all(),
            'permissions'   => Permission::where('id', '>', 1)->get(),
        ]);
    }

    public function update(User $usuario, Request $request) {
        //return $request;
        $request->validate(
            [
                'nombre'    => 'required',
                'usuario'  => ['required', Rule::unique('users', 'username')->ignore($usuario->id),],
                'clave'  => [Password::min(8), 'nullable', 'confirmed'],
                'rol'  => ['required', 'exists:roles,id'],
            ],
            [
                'usuario.required' => 'El usuario es requerido',
                'usuario.unique'   => 'Este nombre de usuario ya está en uso',
            ]
        );

        //return $request;
        try {
            DB::beginTransaction();
            
            $usuario->name = $request->nombre;
            $usuario->username = $request->usuario;
            if ($request->clave != NULL) {
                $usuario->password = Hash::make($request->clave);
            }
            $usuario->save();

            $usuario->roles()->sync($request->rol);

            if ($request->rol == 3) {
                $usuario->syncPermissions($request->permisos);
            } else {
                $usuario->revokePermissionTo($usuario->getPermissionNames()->toArray());
            }

            DB::commit();

            return redirect(route('usuarios.index'))->with('success','Usuario editado exitosamente');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

    }

    public function destroy(User $usuario) {
        try {
            DB::beginTransaction();
            $usuario->delete();
            DB::commit();

            return redirect(route('usuarios.index'))->with('success','Usuario eliminado correctamente');

        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect(route('usuarios.index'))->with('success', $th->getMessage());
        }
    }

    public function status(User $usuario) {
        try {
            DB::beginTransaction();

            if ($usuario->status != 1) {
                $usuario->status = 1;
                $message = 'Usuario activado correctamente';
            } else {
                $usuario->status = 0;
                $message = 'Usuario desactivado correctamente';
            }

            $usuario->save();
            
            DB::commit();

            return redirect(route('usuarios.index'))->with('success',$message);

        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect(route('usuarios.index'))->with('success', $th->getMessage());
        }
    }
    
    public function perfilUpdate(Request $request) {
        $validated = $request->validate(
            [
                'nombre'    => 'required',
                'username'  => 'required|unique:users,username,'.Auth::user()->id,
                'clave'     => 'current_password|required',
                'password'  => [Password::min(8), 'nullable'],
            ],
            [
                'username.required' => 'El usuario es requerido',
                'username.unique'   => 'Este nombre de usuario ya está en uso',
            ]
        );

        try {
            DB::beginTransaction();
            
            $user = Auth::user();

            $clave = ($request->password != null) ? Hash::make($request->password) : $user->password;
            
            $user->forceFill([
                'name' => $request->nombre,
                'username' => $request->username,
                'password' => $clave,
            ])->save();

            if (isset($request->image)) {
                Storage::disk('public')->delete('img/users/'.$user->profile_photo_path);

                $name = $request->username.date('dmYGis').'.png';

                $path = $request->file('image')->storeAs('img/users', $name, 'public');

                $user->profile_photo_path = $name;
                $user->save();
            }

            DB::commit();

            return redirect(route('perfil'));
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

    }
}
