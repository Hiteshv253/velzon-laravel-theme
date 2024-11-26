<?php

namespace App\Http\Controllers\ModuleMaster;

use Illuminate\Routing\Controller;
use App\Models\CrudModel\Module;
use App\Models\CrudModel\Permission;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Str;

class ModuleController extends Controller {

    public function __construct() {
//        $this->middleware('permission:Access Module', ['only' => ['show', 'index']]);
//        $this->middleware('permission:Create Module', ['only' => ['create']]);
//        $this->middleware('permission:Edit Module', ['only' => ['edit']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
        $modules = Module::all();

        $breadcrumbs = [
            ['link' => "/", 'name' => "CRUD Management"],
            ['link' => "/users/", 'name' => "CRUD Management"],
            ['link' => "/users/", 'name' => "list"],
        ];
//        dd($modules->all())
        return view('curd-module.all', [
            'breadcrumbs' => $breadcrumbs,
            'modules' => $modules
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $modules = Module::select('name')->get();
        return view('curd-module.formbuilder', [
            'modules' => $modules
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request) {
//        dd($request->all());
        if (!empty($request->form_data)) {
            $table = $this->createTable($request->name, $request->form_data);
            if ($table->status() === 400) {
                return response()->json(['message' => 'Module exist with same name'], 400);
            }
        }
        $module = Module::create([
                    'name' => $request->name,
                    'form' => $request->form_data,
                    'is_active' => $request->is_active
        ]);

//        return redirect()->route('module.index')->with('success', 'New Module has been successfully Created');
//        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
//        $access = Permission::create(['name' => 'Access ' . $request->name]);
//        $read = Permission::create(['name' => 'Read ' . $request->name]);
//        $create = Permission::create(['name' => 'Create ' . $request->name]);
//        $edit = Permission::create(['name' => 'Edit ' . $request->name]);
//        $delete = Permission::create(['name' => 'Delete ' . $request->name]);
//
//        $role = Role::findByName('admin');
//        $role->givePermissionTo([$create, $edit, $read, $delete, $access]);

        return response()->json(['message' => 'Created successfully'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Module $module
     * @return \Illuminate\Http\Response
     */
    public function show(Module $module) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Module $module
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Module $module) {
        $module_ori = $module;
        return view('curd-module.editformbuilder', [
            'module_ori' => $module_ori
        ]);
//        return view('curd-module.editformbuilder', compact('module_ori'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Module $module
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request) {
        $module_name = str_replace(' ', '_', $request->name);
        $table = $this->updateTable($request->name, $request->form_data);
        $module = Module::where('name', $module_name)->first();
        $module->update([
            'name' => $request->name,
            'form' => $request->form_data
        ]);

        return response()->json(['message' => 'Given module has been updated!'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Module $module
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Module $module) {
        
        $table = str_replace(' ', '_', $module->name);
        $remove_table = $this->removeTable($table);
        $module->delete();

        return response()->json(['message' => 'Given module has been removed!'], 200);
    }

    /**
     * Create dynamic table along with dynamic fields
     *
     * @param       $table_name
     * @param array $fields
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function createTable($table_name, $fields) {
        $table_name = str_replace(' ', '_', $table_name);

        // check if table is not already exists
        if (!Schema::hasTable($table_name)) {

            Schema::create($table_name, function (Blueprint $table) use ($fields, $table_name) {

                $table->increments('id');

                foreach (json_decode($fields) as $field) {
                    $column_replace_space_underscores = str_replace(' ', '_', $field->label);
                    if ($field->type === 'date') {
                        $table->date($column_replace_space_underscores)->nullable();
                    } elseif ($field->type === 'header' || $field->type === 'paragraph' || $field->type === 'section') {
                        
                    } elseif ($field->type === 'multiLookup') {
                        $table->string($column_replace_space_underscores)->nullable();

                        $lookup_table = str_replace(' ', '_', $field->module);
                        $lookup_form = DB::table('modules')->where('name', $field->module)->select('form', 'name')->first();

                        if (isset($field->fieldName)) {
                            $field_name = str_replace(' ', '_', $field->fieldName);
                        } else {
                            $field_name = $column_replace_space_underscores;
                        }

                        $convert_form_array = json_decode($lookup_form->form, true);
                        $convert_form_array[] = array(
                            'type' => 'multiLookup',
                            'label' => str_replace(' ', '_', $field_name),
                            'module' => str_replace('_', ' ', $table_name),
                            'fieldName' => ''
                        );

                        $convert_form_json = json_encode($convert_form_array);

                        $update_lookup_form = DB::table('modules')
                                ->where('name', $field->module)
                                ->update(['form' => $convert_form_json]);

                        Schema::table($lookup_table, function (Blueprint $table) use ($field_name) {
                            $table->string($field_name)->nullable();
                        });
                    } elseif ($field->type === 'number') {
                        $table->bigInteger($column_replace_space_underscores)->nullable();
                    } else {

                        $table->string($column_replace_space_underscores)->nullable();
                    }
                }

                $table->timestamps();
            });
            return response()->json(['message' => 'Given table has been created!'], 200);
        }
        return response()->json(['message' => 'Given table is already exists.'], 400);
    }

    /**
     * To delete the tabel from the database
     *
     * @param $table_name
     *
     * @return bool
     */
    public function removeTable($table_name) {

        Permission::where('name', 'Access ' . $table_name)->delete();
        Permission::where('name', 'Read ' . $table_name)->delete();
        Permission::where('name', 'Create ' . $table_name)->delete();
        Permission::where('name', 'Edit ' . $table_name)->delete();
        Permission::where('name', 'Delete ' . $table_name)->delete();

        Schema::dropIfExists($table_name);

        return true;
    }

    public function updateTable($table_name, $new_fields) {
        $table_name = str_replace(' ', '_', $table_name);
        $existing_fields = Schema::getColumnListing($table_name);

        if (Schema::hasTable($table_name)) {
            Schema::table($table_name, function (Blueprint $table) use ($new_fields, $existing_fields, $table_name) {
                $array = [];

                foreach (json_decode($new_fields) as $new_field) {

                    $column_replace_space_underscores = str_replace(' ', '_', $new_field->label);
                    array_push($array, $column_replace_space_underscores);

                    if (!in_array($column_replace_space_underscores, $existing_fields)) {
                        if ($new_field->type === 'date') {
                            $table->date($column_replace_space_underscores)->nullable();
                        } elseif ($new_field->type === 'header' || $new_field->type === 'paragraph' || $new_field->type === 'section') {
                            
                        } elseif ($new_field->type === 'multiLookup') {
                            $table->string($column_replace_space_underscores)->nullable();

                            $lookup_table = str_replace(' ', '_', $new_field->module);
                            $lookup_form = DB::table('modules')->where('name', $new_field->module)->select('form', 'name')->first();

                            if (isset($new_field->fieldName)) {
                                $field_name = str_replace(' ', '_', $new_field->fieldName);
                            } else {
                                $field_name = $column_replace_space_underscores;
                            }

                            $convert_form_array = json_decode($lookup_form->form, true);
                            $convert_form_array[] = array(
                                'type' => 'multiLookup',
                                'label' => str_replace(' ', '_', $field_name),
                                'module' => str_replace('_', ' ', $table_name),
                                'fieldName' => ''
                            );

                            $convert_form_json = json_encode($convert_form_array);

                            $update_lookup_form = DB::table('modules')
                                    ->where('name', $new_field->module)
                                    ->update(['form' => $convert_form_json]);

                            Schema::table($lookup_table, function (Blueprint $table) use ($field_name) {
                                $table->string($field_name)->nullable();
                            });
                        } else {
                            $table->string($column_replace_space_underscores)->nullable();
                        }
                    }
                }

                foreach ($existing_fields as $ex_field) {
                    if ($ex_field !== 'id') {
                        if ($ex_field !== 'created_at') {
                            if ($ex_field !== 'updated_at') {
                                if ($table_name === 'employees') {
                                    if ($ex_field !== 'user_id') {
                                        if ($ex_field !== 'email') {
                                            if (!in_array($ex_field, $array)) {
                                                $table->dropColumn($ex_field);
                                            }
                                        }
                                    }
                                } elseif (!in_array($ex_field, $array)) {
                                    $table->dropColumn($ex_field);
                                }
                            }
                        }
                    }
                }
            });
            return response()->json(['message' => 'Given table has been updated!'], 200);
        } else {
            return response()->json(['message' => 'Given table is not exists.'], 400);
        }
    }
}
