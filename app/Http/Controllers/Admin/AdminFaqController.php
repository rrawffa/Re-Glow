<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class AdminFaqController extends Controller
{
    public function index() {
        $faqs = Faq::all();
        return view('admin.faq.index', compact('faqs'));
    }

    public function create() {
        return view('admin.faq.create');
    }

    public function store(Request $request) {
        $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
        ]);

        Faq::create($request->only(['question', 'answer']));

        return redirect()->route('admin.faq.index')->with('success', 'FAQ added successfully!');
    }

    public function edit($id) {
        $faq = Faq::findOrFail($id);
        return view('admin.faq.edit', compact('faq'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
        ]);

        Faq::findOrFail($id)->update($request->only(['question', 'answer']));

        return redirect()->route('admin.faq.index')->with('success', 'FAQ updated successfully!');
    }

    public function destroy($id) {
        Faq::findOrFail($id)->delete();
        return redirect()->route('admin.faq.index')->with('success', 'FAQ deleted!');
    }
}
