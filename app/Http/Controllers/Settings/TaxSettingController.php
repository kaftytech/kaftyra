<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TaxSetting;

class TaxSettingController extends Controller
{
    public function index()
    {
        $taxes = TaxSetting::paginate(10);
        return view('settings.tax.index', compact('taxes'));
    }

    public function edit($id)
    {
        $tax = TaxSetting::findOrFail($id);
        $taxes = TaxSetting::paginate(10);
        return view('settings.tax.index', compact('taxes', 'tax'));
    }

    public function store(Request $request)
    {
        $input = $request->except('_token');
        TaxSetting::create($input);
        return redirect()->route('tax.index')->with('success', 'Tax created successfully.');
    }

    public function update(Request $request, $id)
    {
        $input = $request->except('_token');
        $input['is_active'] = $request->has('is_active');
        $tax = TaxSetting::findOrFail($id);
        $tax->update($input);
        return redirect()->route('tax.index')->with('success', 'Tax updated successfully.');
    }
}
