<?php

declare(strict_types=1);

namespace DJWeb\Framework\DBAL\Models\Entities;

use Carbon\Carbon;
use DJWeb\Framework\DBAL\Models\Model;

class MailHistory extends Model
{
    public string $table {
        get => 'mail_history';
    }

    public string $from_email {
        get => $this->from_email;
        set {
            $this->from_email = $value;
            $this->markPropertyAsChanged('from_email');
        }
    }

    public string $from_name {
        get => $this->from_name;
        set {
            $this->from_name = $value;
            $this->markPropertyAsChanged('from_name');
        }
    }

    public string $subject {
        get => $this->subject;
        set {
            $this->subject = $value;
            $this->markPropertyAsChanged('subject');
        }
    }

    public string $to_email {
        get => $this->to_email;
        set {
            $this->to_email = $value;
            $this->markPropertyAsChanged('to_email');
        }
    }

    public string $to_name {
        get => $this->to_name;
        set {
            $this->to_name = $value;
            $this->markPropertyAsChanged('to_name');
        }
    }

    public ?string $cc_email {
        get => $this->cc_email;
        set {
            $this->cc_email = $value;
            $this->markPropertyAsChanged('cc_email');
        }
    }

    public ?string $bcc_email {
        get => $this->bcc_email;
        set {
            $this->bcc_email = $value;
            $this->markPropertyAsChanged('bcc_email');
        }
    }

    public ?string $reply_to_email {
        get => $this->reply_to_email;
        set {
            $this->reply_to_email = $value;
            $this->markPropertyAsChanged('reply_to_email');
        }
    }

    public string $status {
        get => $this->status;
        set {
            $this->status = $value;
            $this->markPropertyAsChanged('status');
        }
    }

    public ?string $error {
        get => $this->error;
        set {
            $this->error = $value;
            $this->markPropertyAsChanged('error');
        }
    }

    public Carbon $created_at {
        get => $this->created_at;
        set {
            $this->created_at = $value;
            $this->markPropertyAsChanged('created_at');
        }
    }

    public Carbon $updated_at {
        get => $this->updated_at;
        set {
            $this->updated_at = $value;
            $this->markPropertyAsChanged('updated_at');
        }
    }

    /**
     * @var array<string, string>
     */
    protected array $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
