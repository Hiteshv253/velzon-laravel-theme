<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Auth;
use App\Models\User;
use App\Models\Role;
use App\Models\Employee\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\RolesLog;
use Yajra\DataTables\Facades\Datatables;
use App\Helpers\Helper;

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
                                $btn = '<a type="button" href="/users/edit/' . $row->id . '")" class="btn btn-soft-info btn-icon waves-effect waves-light layout-rightside-btn">
                                <i class="ri-edit-box-line"></i>
                            </a>';
                                $btn .= ' <button type="button" class="btn btn-soft-info btn-icon waves-effect waves-light layout-rightside-btn">
                                <i class="ri-eye-line"></i>
                            </button>';
                                $btn .= ' <button type="button" class="btn btn-soft-info btn-icon waves-effect waves-light layout-rightside-btn">
                                <i class="ri-delete-bin-line"></i>
                            </button>';
//                                $btn .= '<button class="btn btn-sm btn-icon view-record" data-id="325"><i class="bx bx-view"></i></button>';
//                                $btn .= '<button class="btn btn-sm btn-success edit-item-btn" data-bs-toggle="modal" data-bs-target="#showModal">Edit</button>';
//                                $btn .= '<button class="btn btn-sm btn-danger remove-item-btn" data-bs-toggle="modal" data-bs-target="#deleteRecordModal">Remove</button>';
                                return $btn;
                            })
                            ->rawColumns(['action'])
                            ->make(true);
        }
        return view('users.index');
    }

    public function index_vue() {
        $users = User::get();
        return response()->json($users);
    }

    public function index() {
//        $users = User::where('is_active', '=', 0)->get();
        $users = User::get();

        $breadcrumbs = [
            ['link' => "/", 'name' => "User Management"],
            ['link' => "/users/", 'name' => "User Management"],
            ['link' => "/users/", 'name' => "list"],
        ];
        return view('users.index', [
            'breadcrumbs' => $breadcrumbs,
            'users' => $users
        ]);
    }

    public function create() {
        $roles = Role::all();

        $breadcrumbs = [
            ['link' => "/", 'name' => "User Management"],
            ['link' => "/users/", 'name' => "User Management"],
            ['link' => "/users/create/", 'name' => "create"],
        ];
        return view('users.new', [
            'breadcrumbs' => $breadcrumbs,
            'roles' => $roles
        ]);
    }

    public function store(Request $request) {
        dd($request->all());

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
            ['link' => "/", 'name' => "User Management"],
            ['link' => "/users/", 'name' => "User Management"],
            ['link' => "/users/edit/$id", 'name' => "Edit"],
        ];
        return view('users.edit', [
            'breadcrumbs' => $breadcrumbs,
            'user' => $user,
            'roles' => $roles
        ]);
    }

    public function update(Request $request, $id) {
//        dd($request->all(), $id);
//        DB::beginTransaction();
        $this->validate($request, [
//            'name' => 'bail|required|min:2',
//            'email' => 'required|email|unique:users,email,' . $id,
            'roles' => 'required|min:1'
        ]);
        $user = User::findOrFail($id);
        $user->is_active = $request->is_active;
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
//        DB::commit();

        return redirect()->route('users.index')->with('success', 'User has been update successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($delete_id) {
        DB::beginTransaction();
        DB::table('users')->where('id', '=', $delete_id)->update(['is_active' => '1']);
        DB::commit();
        return redirect()->route('users.index')->with('success', 'User has been Deleted successfully');
    }

    public function multi_delete(Request $request) {
//        dd($request->all());
        DB::beginTransaction();
        $flag = false;
        $Status_ids = $request->input('ids');
        $mass_customer_data = User::whereIn('id', $Status_ids)->get();
//        print_r($mass_customer_data);
//        die();
        foreach ($mass_customer_data as $key => $customer_val) {
            DB::table('users')
                    ->where('id', '=', $customer_val->id)
                    ->update([
                        'is_active' => '1']
            );
        }

//        foreach ($mass_customer_data as $role) {
//            $param = [
//                'user_name' => $user->name,
//                'role' => $role,
//                'resion' => RolesLog::USERREMOVE
//            ];
//            $helper = new Helper();
//            $helper->removeUserRole($param);
//        }
//        DB::table('api_expenses')->where('is_user', $id)->delete();
//        DB::table('employees')->where('user_id', $id)->delete();
//        $user = User::find($id);
//        $user->delete();
        DB::commit();
        return redirect()->route('users.index')->with('success', 'User has been Deleted successfully');
    }

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
