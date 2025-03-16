<?php

namespace App\Jobs;

use App\Models\Zoom;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Jubaer\Zoom\Facades\Zoom as ZoomFacade;

class HandleZoomMeetingJob implements ShouldQueue
{
    use Queueable;

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
            switch ($this->operation) {
                case self::TYPE_CREATE:
                    $meeting = ZoomFacade::createMeeting($this->data['meeting_data']);

                    if (isset($this->data['zoom_id'])) {
                        Zoom::where('id', $this->data['zoom_id'])->update([
                            'meeting_id' => $meeting['data']['id'] ?? null,
                            'password' => $meeting['data']['password'] ?? null,
                            'start_url' => $meeting['data']['start_url'] ?? null,
                            'join_url' => $meeting['data']['join_url'] ?? null,
                        ]);
                    }
                    break;

                case self::TYPE_UPDATE:
                    ZoomFacade::updateMeeting($this->data['meeting_id'], $this->data['meeting_data']);
                    break;

                case self::TYPE_DELETE:
                    ZoomFacade::deleteMeeting($this->data['meeting_id']);
                    break;
            }
        } catch (\Exception $e) {
            Log::error("Zoom API operation failed: {$e->getMessage()}");
        }
    }
}
