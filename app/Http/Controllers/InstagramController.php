<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Instagram;
use App\Instagrams\InstagramsRepository;
use App\Models\Photo;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InstagramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param InstagramsRepository $repository
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index(InstagramsRepository $repository)
    {
        if (request()->has('q')) {
            return $repository->search(request('q'));
        } else {
            return Instagram::all();
        }

        // return Instagram::all();
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
                'body' => 'nullable',
                'name' => 'required',
                'username' => 'required',
                'avatar' => 'nullable',
                'filenames' => 'required',
                'filenames.*' => 'mimes:jpg,jpeg,png,webp,avi,mkv,mp4'
            ]);

            if ($v->fails())
            {
                return $v->getMessageBag();
            }


            if($request->hasfile('filenames'))
            {
                $album = new Album();
                //This is just for test
                $album->user_id = 1;
                $album->save();
                $album_id = $album->id;
                foreach($request->file('filenames') as $file)
                {
                    $name = time().'.'.$file->extension();
                    if (in_array($file->extension(),['jpg','jpeg','png','webp'])){
                        $file->move(storage_path().'/app/images/', $name);
                        $photo= new Photo();
                        $photo->path = storage_path().'/app/images/'. $name;
                        $photo->album_id = $album_id;
                        $photo->save();
                    } else if (in_array($file->extension(),['avi','mkv','mp4'])){
                        $file->move(storage_path().'/app/videos/', $name);
                        $video= new Video();
                        $video->path = storage_path().'/app/videos/'. $name;
                        $video->album_id = $album_id;
                        $video->save();
                    }

                }
            }



            $Instagram = new Instagram();

            $Instagram->title = $request['title'];
            if(request()->has('body') ){
                $Instagram->body = $request['body'];
            }
            $Instagram->name = $request['name'];
            $Instagram->username = $request['username'];
            $Instagram->album_id = $album_id;
            if($request->hasfile('avatar')){
                $Instagram->avatar = $request->file('avatar')->store('images');
            }
            $Instagram->save();
            return response()->json($Instagram);


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
