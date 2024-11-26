<?php

namespace App\Http\Controllers\ModuleMaster;

use Illuminate\Routing\Controller;

use App\Module\Employee;
use App\Module\Module;
use App\Module\SaveFilter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModuleCRUDController extends Controller
{
    public function __construct(Request $request)
    {
//        $name = str_replace('_', ' ', $request->name);
//        $this->middleware('permission:Access ' . $name, ['only' => ['show', 'index']]);
//        $this->middleware('permission:Create ' . $name, ['only' => ['create']]);
//        $this->middleware('permission:Edit ' . $name, ['only' => ['edit']]);
    }

    public function index(Request $request)
    {
        dd($request->all());
        $table = str_replace(' ', '_', $request->name);
        $columns = $this->getTableColumnsNames($table);
        $columns = array_diff($columns, ["created_at", "updated_at"]);
        $filter_columns = Module::where('name', $request->name)->select('form')->get();
        $save_filters = SaveFilter::where('module_name', $table)->get();

        if (request()->has('filter')) {
            $data = DB::table($table);
            $all = request()->all();
            foreach ($all as $one) {
                if ($one !== 'filter') {
                    $column_sw = substr($one, strpos($one, "_") + 1);
                    $column = str_replace(' ', '_', $column_sw);
                    if ($this->startsWith($one, 'contains_') === true) {
                        $data->where($column, 'LIKE', '%' . request()->$column . '%');
                    } elseif ($this->startsWith($one, 'is_') === true) {
                        $data->where($column, request()->$column);
                    } elseif ($this->startsWith($one, 'isNot_') === true) {
                        $data->where($column, '!=', request()->$column);
                    } elseif ($this->startsWith($one, 'notContains_') === true) {
                        $data->orWhere($column, 'NOT LIKE', '%' . request()->$column . '%');
                    } elseif ($this->startsWith($one, 'isEmpty_') === true) {
                        $data->whereNull($column);
                    } elseif ($this->startsWith($one, 'startWith_') === true) {
                        $data->where($column, 'LIKE', request()->$column . '%');
                    } elseif ($this->startsWith($one, 'endWith_') === true) {
                        $data->where($column, 'LIKE', '%' . request()->$column);
                    } elseif ($this->startsWith($one, 'isNotEmpty_') === true) {
                        $data->whereNotNull($column);
                    } elseif ($this->startsWith($one, 'inTheLast_') === true) {
                        foreach ($all as $time_one_last) {
                            if ($this->startsWith($time_one_last, 'days_') === true) {
                                $data->whereDate($column, '>=', Carbon::now()->subDays(request()->$column));
                            } elseif ($this->startsWith($time_one_last, 'weeks_') === true) {
                                $data->whereDate($column, '>=', Carbon::now()->subWeeks(request()->$column));
                            } elseif ($this->startsWith($time_one_last, 'months_') === true) {
                                $data->whereDate($column, '>=', Carbon::now()->subMonths(request()->$column));
                            }
                        }
                    } elseif ($this->startsWith($one, 'dueIn_') === true) {
                        foreach ($all as $time_one_due) {
                            if ($this->startsWith($time_one_due, 'days_') === true) {
                                $data->whereDate($column, '>', Carbon::now()->subDays(request()->$column));
                            } elseif ($this->startsWith($time_one_due, 'weeks_') === true) {
                                $data->whereDate($column, '>', Carbon::now()->subWeeks(request()->$column));
                            } elseif ($this->startsWith($time_one_due, 'months_') === true) {
                                $data->whereDate($column, '>', Carbon::now()->subMonths(request()->$column));
                            }
                        }
                    } elseif ($this->startsWith($one, 'on_') === true) {
                        $req_value = 'generalDate_' . $column;
                        $data->where($column, request()->$req_value);
                    } elseif ($this->startsWith($one, 'before_') === true) {
                        $req_value = 'generalDate_' . $column;
                        $data->where($column, '<', request()->$req_value);
                    } elseif ($this->startsWith($one, 'after_') === true) {
                        $req_value = 'generalDate_' . $column;
                        $data->where($column, '>', request()->$req_value);
                    } elseif ($this->startsWith($one, 'between_') === true) {
                        $from_date = 'betweenStart_' . $column;
                        $to_date = 'betweenEnd_' . $column;
                        $from = request()->$from_date;
                        $to = request()->$to_date;
                        $data->whereBetween($column, [$from, $to]);
                    } elseif ($this->startsWith($one, 'today_') === true) {
                        $data->whereDate($column, '=', Carbon::today());
                    } elseif ($this->startsWith($one, 'yesterday_') === true) {
                        $data->whereDate($column, '=', Carbon::now()->addDay(-1));
                    } elseif ($this->startsWith($one, 'thisWeek_') === true) {
                        $data->where($column, [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    } elseif ($this->startsWith($one, 'thisMonth_') === true) {
                        $data->whereMonth($column, Carbon::now()->month)
                            ->whereYear($column, Carbon::now()->year);
                    } elseif ($this->startsWith($one, 'thisYear_') === true) {
                        $data->whereYear($column, Carbon::now()->year);
                    } elseif ($this->startsWith($one, 'lastWeek_') === true) {
                        $start = Carbon::now()->subWeek()->startOfWeek();
                        $end = Carbon::now()->subWeek()->endOfWeek();
                        $data->whereBetween($column, [$start, $end]);
                    } elseif ($this->startsWith($one, 'lastMonth_') === true) {
                        $start = Carbon::now()->subMonth()->startOfMonth();
                        $end = Carbon::now()->subMonth()->endOfMonth();
                        $data->whereBetween($column, [$start, $end]);
                    } elseif ($this->startsWith($one, 'dateIsEmpty_') === true) {
                        $data->whereNull($column);
                    } elseif ($this->startsWith($one, 'dateIsNotEmpty_') === true) {
                        $data->whereNotNull($column);
                    } elseif ($this->startsWith($one, 'numberEqual_') === true) {
                        $number_value = 'generalNumberValue_' . $column;
                        $data->where($column, '=', request()->$number_value);
                    } elseif ($this->startsWith($one, 'numberNotEqual_') === true) {
                        $number_value = 'generalNumberValue_' . $column;
                        $data->where($column, '!=', request()->$number_value);
                    } elseif ($this->startsWith($one, 'numberLess_') === true) {
                        $number_value = 'generalNumberValue_' . $column;
                        $data->where($column, '<', request()->$number_value);
                    } elseif ($this->startsWith($one, 'numberLessEqual_') === true) {
                        $number_value = 'generalNumberValue_' . $column;
                        $data->where($column, '<=', request()->$number_value);
                    } elseif ($this->startsWith($one, 'numberGrater_') === true) {
                        $number_value = 'generalNumberValue_' . $column;
                        $data->where($column, '>', request()->$number_value);
                    } elseif ($this->startsWith($one, 'numberGraterEqual_') === true) {
                        $number_value = 'generalNumberValue_' . $column;
                        $data->where($column, '>=', request()->$number_value);
                    } elseif ($this->startsWith($one, 'numberBetween_') === true) {
                        $start = 'numberBetweenStart_' . $column;
                        $end = 'numberBetweenEnd_' . $column;
                        $data->whereBetween($column, [request()->$start, request()->$end]);
                    } elseif ($this->startsWith($one, 'numberNotBetween_') === true) {
                        $start = 'numberBetweenStart_' . $column;
                        $end = 'numberBetweenEnd_' . $column;
                        $data->whereNotBetween($column, [request()->$start, request()->$end]);
                    } elseif ($this->startsWith($one, 'numberEmpty_') === true) {
                        $data->whereNull($column);
                    } elseif ($this->startsWith($one, 'numberNotEmpty_') === true) {
                        $data->whereNotNull($column);
                    }
                }
            }
            $rows = $data->get();
        } else {
            $rows = DB::table($table)->get();
        }

        return view('curd-module.index', compact('columns', 'table', 'rows', 'filter_columns', 'save_filters'));
    }

    public
    function getTableColumnsNames($table_name)
    {
        return \Schema::getColumnListing($table_name);
    }

    public
    function create(Request $request)
    {
        $module_name = str_replace('_', ' ', $request->name);
        $module_form = Module::where('name', $module_name)->get();
        return view('curd-module.create', compact('module_name', 'module_form'));
    }

    public
    function store(Request $request)
    {
        $table_id = DB::table($request->table_name)->insertGetId([]);
        $module_name = str_replace('_', ' ', $request->table_name);
        $fields = Module::where('name', $module_name)->select('form')->get();

        foreach ($fields as $field) {
            foreach (json_decode($field->form) as $item) {
                if ($item->type !== 'section') {

                    $f = str_replace(' ', '_', $item->label);
                    if (is_array($request->$f) === true) {
                        $array_value = implode(',', $request->$f);
                        DB::table($request->table_name)->where('id', $table_id)->update([
                            $f => $array_value
                        ]);
                    } else {

                        if ($item->type === 'file') {
                            if ($request->hasFile($f)) {
                                $file_path = $this->uploadFile($request->$f);
                            } else {
                                $file_path = '';
                            }

                            DB::table($request->table_name)->where('id', $table_id)->update([
                                $f => $file_path
                            ]);
                        } else {

                            DB::table($request->table_name)->where('id', $table_id)->update([

                                $f => $request->$f
                            ]);
                        }
                    }
                }
            }
        }

        return redirect(route('modules.module', $request->table_name))->with('success', 'Record Inserted!');
    }

    public
    function show(Request $request)
    {
        $table_data = DB::table($request->name)->where('id', $request->id)->first();
        $table_name = str_replace('_', ' ', $request->name);
        $module_name = str_replace('_', ' ', $request->name);
        $fields = Module::where('name', $module_name)->select('form')->get();

        return view('curd-module.show', compact('table_data', 'fields', 'table_name'));

    }

    public
    function edit(Request $request)
    {
        $table_data = DB::table($request->name)->where('id', $request->id)->first();
        $table_name = $request->name;
        $module_name = str_replace('_', ' ', $request->name);
        $module_form = Module::where('name', $module_name)->get();
        return view('curd-module.edit', compact('table_data', 'table_name', 'module_form'));
    }

    public
    function destroy(Request $request)
    {

        if ($request->name === 'employees') {
            $employee = Employee::find($request->id);
            $employee->user()->delete();
        }
        $table = DB::table($request->name)->where('id', $request->id)->delete();

        return response()->json(['message' => 'Given record has been removed!'], 200);
    }

    public
    function uploadFile($file)
    {
        $fileName = time() . '_' . $file->getClientOriginalName();

        $file->move(public_path('uploads'), $fileName);
        $file_path = url('uploads', $fileName);
        return $file_path;
    }

    public
    function massDestroy(Request $request)
    {
        if ($request->table === 'employees') {
            foreach ($request->ids as $id) {
                $employee = Employee::find($id);
                $employee->user()->delete();
            }
        }

        DB::table($request->table)->whereIn('id', $request->ids)->delete();

        return response()->json(['message' => 'Given records has been removed!'], 200);
    }

    public
    function update(Request $request)
    {
        $module_name = str_replace('_', ' ', $request->table_name);
        $fields = Module::where('name', $module_name)->select('form')->get();

        foreach ($fields as $field) {
            foreach (json_decode($field->form) as $item) {
                if ($item->type !== 'section') {

                    $f = str_replace(' ', '_', $item->label);
                    if (is_array($request->$f) === true) {
                        $array_value = implode(',', $request->$f);
                        DB::table($request->table_name)->where('id', $request->id)->update([
                            $f => $array_value
                        ]);
                    } else {

                        if ($item->type === 'file') {
                            if ($request->hasFile($f)) {
                                $file_path = $this->uploadFile($request->$f);
                            } else {
                                $file_path = '';
                            }
                            DB::table($request->table_name)->where('id', $request->id)->update([
                                $f => $file_path
                            ]);
                        } else {

                            DB::table($request->table_name)->where('id', $request->id)->update([

                                $f => $request->$f
                            ]);
                        }
                    }
                }
            }
        }

        return redirect(route('modules.module', $request->table_name))->with('success', 'Record updated!');

    }

    public
    function startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }
}
