<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OncallNotification extends Notification
{
    protected string $type;
    protected array  $data;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $type, array $data)
    {
        $this->type = $type;
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return match ($this->type) {

            'submitted' => [
                'type'    => 'new_oncall',
                'title'   => 'Penugasan Oncall Baru',
                'message' => "{$this->data['employee_name']} mengajukan Oncall pada "
                            . "{$this->data['start_date']} – {$this->data['end_date']} "
                            . "({$this->data['interval']}).",
                'url'     => route('approval-oncall'),
                'icon'    => 'phone',
                'color'   => 'blue',
                'oncall_id' => $this->data['oncall_id'],

            ],
            'approved' => [
                'type'    => 'approved_oncall',
                'title'   => 'Penugasan Oncall Disetujui',
                'message' => "Penugasan oncall Anda pada "
                            . "{$this->data['start_date']} – {$this->data['end_date']} "
                            . "telah disetujui oleh {$this->data['approved_by']}.",
                'url'     => route('oncall.riwayat'),
                'icon'    => 'check-circle',
                'color'   => 'green',
                'oncall_id' => $this->data['oncall_id'],
            ],
            'rejected' => [
                'type'    => 'rejected_oncall',
                'title'   => 'Penugasan Oncall Ditolak',
                'message' => "Penugasan oncall Anda pada "
                            . "{$this->data['start_date']} – {$this->data['end_date']} "
                            . "ditolak oleh {$this->data['rejected_by']}."
                            . (!empty($this->data['reason']) ? " Alasan: {$this->data['reason']}" : ''),
                'url'     => route('riwayat-oncall'),
                'icon'    => 'x-circle',
                'color'   => 'red',
                'oncall_id' => $this->data['oncall_id'],
            ],
            'escalated' => [
                'type'    => 'escalated_oncall',
                'title'   => 'Eskalasi Penugasan Oncall',
                'message' => "Penugasan oncall {$this->data['employee_name']} pada "
                            . "{$this->data['start_date']} – {$this->data['end_date']} "
                            . "telah dieskalasi menunggu persetujuan.",
                'url'     => route('approval-oncall'),
                'icon'    => 'exclamation-triangle',
                'color'   => 'orange',
                'oncall_id' => $this->data['oncall_id'],
            ],

            default => [],
        };
    }

}
