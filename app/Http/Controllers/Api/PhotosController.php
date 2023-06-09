<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LikeModels;
use App\Models\PhotosModels;
use App\Models\UnlikeModels;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PhotosController extends Controller
{

    protected $modelPhotos;

    protected $modelLikes;

    public function __construct(PhotosModels $modelPhotos, LikeModels $modelLikes)
    {
        $this->modelPhotos = $modelPhotos;
        $this->modelLikes = $modelLikes;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = 50; //limit default perpage jika datanya sudah melebihi dari batas/limit query
        if ($limit >= $request->limit) {
            $limit = $request->limit;
        }
        $data = $this->modelPhotos->when($request->name, function ($query) use ($request) {
            return $query->where('name', 'like', "%{$request->name}%");
        })->when($request->caption, function ($query) use ($request) {
            return $query->where('caption', 'like', "%{$request->caption}%");
        })->when($request->tags, function ($query) use ($request) {
            return $query->where('tags', 'like', "%{$request->tags}%");
        })
            ->with('like')
            ->orderByDesc('id')
            ->paginate($limit);
        return $this->builder($data->items());
    }


    /**
     * Display a detail of the resource.
     */
    public function detail($id)
    {
        if ($data = $this->modelPhotos
            ->whereId($id)
            ->first()
        ) {
            $result =  $this->builder($data, 'SuccessFully Detail Data');
        } else {
            $result =  $this->builder('id not found', 'id tidak di temukan');
        }
        return $result;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required|unique:photos,name',
            'caption' => 'required',
            'tags' => 'required',
            'img' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ], [
            'required' => ':attribute jangan di kosongkan',
            'image' => 'yang di upload harus gambar',
            'mimes' => 'minimal extension img yang diupload jpg,png,jpeg,gif,svg',
            'max' => 'maximal 2MB',
            'unique' => 'foto sudah ada',
        ]);

        if ($validator->fails()) {
            $result = $this->customError($validator->errors());
        } else {
            $data['img'] = Storage::disk('public')->put('images', $request->file('img'));
            $data['users_id'] = $request->user()->id; // create photos base on session
            $result = $this->builder($this->modelPhotos->create($data));
        }
        return $result;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'string',
            'caption' => 'string|required',
            'tags' => 'string|required',
            'img' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ], [
            'required' => ':attribute jangan di kosongkan',
            'image' => 'yang di upload harus gambar',
            'mimes' => 'minimal extension img yang diupload jpg,png,jpeg,gif,svg',
            'max' => 'maximal 2MB',
            'unique' => 'foto sudah ada',
        ]);

        if ($validator->fails()) {
            $result = $this->customError($validator->errors());
        } else {
            if ($id > 0) { //mencegah input angka mines dan 0, id gak ada yang mines or 0
                if ($update = $this->modelPhotos
                    ->whereId($id)
                    ->first()
                ) {
                    $update->update($data);
                    $result = $this->builder($update, 'Successfully Update');
                } else {
                    $result = $this->builder('id tidak di temukan', 'id not found', 422);
                }
            } else {
                $result = $this->builder('insert id', 'masukan id photos', 422);
            }
        }
        return $result;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if ($id > 0) { //mencegah input angka mines dan 0, id gak ada yang mines or 0
            if ($delete = $this->modelPhotos->whereId($id)->first()) {
                if ($deletePhotosLike = $this->modelLikes->wherephotos_id($delete->id)) {
                    $deletePhotosLike->delete();
                }
                $delete->delete();
                $result = $this->builder($delete, 'Successfully Delete Photos');
            } else {
                $result = $this->builder('id tidak ditemukan', 'id not found', 422);
            }
        } else {
            $result = $this->builder('insert id', 'masukan id photos', 422);
        }
        return $result;
    }

    /**
     * like the specified resource from like photos.
     */
    public function like($id, Request $request)
    {
        if ($id > 0) { //mencegah input angka mines dan 0, id gak ada yang mines or 0
            if ($photos = $this->modelPhotos
                ->whereId($id)
                ->first()
            ) {
                $result = $this->builder($this->modelLikes->create([
                    'photos_id' => $photos->id,
                    'users_id' => $request->user()->id
                ]), 'berhasil like foto');
            } else {
                $result = $this->builder('id not found', 'id tidak di temukan', 422);
            }
        } else {
            $result = $this->builder('insert id', 'masukan id photos', 422);
        }
        return $result;
    }

    /**
     * unlike specified resource from unlike photos .
     */
    public function unlike($id, Request $request)
    {
        if ($id > 0) { //mencegah input angka mines dan 0, id gak ada yang mines or 0
            if ($photos = $this->modelPhotos
                ->whereId($id)
                ->first()
            ) {
                if ($unlike = $this->modelLikes
                    ->wherephotos_id($photos->id)
                    ->first()
                    ->whereusers_id($request->user()->id)
                    ->first()
                ) {
                    //menghapus penyukaan foto berdasarkan foto yang kita sukai, buat mencegah menghapus like orang/user lain
                    $unlike->delete();
                    $result = $this->builder($photos, 'berhasil unlike foto');
                } else {
                    $result = $this->builder('have been unlike', 'foto sudah di unlike', 422);
                }
            } else {
                $result = $this->builder('id not found', 'id tidak di temukan', 422);
            }
        } else {
            $result = $this->builder('insert id photos', 'masukan id photos', 422);
        }
        return $result;
    }
}
