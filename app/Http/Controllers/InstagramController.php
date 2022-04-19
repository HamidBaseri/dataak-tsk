<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Instagram;
use App\Interfaces\InstagramRepository;
use App\Models\Photo;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InstagramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param InstagramRepository $repository
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index(InstagramRepository $repository)
    {
        //if we have query string we should search otherwise we should return all items
        return request()->all()?$repository->search(request()->all()) : Instagram::all();
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
                'body' => 'nullable',
                'name' => 'required',
                'username' => 'required',
                'avatarImage' => 'nullable',
                'filenames' => 'required',
                'filenames.*' => 'mimes:jpg,jpeg,png,webp,avi,mkv,mp4'
            ]);

            if ($v->fails())
            {
                return response()->json($v->getMessageBag(),400);
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
            $request['album_id'] = $album_id;
            if($request->hasfile('avatarImage')){
                $request['avatar'] = $request->file('avatarImage')->store('images');
            }
            $instagram = Instagram::create($request->all());

            return response()->json($instagram);


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
