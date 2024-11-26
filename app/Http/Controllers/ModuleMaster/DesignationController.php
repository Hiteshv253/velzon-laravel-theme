<?php

namespace App\Http\Controllers\ModuleMaster;

use Illuminate\Routing\Controller;
use App\Models\CrudModel\Designation;
use App\Models\CrudModel\Employee;
use App\Models\CrudModel\Module;
use App\Models\CrudModel\Team;
use Illuminate\Http\Request;

class DesignationController extends Controller {

    public function designationStore(Request $request) {
        $tdesignation_id = Designation::insertGetId([]);
        $tdesignation = Designation::find($tdesignation_id);

        $employees = Employee::find($request->employees);
        $tdesignation->employees()->attach($employees);

        $fields = Module::where('name', 'designations')->select('form')->get();

        foreach ($fields as $field) {
            foreach (json_decode($field->form) as $item) {
                if ($item->type !== 'section') {
                    $f = str_replace(' ', '_', $item->label);

                    if (is_array($request->$f) === true) {

                        $array_value = implode(',', $request->$f);
                        Designation::where('id', $tdesignation_id)->update([
                            $f => $array_value
                        ]);
                    } else {

                        if ($item->type === 'file') {
                            if ($request->hasFile($f)) {
                                $file_path = $this->uploadFile($request->$f);
                            } else {
                                $file_path = '';
                            }

                            Designation::where('id', $tdesignation_id)->update([
                                $f => $file_path
                            ]);
                        } else {

                            Designation::where('id', $tdesignation_id)->update([
                                $f => $request->$f
                            ]);
                        }
                    }
                }
            }
        }
        return redirect(route('modules.module', 'designations'))->with('message', 'Record Inserted!');
    }

    public function designationUpdate(Request $request) {
        $designation = Designation::find($request->id);
        $employees = Employee::find($request->employees);
        $designation->employees()->sync($employees);

        $fields = Module::where('name', 'designations')->select('form')->get();

        foreach ($fields as $field) {
            foreach (json_decode($field->form) as $item) {
                if ($item->type !== 'section') {
                    $f = str_replace(' ', '_', $item->label);

                    if (is_array($request->$f) === true) {

                        $array_value = implode(',', $request->$f);
                        Designation::where('id', $request->id)->update([
                            $f => $array_value
                        ]);
                    } else {

                        if ($item->type === 'file') {
                            if ($request->hasFile($f)) {
                                $file_path = $this->uploadFile($request->$f);
                            } else {
                                $file_path = '';
                            }

                            Designation::where('id', $request->id)->update([
                                $f => $file_path
                            ]);
                        } else {

                            Designation::where('id', $request->id)->update([
                                $f => $request->$f
                            ]);
                        }
                    }
                }
            }
        }
        return redirect(route('modules.module', 'designations'))->with('message', 'Record Updated!');
    }

    public function uploadFile($file) {
        $fileName = time() . '_' . $file->getClientOriginalName();

        $file->move(public_path('uploads'), $fileName);
        $file_path = url('uploads', $fileName);
        return $file_path;
    }
}
