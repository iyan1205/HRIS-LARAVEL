<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeaveNotification extends Notification
{
    protected string $type;
    protected array  $data;
    protected bool   $withMail;

    /**
     * @param string $type     submitted | approved | rejected | escalated
     * @param array  $data     payload notifikasi
     * @param bool   $withMail kirim email juga? (default: false)
     */
    public function __construct(string $type, array $data, bool $withMail = false)
    {
        $this->type     = $type;
        $this->data     = $data;
        $this->withMail = $withMail;
    }

    /* ─────────────────────────────────────────────────────────
     | Channel — database selalu aktif, mail opsional
     ───────────────────────────────────────────────────────── */
    public function via(object $notifiable): array
    {
        $channels = ['database'];

        if ($this->withMail) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    /* ─────────────────────────────────────────────────────────
     | Database
     ───────────────────────────────────────────────────────── */
    public function toDatabase(object $notifiable): array
    {
        return match ($this->type) {

            'submitted' => [
                'type'     => 'new_leave',
                'title'    => 'Pengajuan Cuti Baru',
                'message'  => "{$this->data['employee_name']} mengajukan cuti "
                            . "{$this->data['leave_type']} pada "
                            . "{$this->data['start_date']} – {$this->data['end_date']} "
                            . "({$this->data['total_days']} hari).",
                'url'      => route('approval-cuti'),
                'icon'     => 'calendar-plus',
                'color'    => 'blue',
                'leave_id' => $this->data['leave_id'],
            ],

            'approved' => [
                'type'     => 'leave_approved',
                'title'    => 'Cuti Disetujui',
                'message'  => "Pengajuan cuti {$this->data['leave_type']} Anda pada "
                            . "{$this->data['start_date']} – {$this->data['end_date']} "
                            . "telah Disetujui.",
                'url'      => route('riwayat-cuti'),
                'icon'     => 'check-circle',
                'color'    => 'green',
                'leave_id' => $this->data['leave_id'],
            ],

            'rejected' => [
                'type'     => 'leave_rejected',
                'title'    => 'Pengajuan Cuti Ditolak',
                'message'  => "Pengajuan cuti {$this->data['leave_type']} Anda pada "
                            . "{$this->data['start_date']} – {$this->data['end_date']} "
                            . "ditolak oleh {$this->data['rejected_by']}."
                            . (!empty($this->data['reason']) ? " Alasan: {$this->data['reason']}" : ''),
                'url'      => route('riwayat-cuti'),
                'icon'     => 'x-circle',
                'color'    => 'red',
                'leave_id' => $this->data['leave_id'],
            ],

            'escalated' => [
                'type'     => 'leave_escalated',
                'title'    => 'Eskalasi Pengajuan Cuti',
                'message'  => "Pengajuan cuti {$this->data['employee_name']} "
                            . "({$this->data['leave_type']}) pada "
                            . "{$this->data['start_date']} – {$this->data['end_date']} "
                            . "telah dieskalasi menunggu persetujuan.",
                'url'      => route('approval-cuti'),
                'icon'     => 'arrow-up-circle',
                'color'    => 'yellow',
                'leave_id' => $this->data['leave_id'],
            ],

            default => [
                'type'     => 'leave_info',
                'title'   => 'Informasi Cuti',
                'message' => $this->data['message'] ?? '-',
                'url'     => route('pengajuan-cuti'),
                'icon'    => 'bell',
                'color'   => 'gray',
            ],
        };
    }

    /* ─────────────────────────────────────────────────────────
     | Mail (hanya dipakai jika $withMail = true)
     ───────────────────────────────────────────────────────── */
    public function toMail(object $notifiable): MailMessage
    {
        $db = $this->toDatabase($notifiable);

        return (new MailMessage)
            ->subject($db['title'])
            ->greeting("Halo, {$notifiable->name}!")
            ->line($db['message'])
            ->action('Lihat Detail', $db['url'])
            ->line('Terima kasih telah menggunakan sistem cuti kami.');
    }
}
