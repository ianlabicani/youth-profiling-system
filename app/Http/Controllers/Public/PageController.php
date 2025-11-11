<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display the features page
     */
    public function features()
    {
        return view('public.features');
    }

    /**
     * Display the contact page
     */
    public function contact()
    {
        return view('public.contact');
    }

    /**
     * Handle contact form submission
     */
    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10|max:5000',
        ]);

        // TODO: Send email or store message in database
        // For now, just store in session

        return redirect()->route('contact')
            ->with('success', 'Your message has been sent successfully!');
    }
}
