<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\PageRequest;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PagesController extends Controller
{
    public function index()
    {
        Gate::authorize('pages.index');

        $pages = Page::latest()->get();

        return view('dashboard.pages.index', compact('pages'));
    }

    public function create()
    {
        Gate::authorize('pages.create');

        return view('dashboard.pages.create');
    }

    public function store(PageRequest $request)
    {
        Page::create($request->except('_token'));

        return redirect()->route('dashboard.pages.index')->with('success', 'تمت الإضافة بنجاح');
    }

    public function edit(Page $page)
    {
        Gate::authorize('pages.update');

        return view('dashboard.pages.edit', compact('page'));
    }

    public function update(PageRequest $request, Page $page)
    {

        $page->update($request->except('_token'));


        return redirect()->route('dashboard.pages.index')->with('success', 'تم التعديل بنجاح');
    }

    public function destroy($id)
    {
        Gate::authorize('pages.delete');

        Page::destroy($id);

        return redirect()->route('dashboard.pages.index')->with('success', 'تم الحذف بنجاح');
    }
}
