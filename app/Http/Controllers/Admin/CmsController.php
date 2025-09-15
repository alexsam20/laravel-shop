<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use Illuminate\Http\Request;

class CmsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cmsPages = CmsPage::get()->toArray();

        return view('admin.pages.cms_pages', compact('cmsPages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CmsPage $cmsPage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id = null)
    {
        if ($id == "") {
            $title = "Add New CMS Page";
            $cmsPage = new CmsPage();
            $message = "CMS Page added successfully.";
        } else {
            $title = "Edit CMS Page";
        }

        if ($request->isMethod("post")) {
            $data = $request->all();

            // CMS Pages Validations
            $request->validate([
                'title' => 'required|max:255',
                'url' => 'required|max:255',
                'description' => 'required',
            ]);

            $cmsPage->title = $data['title'];
            $cmsPage->url = $data['url'];
            $cmsPage->description = $data['description'];
            $cmsPage->meta_title = $data['meta_title'];
            $cmsPage->meta_description = $data['meta_description'];
            $cmsPage->meta_keywords = $data['meta_keywords'];
            $cmsPage->status = 1;

            if ($cmsPage->save()) {
                return redirect()->route('cms-pages')->with('success_message', $message);
            }
        }

        return view('admin.pages.add-edit-cms-page', compact('title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            if ($data['status'] == 'Active') {
                $status = 0;
            } else {
                $status = 1;
            }
        }

        CmsPage::where('id', $data['page_id'])->update(['status' => $status]);

        return response()->json(['status' => $status, 'page_id' => $data['page_id']]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CmsPage $cmsPage)
    {
        //
    }
}
