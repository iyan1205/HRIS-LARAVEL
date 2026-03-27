<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quotes;

class QuotesController extends Controller
{
    function index()
    {
        $quotes = Quotes::all();
        return view('quotes.index', compact('quotes'));
    }

    function create_function()
    {
        return view('quotes.create');
    }

    function store(Request $request)
    {
        $request->validate([
            'author' => 'required',
            'quote' => 'required',
        ]);

        Quotes::create([
            'author' => $request->author,
            'quote' => $request->quote,
            'status' => true,
        ]);

        return redirect()->back()->with('successAdd', 'Quote added successfully!');
    }   

    function edit($id)
    {
        $quote = Quotes::findOrFail($id);
        return view('quotes.edit', compact('quote'));
    }

    function update(Request $request, $id)
    {
        $request->validate([
            'author' => 'required',
            'quote' => 'required',
        ]);

        $quote = Quotes::findOrFail($id);
        $quote->update([
            'author' => $request->author,
            'quote' => $request->quote,
            'status' => true,
        ]);
        return redirect()->back()->with('successAdd', 'Quote updated successfully!');
    }   

    public function toggle($id)
    {
        $quote = Quotes::findOrFail($id);

        $quote->status = !$quote->status;
        $quote->save();

        return redirect()->back()->with('successAdd', 'Status berhasil diubah');
    }

    function delete($id)
    {
        $quote = Quotes::findOrFail($id);
        $quote->delete();

        return redirect()->back()->with('successAdd', 'Quote deleted successfully!');
    }
}
