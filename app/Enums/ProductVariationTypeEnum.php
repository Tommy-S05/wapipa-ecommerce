<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ProductVariationTypeEnum: string implements HasLabel
{
    case Select = 'Select';
    case Radio = 'Radio';
    case Image = 'Image';

//    public static function labels(): array
//    {
//        return [
//            self::Select->value => __('Select'),
//            self::Radio->value => __('Radio'),
//            self::Image->value => __('Image'),
//        ];
//    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Select => __('Select'),
            self::Radio => __('Radio'),
            self::Image => __('Image'),
        };
    }
}
