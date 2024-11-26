<?php

namespace App\Http\Controllers\ModuleMaster;

use Illuminate\Routing\Controller;

use App\Module\Employee;
use App\Module\Module;
use App\Module\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function teamStore(Request $request)
    {
        $team_id = Team::insertGetId([]);
        $team = Team::find($team_id);

        $employees = Employee::find($request->employees);
        $team->employees()->attach($employees);

        $fields = Module::where('name', 'teams')->select('form')->get();

        foreach ($fields as $field) {
            foreach (json_decode($field->form) as $item) {
                if ($item->type !== 'section') {
                    $f = str_replace(' ', '_', $item->label);

                    if (is_array($request->$f) === true) {

                        $array_value = implode(',', $request->$f);
                        Team::where('id', $team_id)->update([
                            $f => $array_value
                        ]);
                    } else {

                        if ($item->type === 'file') {
                            if ($request->hasFile($f)) {
                                $file_path = $this->uploadFile($request->$f);
                            } else {
                                $file_path = '';
                            }

                            Team::where('id', $team_id)->update([
                                $f => $file_path
                            ]);
                        } else {

                            Team::where('id', $team_id)->update([

                                $f => $request->$f
                            ]);
                        }
                    }
                }
            }
        }
        return redirect(route('modules.module', 'teams'))->with('message', 'Record Inserted!');
    }

    public function teamUpdate(Request $request)
    {
        $team = Team::find($request->id);
        $employees = Employee::find($request->employees);
        $team->employees()->sync($employees);

        $fields = Module::where('name', 'teams')->select('form')->get();

        foreach ($fields as $field) {
            foreach (json_decode($field->form) as $item) {
                if ($item->type !== 'section') {
                    $f = str_replace(' ', '_', $item->label);

                    if (is_array($request->$f) === true) {

                        $array_value = implode(',', $request->$f);
                        Team::where('id', $request->id)->update([
                            $f => $array_value
                        ]);
                    } else {

                        if ($item->type === 'file') {
                            if ($request->hasFile($f)) {
                                $file_path = $this->uploadFile($request->$f);
                            } else {
                                $file_path = '';
                            }

                            Team::where('id', $request->id)->update([
                                $f => $file_path
                            ]);
                        } else {

                            Team::where('id', $request->id)->update([

                                $f => $request->$f
                            ]);
                        }
                    }
                }
            }
        }
        return redirect(route('modules.module', 'teams'))->with('message', 'Record Updated!');
    }

    public function uploadFile($file)
    {
        $fileName = time() . '_' . $file->getClientOriginalName();

        $file->move(public_path('uploads'), $fileName);
        $file_path = url('uploads', $fileName);
        return $file_path;
    }
}
