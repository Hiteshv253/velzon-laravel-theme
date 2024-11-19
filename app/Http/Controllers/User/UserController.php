<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Auth;
use App\Module;
use App\Models\User;
use App\Models\Role;
use App\Models\Employee\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\UserColors;
use Illuminate\Support\Facades\DB;
use App\RolesLog;
use Yajra\DataTables\Facades\Datatables;
use App\Helpers\Helper;
use Spatie\Permission\Models\Permission;

class UserController extends Controller {

    public function __construct() {
//        $this->middleware('permission:Access User', ['only' => ['show', 'index']]);
//        $this->middleware('permission:Create User', ['only' => ['create']]);
//        $this->middleware('permission:Edit User', ['only' => ['edit']]);
    }

    public function index__(Request $request) {

        if ($request->ajax()) {
            $data = User::select('*');
            return Datatables::of($data)
                            ->addIndexColumn()
                            ->addColumn('action', function ($row) {
                                $btn = '<button class="btn btn-sm btn-icon edit-record" data-id="325" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddUser"><i class="bx bx-edit"></i></button>';
                                $btn .= '<button class="btn btn-sm btn-icon delete-record" data-id="325"><i class="bx bx-trash"></i></button>';
                                $btn .= '<button class="btn btn-sm btn-icon view-record" data-id="325"><i class="bx bx-view"></i></button>';
                                return $btn;
                            })
                            ->rawColumns(['action'])
                            ->make(true);
        }
        return view('users.index');
    }

    public function index() {


        $users = User::all();
        $breadcrumbs = [
            ['master' => "Home", 'module_name' => "users managment", 'module_create' => "list"]
        ];
//        dd($breadcrumbs);
        return view('users.index', [
            'breadcrumbs' => $breadcrumbs,
            'users' => $users
        ]);
    }

    public function create() {
        $roles = Role::all();

        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"],
            ['link' => "/users/", 'name' => "Users"],
            ['name' => "Create"],
        ];
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true, 'isCustomizer' => true];
        return view('users.new', [
            'pageConfigs' => $pageConfigs,
            'breadcrumbs' => $breadcrumbs,
            'roles' => $roles
        ]);
    }

    public function store(Request $request) {

        $this->validate($request, [
            'name' => 'bail|required|min:2',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'roles' => 'required|min:1'
        ]);
        $request->merge(['password' => bcrypt($request->get('password'))]);
        $DataUser = User::create($request->except('roles'));
        $lastId = $DataUser->id;
        $table_id = Employee::insertGetId([]);
        Employee::where('id', $table_id)->update([
            'name' => $request->name,
            'user_id' => $lastId,
            'email' => $request->email
        ]);

        if ($user = $DataUser) {

            //$user->syncRoles($request->roles);

            return redirect()->route('users.index')->with('success', 'New User has been successfully added');
        } else {
            return redirect()->back();
        }
    }

    public function show($id) {
        
    }

    public function edit($id) {
        $user = User::find($id);
        $roles = Role::all();

        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"],
            ['link' => "/users/", 'name' => "Users"],
            ['name' => "edit"],
        ];
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true, 'isCustomizer' => true];
        return view('users.edit', [
            'pageConfigs' => $pageConfigs,
            'breadcrumbs' => $breadcrumbs,
            'user' => $user,
            'roles' => $roles
        ]);
    }

    public function update(Request $request, $id) {
        DB::beginTransaction();
        $this->validate($request, [
            // 'name' => 'bail|required|min:2',
            // 'email' => 'required|email|unique:users,email,' . $id,
            'roles' => 'required|min:1'
        ]);
        $user = User::findOrFail($id);
        if ($request->get('password')) {
            $user->password = bcrypt($request->get('password'));
        }
        $old_role = $user->getRoleNames()->toArray();
        $new_add_role_list = array_diff($request->roles, $old_role);
        $remove_role_list = array_diff($old_role, $request->roles);
        $helper = new Helper();
        foreach ($new_add_role_list as $role) {
            $param = [
                'user_name' => $user->name,
                'role' => $role,
                'resion' => RolesLog::ASSIGN
            ];
            $helper->addUserRole($param);
        }
        foreach ($remove_role_list as $role) {
            $param = [
                'user_name' => $user->name,
                'role' => $role,
                'resion' => RolesLog::UNASSIGN
            ];
            $helper->removeUserRole($param);
        }
        $user->syncRoles($request->roles);
        $user->save();
        DB::commit();
        redirect()->back()->with('success', 'User has been updated. !');
        return redirect('/users/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id) {
        // for role log by surani ajit 05-09-2024
        DB::beginTransaction();
        $user = User::find($id);
        $user_roles = $user->getRoleNames()->toArray();
        foreach ($user_roles as $role) {
            $param = [
                'user_name' => $user->name,
                'role' => $role,
                'resion' => RolesLog::USERREMOVE
            ];
            $helper = new Helper();
            $helper->removeUserRole($param);
        }

        // end role log
        /* 01-02-2024 */
        DB::table('api_expenses')->where('is_user', $id)->delete();
        DB::table('employees')->where('user_id', $id)->delete();
        /* 01-02-2024 */
        $user = User::find($id);
        $user->delete();
        DB::commit();
        return response()->json(['message' => 'Given record has been removed!'], 200);
    }
}
