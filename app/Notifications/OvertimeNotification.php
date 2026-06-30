<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OvertimeNotification extends Notification
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
                'type'    => 'new_overtime',
                'title'   => 'Pengajuan Lembur Baru',
                'message' => "{$this->data['employee_name']} mengajukan lembur pada "
                            . "{$this->data['start_date']} – {$this->data['end_date']} "
                            . "({$this->data['interval']}).",
                'url'     => route('approval-overtime'),
                'icon'    => 'clock',
                'color'   => 'blue',
                'overtime_id' => $this->data['overtime_id'],

            ],
            'approved' => [
                'type'    => 'approved_overtime',
                'title'   => 'Pengajuan Lembur Disetujui',
                'message' => "Pengajuan lembur Anda pada "
                            . "{$this->data['start_date']} – {$this->data['end_date']} "
                            . "telah Disetujui.",
                'url'     => route('overtime.riwayat'),
                'icon'    => 'check-circle',
                'color'   => 'green',
                'overtime_id' => $this->data['overtime_id'],
            ],
            'rejected' => [
                'type'    => 'rejected_overtime',
                'title'   => 'Pengajuan Lembur Ditolak',
                'message' => "Pengajuan lembur Anda pada "
                            . "{$this->data['start_date']} – {$this->data['end_date']} "
                            . "ditolak oleh: {$this->data['rejected_by']}.",
                'url'     => route('overtime.riwayat'),
                'icon'    => 'times-circle',
                'color'   => 'red',
                'overtime_id' => $this->data['overtime_id'],
            ],
            'escalated' => [
                'type'    => 'escalated_overtime',
                'title'   => 'Eskalasi Pengajuan Lembur',
                'message' => "Pengajuan lembur {$this->data['employee_name']} pada "
                            . "{$this->data['start_date']} – {$this->data['end_date']} "
                            . "telah dieskalasi menunggu persetujuan.",
                'url'     => route('approval-overtime'),
                'icon'    => 'exclamation-triangle',
                'color'   => 'orange',
                'overtime_id' => $this->data['overtime_id'],
            ],

        };
    }
}
