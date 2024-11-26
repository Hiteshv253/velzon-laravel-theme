<?php

namespace App\Http\Controllers\ModuleMaster;

use Illuminate\Routing\Controller;

use App\Module\Employee;
use App\Module\Module;
use App\Module\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function employeeStore(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'unique:users,email,' . $request->id
        ]);

        $user = User::insertGetId([
            'name' => $request->Name,
            'email' => $request->email,
            'password' => bcrypt($request->email),
            'reset_password' => 0
        ]);
        $table_id = Employee::insertGetId([]);
        Employee::where('id', $table_id)->update([
            'user_id' => $user,
            'email' => $request->email
        ]);

        $fields = Module::where('name', 'employees')->select('form')->get();

        foreach ($fields as $field) {
            foreach (json_decode($field->form) as $item) {
                if ($item->type !== 'section') {
                    $f = str_replace(' ', '_', $item->label);

                    if (is_array($request->$f) === true) {

                        $array_value = implode(',', $request->$f);
                        Employee::where('id', $table_id)->update([
                            $f => $array_value
                        ]);
                    } else {

                        if ($item->type === 'file') {
                            if ($request->hasFile($f)) {
                                $file_path = $this->uploadFile($request->$f);
                            } else {
                                $file_path = '';
                            }

                            Employee::where('id', $table_id)->update([
                                $f => $file_path
                            ]);
                        } else {

                            Employee::where('id', $table_id)->update([

                                $f => $request->$f
                            ]);
                        }
                    }
                }
            }
        }

        return redirect(route('modules.module', 'employees'))->with('message', 'Record Inserted!');
    }

    public function employeeUpdate(Request $request)
    {
        Employee::where('id', $request->id)->update([
            'email' => $request->email
        ]);

        $fields = Module::where('name', 'employees')->select('form')->get();

        foreach ($fields as $field) {
            foreach (json_decode($field->form) as $item) {
                if ($item->type !== 'section') {
                    $f = str_replace(' ', '_', $item->label);

                    if (is_array($request->$f) === true) {

                        $array_value = implode(',', $request->$f);
                        Employee::where('id', $request->id)->update([
                            $f => $array_value
                        ]);
                    } else {

                        if ($item->type === 'file') {
                            if ($request->hasFile($f)) {
                                $file_path = $this->uploadFile($request->$f);
                            } else {
                                $file_path = '';
                            }

                            Employee::where('id', $request->id)->update([
                                $f => $file_path
                            ]);
                        } else {

                            Employee::where('id', $request->id)->update([

                                $f => $request->$f
                            ]);
                        }
                    }
                }
            }
        }

        return redirect(route('modules.module', 'employees'))->with('message', 'Record Updated!');
    }

    public function uploadFile($file)
    {
        $fileName = time() . '_' . $file->getClientOriginalName();

        $file->move(public_path('uploads'), $fileName);
        $file_path = url('uploads', $fileName);
        return $file_path;
    }
}
