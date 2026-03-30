<?php

namespace App\Traits;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

trait LogsActivityTrait
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName($this->getActivityLogName())
            ->logOnlyDirty();
    }

    public function getActivityLogName(): string
    {
        return isset(static::$logName) ? static::$logName : class_basename($this);
    }
}
