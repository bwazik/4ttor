<?php

namespace App\Jobs;

use App\Models\Zoom;
use App\Models\ZoomAccount;
use App\Services\CustomZoomClient;
use Illuminate\Support\Facades\Log;
use App\Traits\ServiceResponseTrait;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleZoomMeetingJob implements ShouldQueue
{
    use Queueable, ServiceResponseTrait;

    const TYPE_CREATE = 'create';
    const TYPE_UPDATE = 'update';
    const TYPE_DELETE = 'delete';

    protected $operation;
    protected $data;

    public function __construct(string $operation, array $data)
    {
        $this->operation = $operation;
        $this->data = $data;
    }

    public function handle()
    {
        try {
            $teacherId = $this->data['teacher_id'] ?? null;
            if (!$teacherId) {
                return $this->errorResponse(trans('main.errorMessage'));
            }

            $zoomAccount = ZoomAccount::where('teacher_id', $teacherId)
                ->select('client_id', 'client_secret', 'account_id')
                ->first();

            if (!$zoomAccount) {
                return $this->errorResponse(trans('teacher/errors.validateTeacherZoomAccount'));
            }

            $zoom = new CustomZoomClient();
            $zoom->setCredentials(
                $zoomAccount->client_id,
                $zoomAccount->client_secret,
                $zoomAccount->account_id
            );

            // Validate credentials
            $accessToken = $zoom->getAccessTokenPublic();

            switch ($this->operation) {
                case self::TYPE_CREATE:
                    $meeting = $zoom->createMeeting($this->data['meeting_data']);
                    if (isset($this->data['zoom_id'])) {
                        Zoom::where('id', $this->data['zoom_id'])->update([
                            'meeting_id' => $meeting['data']['id'] ?? null,
                            'password' => $meeting['data']['password'] ?? null,
                            'start_url' => $meeting['data']['start_url'] ?? null,
                            'join_url' => $meeting['data']['join_url'] ?? null,
                        ]);
                    }
                    Log::info('HandleZoom: Meeting created', ['teacher_id' => $teacherId, 'zoom_id' => $this->data['zoom_id']]);
                    break;

                case self::TYPE_UPDATE:
                    $zoom->updateMeeting($this->data['meeting_id'], $this->data['meeting_data']);
                    Log::info('HandleZoom: Meeting updated', ['teacher_id' => $teacherId, 'meeting_id' => $this->data['meeting_id']]);
                    break;

                case self::TYPE_DELETE:
                    $zoom->deleteMeeting($this->data['meeting_id']);
                    Log::info('HandleZoom: Meeting deleted', ['teacher_id' => $teacherId, 'meeting_id' => $this->data['meeting_id']]);
                    break;
            }
        } catch (\Exception $e) {
            Log::error('HandleZoom: Operation failed', [
                'teacher_id' => $teacherId ?? 'unknown',
                'operation' => $this->operation,
                'error' => $e->getMessage(),
                'trace' => substr($e->getTraceAsString(), 0, 200) . '...',
            ]);
            return $this->errorResponse($e->getMessage());
        }
    }
}
