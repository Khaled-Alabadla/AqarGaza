<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Mail\ContactReply;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;

class ContactMessagesController extends Controller
{
    public function index()
    {
        Gate::authorize('contacts.index');

        $messages = Contact::all();

        return view('dashboard.contacts.index', compact('messages'));
    }

    // public function

    public function show($id)
    {
        Gate::authorize('contacts.show');

        $message = Contact::findOrFail($id);

        return view('dashboard.contacts.show', compact('message'));
    }

    public function reply(Request $request, $id)
    {
        Gate::authorize('contacts.reply');

        $message = Contact::findOrFail($id);

        Mail::to($message->email)->send(new ContactReply($message->name, $request->message));

        $message->update([
            'is_open' => 1
        ]);

        flash()->success('تم إرسال الرسالة بنجاح');


        return redirect()->route('dashboard.contact_messages.index');
    }

    public function destroy($id)
    {
        Gate::authorize('contacts.delete');

        Contact::destroy($id);

        flash()->success('تم الحذف بنجاح');

        return redirect()->route('dashboard.contact_messages.index');
    }
}
