<?php

namespace App\Http\Controllers\Admin;

use App\Interfaces\SettingsInterface;
use App\Models\Settings;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SettingsController extends Controller
{
    public function __construct(SettingsInterface $settingsRepository)
    {
        $this->settingsRepository = $settingsRepository;
    }

    public function index(Request $request)
    {
        if (!empty($request->term)) {
            $data = $this->settingsRepository->getSearchSettings($request->term);
        } else {
            $data = $this->settingsRepository->listAll();
        }
        return view('admin.settings.index', compact('data'));
    }

    public function show(Request $request, $id)
    {
        $data = $this->settingsRepository->listById($id);
        return view('admin.settings.detail', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            "content" => "required|string"
        ]);

        $params = $request->except('_token');
        $storeData = $this->settingsRepository->update($id, $params);

        if ($storeData) {
            return redirect()->route('admin.settings.index');
        } else {
            return redirect()->route('admin.settings.create')->withInput($request->all());
        }
    }
    public function bulkDestroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bulk_action' => 'required',
            'delete_check' => 'required|array',
        ], [
            'delete_check.*' => 'Please select at least one item'
        ]);

        if (!$validator->fails()) {
            if ($request['bulk_action'] == 'delete') {
                foreach ($request->delete_check as $index => $delete_id) {
                    Settings::where('id', $delete_id)->delete();
                }

                return redirect()->route('admin.settings.index')->with('success', 'Selected items deleted');
            } else {
                return redirect()->route('admin.settings.index')->with('failure', 'Please select an action')->withInput($request->all());
            }
        } else {
            return redirect()->route('admin.settings.index')->with('failure', $validator->errors()->first())->withInput($request->all());
        }
    }


}