<?php

namespace App\Http\Controllers;

use App\Interfaces\NewsRepository;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param NewsRepository $repository
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index(NewsRepository $repository)
    {
        //if we have query string we should search otherwise we should return all items
        return request()->all()?$repository->search(\request()->all()) : News::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $v = Validator::make($request->all(), [
                'title' => 'required',
                'source' => 'required',
                'body' => 'required',
                'src_url' => 'required|url',
                'avatarImage' => 'nullable|image',
            ]);

            if ($v->fails()) {
                return response()->json($v->getMessageBag(),400);
            }

            if ($request->hasfile('avatarImage')) {
                $news['avatar'] = $request->file('avatarImage')->store('images');
            }
            $news = News::create($request->all());
            return response()->json($news);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
