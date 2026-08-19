<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Image;
use Spatie\Image\Enums\Fit;

use Illuminate\Support\Facades\File;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'news' => News::latest()->get(),
        ];
        return view('news.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'categories' => Category::get(),
        ];
        return view('news.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // $request->validate([
        //     'name'     => 'required|string|max:255',
        //     'image'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        // ]);

        $data = $request->all();
        // dd($data);

        $uploadedFile = $request->file('image');
        $filename = time() . $uploadedFile->getClientOriginalName();
        $destinationPath = storage_path('app/public/news/' . $filename);

        Image::load($uploadedFile->getRealPath())
            ->fit(Fit::Contain, 1200, 1200)
            ->quality(1)
            ->save($destinationPath);

        // $imagePath = null;
        // if ($request->hasFile('image')) {
        //     $imagePath = $request->file('image')->store('news', 'public');
        // }

        // dd($uploadedFile, $destinationPath, $data);

        $news = News::create([
            'title'     => $request->title,
            'description'    => $request->description,
            'category_id'     => $request->category_id,
            'image'    => $filename,
        ]);

        return redirect()->route('news.index')->with('success', 'news created Succeessfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = [
            'news' => News::find($id),
            'categories' => Category::get(),
        ];

        return view('news.create', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, News $news)
    {
        // $data = $request->all();

        if ($request->hasFile('image')) {
            $uploadedFile = $request->file('image');

            $filename = time() . $uploadedFile->getClientOriginalName();
            $destinationPath = storage_path('app/public/news/' . $filename);
            if ($news->image) {
                Storage::disk('public')->delete('news/' . $news->image);
            }

            // if (File::exists($destinationPath)) {
            //     File::delete($destinationPath);
            // }
            // dd($oldimage);
            // Storage::delete($oldimage);
            Storage::disk('public')->delete($news->image);
            // dd("ok");

            Image::load($uploadedFile->getRealPath())
                ->fit(Fit::Contain, 1200, 1200)
                ->quality(1)
                ->save($destinationPath);
        }

        // $imagePath = null;
        // if ($request->hasFile('image')) {
        //     $imagePath = $request->file('image')->store('users', 'public');
        // }


        // $news->update($data);

        $news->update([
            'title'       => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'image'       => $filename ?? $news->image,
        ]);

        return redirect()->route('news.index')->with('success', 'news updated Succeessfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = News::find($id);
        if ($data->image) {
            Storage::disk('public')->delete('news/' . $data->image);
        }
        $data->delete();
        return redirect()->route('news.index')->with('success', 'news Deleted Succeessfully');
    }
}
