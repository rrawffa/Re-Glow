<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::orderBy('id', 'DESC')->get();
        return view('faq.index', compact('faqs'));
    }
    public function userIndex()
    {
    $faqs = Faq::orderBy('id', 'DESC')->get();
    return view('faq.user', compact('faqs')); // blade khusus user
    }

    public function create()
    {
        return view('faq.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer'   => 'required|string',
        ]);

        Faq::create($request->only(['question', 'answer']));

        return redirect()->route('faq.index')->with('success', 'FAQ added!');
    }

    public function edit($id)
    {
        $faq = Faq::findOrFail($id);
        return view('faq.edit', compact('faq'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer'   => 'required|string',
        ]);

        $faq = Faq::findOrFail($id);
        $faq->update($request->only(['question', 'answer']));

        return redirect()->route('faq.index')->with('success', 'FAQ updated!');
    }

    public function destroy($id)
    {
        Faq::destroy($id);

        return redirect()->route('faq.index')->with('success', 'FAQ deleted!');
    }
}
