<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::all();
        
        return response()->json($blogs);
    }
    
    public function show($id)
    {
        $blog = Blog::findOrFail($id);
        
        return response()->json($blog);
    }
    
    public function store(Request $request)
    {
        // dd($request);
        $blog = Blog::create($request->all());
   
        return response()->json($blog, 200);
    }

    
    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        $blog->delete();
    
        return response()->json(['message' => 'Blog deleted successfully'], 200);
    }
    
    
    




    public function storeblog(Request $request){

        $validatedData = $request->validate([
            'title' => 'required',
            'author' => 'required',
            'content' => 'required',
            'imgUrl' => 'required',
        ]);

 

            

            $blog = new Blog($validatedData);

            

            $blog->save();
            
            return response()->json(['message' => 'Blog created successfully'], 200);
    
    }
    
    public function update(Request $request, $id)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
            'content' => 'required|string',
            'imgUrl' => 'required|string',
        ]);
    
        try {
            // Find the blog post by ID
            $blog = Blog::findOrFail($id);
    
            // Update the blog post with the validated data
            $blog->update($validatedData);
    
            return response()->json(['message' => 'Blog updated successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error updating blog'], 500);
        }
    }




//
    
}


