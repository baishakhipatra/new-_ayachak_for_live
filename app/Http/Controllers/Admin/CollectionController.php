<?php

namespace App\Http\Controllers\Admin;

use App\Interfaces\CollectionInterface;
use App\Models\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CollectionController extends Controller
{
    // private CollectionInterface $collectionRepository;

    public function __construct(CollectionInterface $collectionRepository)
    {
        $this->collectionRepository = $collectionRepository;
    }

    public function index(Request $request)
    {
        if (!empty($request->term)) {
            $data = $this->collectionRepository->getSearchCollections($request->term);
        } else {
            $data = $this->collectionRepository->getAllCollections();
        }
        return view('admin.collection.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|regex:/^[\pL\s\-]+$/u|max:255",
            "description" => "nullable|string",
            "icon_path" => "required|mimes:jpg,jpeg,png,svg,gif|max:10000000",
            "sketch_icon" => "required|mimes:jpg,jpeg,png,svg,gif|max:10000000",
            "image_path" => "required|mimes:jpg,jpeg,png,svg,gif|max:10000000",
            "banner_image" => "required|mimes:jpg,jpeg,png,svg,gif|max:10000000"
        ]);

        $params = $request->except('_token');
        $storeData = $this->collectionRepository->createCollection($params);

        if ($storeData) {
            return redirect()->route('admin.collection.index')->with('success', 'New Collection Added!');
        } else {
            return redirect()->route('admin.collection.create')->withInput($request->all());
        }
    }

    public function show(Request $request, $id)
    {
        $data = $this->collectionRepository->getCollectionById($id);
        return view('admin.collection.detail', compact('data'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());

        $request->validate([
            "name" => "required|regex:/^[\pL\s\-]+$/u|max:255",
            "description" => "nullable|string",
            "icon_path" => "nullable|mimes:jpg,jpeg,png,svg,gif|max:10000000",
            "sketch_icon" => "nullable|mimes:jpg,jpeg,png,svg,gif|max:10000000",
            "image_path" => "nullable|mimes:jpg,jpeg,png,svg,gif|max:10000000",
            "banner_image" => "nullable|mimes:jpg,jpeg,png,svg,gif|max:10000000"
        ]);

        $params = $request->except('_token');
        $storeData = $this->collectionRepository->updateCollection($id, $params);

        if ($storeData) {
            return redirect()->route('admin.collection.index')->with('success', 'Collection updated!');
        } else {
            return redirect()->route('admin.collection.create')->withInput($request->all());
        }
    }

    public function status(Request $request, $id)
    {
        $storeData = $this->collectionRepository->toggleStatus($id);

        if ($storeData) {
            return redirect()->route('admin.collection.index');
        } else {
            return redirect()->route('admin.collection.create')->withInput($request->all());
        }
    }

    public function destroy(Request $request, $id)
    {
        $this->collectionRepository->deleteCollection($id);

        return redirect()->route('admin.collection.index');
    }
    public function bulkDestroy(Request $request)
    {
        // $request->validate([
        //     'bulk_action' => 'required',
        //     'delete_check' => 'required|array',
        // ]);

        $validator = Validator::make($request->all(), [
            'bulk_action' => 'required',
            'delete_check' => 'required|array',
        ], [
            'delete_check.*' => 'Please select at least one item'
        ]);

        if (!$validator->fails()) {
            if ($request['bulk_action'] == 'delete') {
                foreach ($request->delete_check as $index => $delete_id) {
                    Collection::where('id', $delete_id)->delete();
                }

                return redirect()->route('admin.collection.index')->with('success', 'Selected items deleted');
            } else {
                return redirect()->route('admin.collection.index')->with('failure', 'Please select an action')->withInput($request->all());
            }
        } else {
            return redirect()->route('admin.collection.index')->with('failure', $validator->errors()->first())->withInput($request->all());
        }
    }
}