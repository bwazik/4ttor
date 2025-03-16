<?php

namespace App\Services\Admin\Activities;

use Carbon\Carbon;
use App\Models\Zoom;
use App\Models\Grade;
use App\Models\Group;
use App\Models\Teacher;
use App\Models\ZoomAccount;
use App\Jobs\HandleZoomMeetingJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\PreventDeletionIfRelated;
use Jubaer\Zoom\Facades\Zoom as ZoomFacade;

class ZoomService
{
    use PreventDeletionIfRelated;

    protected $relationships = [];

    protected $transModelKey = 'admin/zooms.zooms';

    public function getZoomsForDatatable($zoomsQuery)
    {
        return datatables()->eloquent($zoomsQuery)
            ->addIndexColumn()
            ->addColumn('selectbox', function ($row) {
                $btn = '<td class="dt-checkboxes-cell"><input type="checkbox" value="' . $row->id . '" class="dt-checkboxes form-check-input"></td>';
                return $btn;
            })
            ->editColumn('topic', function ($row) {
                return $row->topic;
            })
            ->editColumn('teacher_id', function ($row) {
                return "<a target='_blank' href='" . route('admin.teachers.details', $row->teacher_id) . "'>" . ($row->teacher_id ? $row->teacher->name : '-') . "</a>";
            })
            ->editColumn('grade_id', function ($row) {
                return $row->grade_id ? $row->grade->name : '-';
            })
            ->editColumn('group_id', function ($row) {
                return $row->group_id ? $row->group->name : '-';
            })
            ->editColumn('duration', function ($row) {
                $minutes = $row->duration;
                $hours = floor($minutes / 60);
                $remainingMinutes = $minutes % 60;

                if ($hours > 0) {
                    return $hours . ' ' . trans('admin/zooms.hours') . '' .
                    ($remainingMinutes > 0 ? ' ' . trans('admin/zooms.and') . ' ' .
                    $remainingMinutes . ' ' . trans('admin/zooms.minute') . '' : '');
                }
                return $remainingMinutes . ' ' . trans('admin/zooms.minutes') . '';
            })
            ->editColumn('start_time', function ($row) {
                return \Carbon\Carbon::parse($row->start_time)->diffForHumans();
            })
            ->editColumn('join_url', function ($row) {
                return '<a href="'.$row->join_url.'" target="_blank" class="btn btn-sm btn-label-success waves-effect">'.trans('main.join_url').'</a>';
            })
            ->addColumn('actions', function ($row) {
                return
                    '<div class="align-items-center">' .
                    '<span class="text-nowrap">
                        <button class="btn btn-sm btn-icon btn-text-secondary text-body rounded-pill waves-effect waves-light"
                            tabindex="0" type="button" data-bs-toggle="offcanvas" data-bs-target="#edit-modal"
                            id="edit-button" data-id=' . $row->id . ' data-meeting_id="' . $row -> meeting_id . '" data-topic_ar="' . $row->getTranslation('topic', 'ar') . '" data-topic_en="' . $row->getTranslation('topic', 'en') . '"
                            data-teacher_id="' . $row->teacher_id . '" data-grade_id="' . $row->grade_id . '" data-group_id="' . $row->group_id . '"
                            data-duration="' . $row->duration . '" data-start_time="' . \Carbon\Carbon::parse($row -> start_time)->format('Y-m-d H:i') . '">
                            <i class="ri-edit-box-line ri-20px"></i>
                        </button>
                    </span>' .
                    '<button class="btn btn-sm btn-icon btn-text-danger rounded-pill text-body waves-effect waves-light me-1"
                            id="delete-button" data-id=' . $row->id . ' data-meeting_id=' . $row->meeting_id . '
                            data-topic_ar="' . $row->getTranslation('topic', 'ar') . '" data-topic_en="' . $row->getTranslation('topic', 'en') . '"
                            data-bs-target="#delete-modal" data-bs-toggle="modal" data-bs-dismiss="modal">
                            <i class="ri-delete-bin-7-line ri-20px text-danger"></i>
                        </button>' .
                    '</div>';
            })
            ->rawColumns(['selectbox', 'teacher_id', 'join_url', 'actions'])
            ->make(true);
    }

    public function insertZoom(array $request)
    {
        DB::beginTransaction();

        try {

            if ($validationError = $this->validateZoomRequest($request)) {
                return $validationError;
            }

            $this->configureZoomAPI($request['teacher_id']);

            $meetingData = $this->prepareMeetingData($request);

            $zoom = Zoom::create([
                'teacher_id' => $request['teacher_id'],
                'grade_id' => $request['grade_id'],
                'group_id' => $request['group_id'],
                'meeting_id' => null,
                'topic' => ['en' => $request['topic_en'], 'ar' => $request['topic_ar']],
                'duration' => $request['duration'],
                'password' => $request['password'] ?? null,
                'start_time' => $request['start_time'],
                'start_url' => 'https://اصبر_علي_الصفحة_هتظهر_بعد_لما_تعمل_الميتنج.com',
                'join_url' => 'https://اصبر_علي_الصفحة_هتظهر_بعد_لما_تعمل_الميتنج.com',
            ]);

            dispatch(new HandleZoomMeetingJob(HandleZoomMeetingJob::TYPE_CREATE, ['meeting_data' => $meetingData, 'zoom_id' => $zoom->id]));

            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.added', ['item' => trans('admin/zooms.zoom')]),
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

    public function updateZoom($id, $meeting_id, array $request): array
    {
        DB::beginTransaction();

        try {
            if ($validationError = $this->validateZoomRequest($request)) {
                return $validationError ;
            }

            $this->configureZoomAPI($request['teacher_id']);

            $meetingData = $this->prepareMeetingData($request, false);

            dispatch(new HandleZoomMeetingJob(HandleZoomMeetingJob::TYPE_UPDATE,
            [
                    'meeting_id' => $meeting_id,
                    'meeting_data' => $meetingData
                ]));

            $zoom = Zoom::findOrFail($id);
            $zoom->update([
                'teacher_id' => $request['teacher_id'],
                'grade_id' => $request['grade_id'],
                'group_id' => $request['group_id'],
                'topic' => ['en' => $request['topic_en'], 'ar' => $request['topic_ar']],
                'duration' => $request['duration'],
                'start_time' =>  $request['start_time'],
            ]);

            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.edited', ['item' => trans('admin/zooms.zoom')]),
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

    public function deleteZoom($id, $meeting_id): array
    {
        DB::beginTransaction();

        try {
            $zoom = Zoom::select('id', 'topic')->findOrFail($id);

            if ($dependencyCheck = $this->checkDependenciesForSingleDeletion($zoom)) {
                return $dependencyCheck;
            }

            $zoom->delete();
            if ($meeting_id) {
                dispatch(new HandleZoomMeetingJob(HandleZoomMeetingJob::TYPE_DELETE, ['meeting_id' => $meeting_id]));
            }

            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.deleted', ['item' => trans('admin/zooms.zoom')]),
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

    public function deleteSelectedZooms($ids)
    {
        if (empty($ids)) {
            return [
                'status' => 'error',
                'message' => trans('main.noItemsSelected'),
            ];
        }

        DB::beginTransaction();

        try {
            $zooms = Zoom::whereIn('id', $ids)
                ->select('id', 'topic')
                ->orderBy('id')
                ->get();

            if ($dependencyCheck = $this->checkDependenciesForMultipleDeletion($zooms)) {
                return $dependencyCheck;
            }

            $meetings = Zoom::whereIn('id', $ids)->get();

            foreach($meetings as $meeting)
            {
                if ($meeting->meeting_id) {
                    try {
                        dispatch(new HandleZoomMeetingJob(HandleZoomMeetingJob::TYPE_DELETE, ['meeting_id' => $meeting->meeting_id]));
                    } catch (\Exception $e) {
                        Log::warning("Failed to delete Zoom meeting {$meeting->meeting_id}: " . $e->getMessage());
                    }
                }
                $meeting->delete();
            }

            DB::commit();
            return [
                'status' => 'success',
                'message' => trans('main.deletedSelected', ['item' => strtolower(trans('admin/zooms.zooms'))]),
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

    public function checkDependenciesForSingleDeletion($zoom)
    {
        return $this->checkForSingleDependencies($zoom, $this->relationships, $this->transModelKey);
    }

    public function checkDependenciesForMultipleDeletion($zooms)
    {
        return $this->checkForMultipleDependencies($zooms, $this->relationships, $this->transModelKey);
    }

    private function validateZoomRequest(array $request): ?array
    {
        if (!$this->verifyTeacherAuthorization($request['teacher_id'], $request['grade_id'], $request['group_id'])) {
            return [
                'status' => 'error',
                'message' => trans('main.validateTeacherGradesGroups'),
            ];
        }

        if (!$this->hasZoomAccount($request['teacher_id'])) {
            return [
                'status' => 'error',
                'message' => trans('main.validateTeacherZoomAccount'),
            ];
        }

        return null;
    }


    private function verifyTeacherAuthorization(int $teacherId, int $gradeId, int $groupId): bool
    {
        return Teacher::where('id', $teacherId)
            ->whereHas('grades', function($query) use ($gradeId) {
                $query->where('grades.id', $gradeId);
            })
            ->whereHas('groups', function($query) use ($groupId, $gradeId) {
                $query->where('groups.id', $groupId)
                    ->where('groups.grade_id', $gradeId);
            })
            ->exists();
    }

    private function configureZoomAPI(int $teacherId)
    {
        $zoomAccount = ZoomAccount::where('teacher_id', $teacherId)
            ->select('client_id', 'client_secret', 'account_id')
            ->first();

        if (!$zoomAccount) {
            return [
                'status' => 'error',
                'message' => trans('main.validateTeacherZoomAccount'),
            ];
        }

        config([
            'zoom.client_id' => $zoomAccount->client_id,
            'zoom.client_secret' => $zoomAccount->client_secret,
            'zoom.account_id' => $zoomAccount->account_id,
        ]);

        return true;
    }

    private function hasZoomAccount(int $teacherId): bool
    {
        return ZoomAccount::where('teacher_id', $teacherId)->exists();
    }

    private function prepareMeetingData(array $request, bool $includeSettings = true): array
    {
        $grade = Grade::where('id', $request['grade_id'])->pluck('name')->first();
        $group = Group::where('id', $request['group_id'])->pluck('name')->first();
        $teacher = Teacher::where('id', $request['teacher_id'])->pluck('name')->first();
        $start_time = Carbon::parse($request['start_time'])->setTimezone('UTC')->format('Y-m-d\TH:i:s\Z');

        $meetingData = [
            "agenda" => $grade . ' - ' . $group,
            "topic" => $request['topic_ar'] . ' - ' . $request['topic_en'] . ' - ' . $teacher,
            "duration" => $request['duration'],
            "timezone" => config('app.timezone'),
            "start_time" => $start_time,
        ];

        if ($includeSettings) {
            $meetingData["password"] = $request['password'];
            $meetingData["settings"] = [
                'join_before_host' => false,
                'host_video' => false,
                'participant_video' => false,
                'mute_upon_entry' => true,
                'waiting_room' => true,
                'audio' => 'both',
                'auto_recording' => 'none',
                'approval_type' => 1,
            ];
        }

        return $meetingData;
    }
}
