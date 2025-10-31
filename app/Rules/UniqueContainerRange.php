<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Container;

class UniqueContainerRange implements ValidationRule
{
    protected $prefix;
    protected $from;
    protected $to;
    protected $sizeId;

    public function __construct($prefix, $from, $to, $sizeId = null)
    {
        $this->prefix = $prefix ?? '';
        $this->from = (int) $from;
        $this->to = (int) $to;
        $this->sizeId = $sizeId;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->from > $this->to) {
            $fail(__('validation.range_invalid'));
            return;
        }

        $codes = collect(range($this->from, $this->to))
            ->map(fn($i) => $this->prefix . $i)
            ->toArray();

            
        $query = Container::whereIn('code', $codes);

        // if ($this->sizeId) {
        //     $query->where('size_id', $this->sizeId);
        // }

        $existingCodes = $query->pluck('code');
        if ($existingCodes->isNotEmpty()) {
            $fail(
                __('validation.container_codes_exist', [
                    'codes' => $existingCodes->take(5)->join(', ') . 
                        ($existingCodes->count() > 5 ? ' ...' : '')
                ])
            );
        }
    }
}
