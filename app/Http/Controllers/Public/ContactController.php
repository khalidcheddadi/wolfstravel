<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Mail\ContactMessageMail;
use App\Models\Location\City;
use App\Models\Listing\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        $cities = City::orderBy('name')->get();
        $categories = Category::whereNull('parent_id')->with('children')->get();
        $listings = collect();

        return view('public.contact', compact('cities', 'categories', 'listings'));
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['nullable', 'string', 'max:200'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        try {
            Mail::to(config('mail.from.address', 'hello@example.com'))
                ->send(new ContactMessageMail(
                    $validated['name'],
                    $validated['email'],
                    $validated['subject'] ?? null,
                    $validated['message'],
                ));
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'No pudimos enviar tu mensaje en este momento. Inténtalo de nuevo más tarde o contáctanos directamente por correo.');
        }

        return redirect()->route('contact')->with('success', 'Su mensaje ha sido enviado correctamente. Le responderemos lo antes posible.');
    }
}
