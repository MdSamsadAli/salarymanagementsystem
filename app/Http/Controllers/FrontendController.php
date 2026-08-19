<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\News;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function index()
    {
        $menu_categories = Category::whereNull('parent_id')->with('childrenRecursive')->get();
        $newsdata = News::with('category')->get();
        return view('website.index', compact('newsdata', 'menu_categories'));
    }

    public function page($id)
    {
        $menu_categories = Category::whereNull('parent_id')->with('childrenRecursive')->get();
        $newsdata = News::where('category_id', $id)->get();
        return view('website.page', compact('newsdata', 'menu_categories'));
    }

    public function detail($id)
    {
        $menu_categories = Category::whereNull('parent_id')->with('childrenRecursive')->get();
        $news = News::find($id);
        return view('website.detail', compact('news', 'menu_categories'));
    }
}
