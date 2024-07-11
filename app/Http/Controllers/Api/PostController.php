<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['category', 'user'])
            ->when(request()->serach, function ($posts) {
                $posts = $posts->where('users_id', auth()->user()->id);
            })->latest()->paginate(5);

        $posts->appends(['search' => request()->search]);


        if ($posts) {
            return new PostResource(true, 'List Data Post', $posts);
        } else {
            return new PostResource(false, 'Failed Load Data Post', $posts);
        }
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'required',
            'user_id' => 'required',
            'title' => 'required',
            'image' => 'sometimes|nullable|image|max:2048',
            'content' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        if ($request->hasFile('image')) {

            $image = $request->file('image');

            $image->storeAs('/public/post', $image->hashName());
            $posts = new Post();
            $posts->category_id = $request->category;
            $posts->user_id = Auth::user()->id;
            $posts->title = $request->title;
            $posts->image = $image;
            $posts->content = $request->content;
            $posts->slug = Str::slug($request->title);
            $posts->save();


            if ($posts) {
                return response()->json([
                    'status' => true,
                    'message' => 'Successfuly created post',
                    'data' => null
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Failed created post',
                    'data' => null
                ]);
            }
        } else {
            $posts = new Post();
            $posts->category_id = $request->category;
            $posts->user_id = Auth::user()->id;
            $posts->title = $request->title;
            $posts->content = $request->content;
            $posts->slug = Str::slug($request->title);
            $posts->save();

            if ($posts) {
                return response()->json([
                    'status' => true,
                    'message' => 'Successfuly created post',
                    'data' => null
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Failed created post',
                    'data' => null
                ]);
            }
        }
    }


    public function show($id)
    {
        $posts = Post::find($id);

        if ($posts) {
            return response()->json([
                'status' => 'success',
                'message' => 'Post detail',
                'data' => $posts
            ]);
        }
    }

    public function update(Request $request, Post $post)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'required',
            'user_id' => 'required',
            'title' => 'required',
            'image' => 'sometimes|nullable|image|max:2048',
            'content' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        if ($request->hasFile('image')) {

            $image = $request->file('image');

            $image->storeAs('/public/post', $image->hashName());
            $posts = new Post();
            $posts->category_id = $request->category;
            $posts->user_id = Auth::user()->id;
            $posts->title = $request->title;
            $posts->image = $image;
            $posts->content = $request->content;
            $posts->slug = Str::slug($request->title);
            $posts->save();


            if ($posts) {
                return response()->json([
                    'status' => true,
                    'message' => 'Successfuly updated post',
                    'data' => null
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Failed updated post',
                    'data' => null
                ]);
            }
        } else {
            $posts = new Post();
            $posts->category_id = $request->category;
            $posts->user_id = Auth::user()->id;
            $posts->title = $request->title;
            $posts->content = $request->content;
            $posts->slug = Str::slug($request->title);
            $posts->save();

            if ($posts) {
                return response()->json([
                    'status' => true,
                    'message' => 'Successfuly updated post',
                    'data' => null
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Failed updated post',
                    'data' => null
                ]);
            }
        }
    }

    public function destroy($id)
    {
        $posts = Post::find($id);

        if ($posts) {
            return response()->json([
                'status' => true,
                'message' => 'Post deleted',
                'data' => null
            ]);
        }
    }
}
