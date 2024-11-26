<?php

namespace App\Http\Controllers\ModuleMaster;

use Illuminate\Routing\Controller;

use App\Module\SaveFilter;
use Illuminate\Http\Request;

class SaveFilterController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $request = $request->all();
        $save_filter = SaveFilter::create($request);
        return redirect()->back()->with('success', 'Filter has been created.');
    }

    public function update(Request $request)
    {
        $save_filter = SaveFilter::where('id', $request->id)->update(['name' => $request->name]);
        return redirect()->back()->with('success', 'Filter has been updated.');
    }

    public function destroy(Request $request)
    {
        $save_filter = SaveFilter::find($request->id);
        $save_filter->delete();
        return redirect()->back()->with('success', 'Filter has been deleted.');
    }
}
