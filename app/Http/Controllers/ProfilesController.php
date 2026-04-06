<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\StoreRequest;
use App\Http\Requests\Profile\UpdateRequest;
use App\Http\Resources\ProfileResource;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilesController extends Controller
{

    public function index()
    {
        $isConnected = auth('sanctum')->check();

        $query = Profile::query();
        if (! $isConnected) {
            $query->where('status', Profile::STATUS_ACTIVE);
        }
        $profiles = $query->get();

        return ProfileResource::collection($profiles);
    }

    /**
     * @param StoreRequest $request
     * @return ProfileResource
     */
    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $file = $request->file('image');

        //Create new image and update path
        if (isset($file)) {
            $path = Storage::disk('public')->put('profiles', $file);
            $data['image'] = '/storage/' . $path;
        }

        $profile = Profile::create($data);

        return new ProfileResource($profile);
    }

    /**
     * @param int $id
     * @return ProfileResource
     */
    public function show(int $id): ProfileResource
    {
        $profile = Profile::findOrFail($id);

        return new ProfileResource($profile);
    }

    /**
     * @param UpdateRequest $request
     * @param Profile $profile
     * @return ProfileResource
     */
    public function update(UpdateRequest $request, Profile $profile)
    {
        $data = $request->validated();
        $file = $request->file('image');

        if (isset($file)) {

            //Delete old image if exists
            if (! empty($profile->image)) {
                $oldPath = str_replace('/storage/', '', $profile->image);

                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            //Create new image and update path
            $path = Storage::disk('public')->put('profiles', $file);
            $data['image'] = '/storage/' . $path;
        }

        $profile->update($data);
        $profile->refresh();

        return new ProfileResource($profile);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $profile = Profile::findOrFail($id);
        $profile->delete();

        return response()->noContent();
    }
}
