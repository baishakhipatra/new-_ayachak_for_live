<?php

namespace App\Http\Controllers\Admin;

use App\Interfaces\FaqInterface;
use App\Models\Faq;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class FaqController extends Controller
{
    public function __construct(FaqInterface $faqRepository) 
    {
        $this->faqRepository = $faqRepository;
    }

    public function index(Request $request) 
    {
        $data = $this->faqRepository->listAll();
        return view('admin.faq.index', compact('data'));
    }

    public function store(Request $request) 
    {
        $request->validate([
            "question" => "required|string",
            "answer" => "required|string"
        ]);

        $params = $request->except('_token');
        $storeData = $this->faqRepository->create($params);

        if ($storeData) {
            return redirect()->route('admin.faq.index');
        } else {
            return redirect()->route('admin.faq.create')->withInput($request->all());
        }
    }

    public function show(Request $request, $id)
    {
        $data = $this->faqRepository->listById($id);
        return view('admin.faq.detail', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            "question" => "required|string",
            "answer" => "required|string"
        ]);

        $params = $request->except('_token');
        $storeData = $this->faqRepository->update($id, $params);

        if ($storeData) {
            return redirect()->route('admin.faq.index');
        } else {
            return redirect()->route('admin.faq.create')->withInput($request->all());
        }
    }

    public function status(Request $request, $id)
    {
        $storeData = $this->faqRepository->toggle($id);

        if ($storeData) {
            return redirect()->route('admin.faq.index');
        } else {
            return redirect()->route('admin.faq.create')->withInput($request->all());
        }
    }

    public function destroy(Request $request, $id) 
    {
        $this->faqRepository->delete($id);

        return redirect()->route('admin.faq.index');
    }
}
