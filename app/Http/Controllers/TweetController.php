<?php

namespace App\Http\Controllers;

use App\Interfaces\TweetsRepository;
use App\Models\Tweet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TweetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param TweetsRepository $repository
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index(TweetsRepository $repository)
    {
        //if we have query string we should search otherwise we should return all items
        return request()->all()?$repository->search(\request()->all()) : Tweet::all();
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
                'username' => 'required',
                'body' => 'required',
                'retweets' => 'required|min:0',
                'avatarImage' => 'required|image',
                'tweetImage' => 'nullable|image',
            ]);

            if ($v->fails()) {
                return response()->json($v->getMessageBag(),400);
            }

            $tweetAvatar = $request->file('avatarImage')->store('images');
            $request['avatar'] = $tweetAvatar;
            if($request->hasfile('tweetImage')) {
                $request['image'] = $request->file('tweetImage')->store('images');
            }
            $tweet = Tweet::create($request->all());
            return response()->json($tweet);

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
