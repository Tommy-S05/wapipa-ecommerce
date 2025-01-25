<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ProductStatusEnum: string implements HasLabel, HasColor
{
    case Draft = 'draft';
    case Published = 'published';

//    public static function labels(): array
//    {
//        return [
//            self::Draft->value => __('Draft'),
//            self::Published->value => __('Published'),
//        ];
//    }

//    public static function colors(): array
//    {
//        return [
//            'gray' => self::Draft->value,
//            'success' => self::Published->value,
//        ];
//    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Draft => __('Draft'),
            self::Published => __('Published'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Published => 'success',
        };
    }
}
