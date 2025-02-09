<?php

namespace App\Enums;

enum TaskStatus: string
{
    case Pending = 'pending';
    case InProgress = 'in_progress';
    case Completed = 'completed';
    /**
     * Get the translated name of the RequestWithdrawing Status.
     *
     * @return string
     */
    public function translated(): string
    {
        return __('messages.task_status.' . $this->value);
    }
    public function rules()
    {
        return [
            'status' => ['required', 'in:' . implode(',', TaskStatus::values())],
        ];
    }
    /**
     * Get all enum values.
     *
     * @return array
    */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
