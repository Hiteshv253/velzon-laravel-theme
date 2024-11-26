<?php

namespace App\Http\Controllers\ModuleMaster;

use Illuminate\Routing\Controller;
use App\Module\Department;
use App\Module\Employee;
use App\Module\Module;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function departmentStore(Request $request)
    {
        $department_id = Department::insertGetId([]);
        $department = Department::find($department_id);

        $employees = Employee::find($request->employees);
        $department->employees()->attach($employees);

        $fields = Module::where('name', 'departments')->select('form')->get();

        foreach ($fields as $field) {
            foreach (json_decode($field->form) as $item) {
                if ($item->type !== 'section') {
                    $f = str_replace(' ', '_', $item->label);

                    if (is_array($request->$f) === true) {

                        $array_value = implode(',', $request->$f);
                        Department::where('id', $department_id)->update([
                            $f => $array_value
                        ]);
                    } else {

                        if ($item->type === 'file') {
                            if ($request->hasFile($f)) {
                                $file_path = $this->uploadFile($request->$f);
                            } else {
                                $file_path = '';
                            }

                            Department::where('id', $department_id)->update([
                                $f => $file_path
                            ]);
                        } else {

                            Department::where('id', $department_id)->update([

                                $f => $request->$f
                            ]);
                        }
                    }
                }
            }
        }
        return redirect(route('modules.module', 'departments'))->with('message', 'Record Inserted!');
    }

    public function uploadFile($file)
    {
        $fileName = time() . '_' . $file->getClientOriginalName();

        $file->move(public_path('uploads'), $fileName);
        $file_path = url('uploads', $fileName);
        return $file_path;
    }

    public function departmentUpdate(Request $request)
    {
        $department = Department::find($request->id);
        $employees = Employee::find($request->employees);
        $department->employees()->sync($employees);

        $fields = Module::where('name', 'departments')->select('form')->get();

        foreach ($fields as $field) {
            foreach (json_decode($field->form) as $item) {
                if ($item->type !== 'section') {
                    $f = str_replace(' ', '_', $item->label);

                    if (is_array($request->$f) === true) {

                        $array_value = implode(',', $request->$f);
                        Department::where('id', $request->id)->update([
                            $f => $array_value
                        ]);
                    } else {

                        if ($item->type === 'file') {
                            if ($request->hasFile($f)) {
                                $file_path = $this->uploadFile($request->$f);
                            } else {
                                $file_path = '';
                            }

                            Department::where('id', $request->id)->update([
                                $f => $file_path
                            ]);
                        } else {

                            Department::where('id', $request->id)->update([

                                $f => $request->$f
                            ]);
                        }
                    }
                }
            }
        }
        return redirect(route('modules.module', 'departments'))->with('message', 'Record Updated!');
    }
}
