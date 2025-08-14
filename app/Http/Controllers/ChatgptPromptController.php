<?php

namespace App\Http\Controllers;

use App\Models\ChatgptPrompt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChatgptPromptController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $prompts = ChatgptPrompt::orderBy('created_at', 'desc')->paginate(10);
        
        // If it's an AJAX request, return JSON
        if ($request->ajax()) {
            return response()->json([
                'prompts' => $prompts
            ]);
        }
        
        // Otherwise return the view
        return view('chatgpt_prompts.index', compact('prompts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('chatgpt_prompts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'prompt' => 'required|string|max:65535',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        ChatgptPrompt::create($request->all());

        return redirect()->route('chatgpt-prompts.index')
            ->with('success', 'ChatGPT Prompt created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ChatgptPrompt $chatgptPrompt)
    {
        return view('chatgpt_prompts.show', compact('chatgptPrompt'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ChatgptPrompt $chatgptPrompt)
    {
        return view('chatgpt_prompts.edit', compact('chatgptPrompt'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ChatgptPrompt $chatgptPrompt)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'prompt' => 'required|string|max:65535',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $chatgptPrompt->update($request->all());

        return redirect()->route('chatgpt-prompts.index')
            ->with('success', 'ChatGPT Prompt updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChatgptPrompt $chatgptPrompt)
    {
        $chatgptPrompt->delete();

        return redirect()->route('chatgpt-prompts.index')
            ->with('success', 'ChatGPT Prompt deleted successfully.');
    }
}
