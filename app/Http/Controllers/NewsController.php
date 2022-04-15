<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\User;
use App\News\NewsRepository;
use App\Notifications\NewsCreated;
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
        if (request()->has('q')) {
            return $repository->search(request('q'));
        } else {
            return News::all();
        }

        // return News::all();
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $v = Validator::make($request->all(), [
                'title' => 'required',
                'source' => 'required',
                'body' => 'required',
                'src_url' => 'required',
                'avatar' => 'nullable|image',
            ]);

            if ($v->fails()) {
                return $v->getMessageBag();
            }


            $news = new News();
            if ($request->hasfile('avatar')) {
                $news->avatar = $request->file('avatar')->store('images');
            }

            $news->title = $request['title'];
            $news->source = $request['source'];
            $news->body = $request['body'];
            $news->src_url = $request['src_url'];

            $news->save();

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
