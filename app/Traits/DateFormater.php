<?php


namespace App\Traits;

use Carbon\Carbon;

trait DateFormater
{
    protected array $formats = ['d/m/Y', 'd.m.Y', 'Y-m-d'];
    public function formatDate($value)
    {
        foreach ($this->formats as $format) {
            try {
                return Carbon::createFromFormat($format, $value)->format('Y-m-d');
            } catch (\Exception $e) {
                continue;
            }
        }
        throw new \InvalidArgumentException("Invalid date format for value: $value");
    }

    public function getDateFromFormat(string $format, $value)
    {
        if(!in_array($format, $this->formats)){
            throw new \InvalidArgumentException("Invalid date format for value");
        }
        if(empty($value)){
            return null;
        }
        return Carbon::parse($value)->format($format);
    }

}
