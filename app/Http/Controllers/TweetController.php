<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use App\Tweets\TweetsRepository;
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
        if (request()->has('q')) {
            if(request()->has('date')){
                return $repository->search(request('q'),request('date'));
            } else {
                return $repository->search(request('q'));
            }
        } else {
            return Tweet::all();
        }
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
                'username' => 'required',
                'body' => 'required',
                'retweets' => 'required|min:0',
                'avatar' => 'required|image',
                'image' => 'nullable|image',
            ]);

            if ($v->fails())
            {
                return $v->getMessageBag();
            }

            $tweet = new Tweet();

            $tweet->body = $request['body'];
            $tweet->username = $request['username'];
            $tweet->retweets = $request['retweets'];
            if($request->hasfile('image')) {
                $tweet->image = $request->file('image')->store('images');
            }
            $tweet->avatar = $request->file('avatar')->store('images');
            $tweet->save();
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
