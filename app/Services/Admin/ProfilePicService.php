<?php

namespace App\Services\Admin;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProfilePicService
{
    public function updateProfilePic($request, $model, $id, $directory = 'students')
    {
        DB::beginTransaction();

        try {
            $entity = $model::select('id', 'profile_pic')->findOrFail($id);

            if ($request->hasFile('profile')) {
                $file = $request->file('profile');

                $fileName = uniqid($directory . '_', true) . '.' . $file->getClientOriginalExtension();

                $file->storeAs($directory, $fileName, 'profiles');

                $oldPicture = $entity->profile_pic;
                if ($oldPicture && Storage::disk('profiles')->exists($directory . '/' . $oldPicture)) {
                    Storage::disk('profiles')->delete($directory . '/' . $oldPicture);
                }

                $entity->profile_pic = $fileName;
                $entity->save();

                DB::commit();

                return [
                    'status' => 'success',
                    'message' => trans('main.profileUpdated'),
                ];
            }

            return [
                'status' => 'error',
                'message' => 'No file uploaded'
            ];

        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'status' => 'error',
                'message' => config('app.env') === 'production'
                    ? trans('main.errorMessage')
                    : $e->getMessage(),
            ];
        }
    }
}
