<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\StoreRequest;
use App\Http\Requests\Profile\UpdateRequest;
use App\Http\Resources\ProfileResource;
use App\Models\Profile;
use Illuminate\Http\Request;

class ProfilesController extends Controller
{

    public function index(Request $request)
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
        $profile = Profile::create($request->validated());

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
        $profile->update($request->validated());

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
