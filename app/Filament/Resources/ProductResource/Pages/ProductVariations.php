<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Enums\ProductVariationTypeEnum;
use App\Filament\Resources\ProductResource;
use App\Models\Product;
use Filament\Actions;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductVariations extends EditRecord
{
    protected static string $resource = ProductResource::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public function getTitle(): string|Htmlable
    {
        return __('Variations');
    }

    public static function getNavigationLabel(): string
    {
        return __('Variations');
    }

    public function form(Form $form): Form
    {
        /** @var Product $productRecord */
        $productRecord = $this->record;
        $types = $productRecord->variationTypes;
        $fields = [];
        foreach($types as $type) {
            $fields[] = TextInput::make('variation_type_' . ($type->id) . '.id')
                ->hidden();
            $fields[] = TextInput::make('variation_type_' . ($type->id) . '.name')
                ->label($type->name);
        }
        return $form
            ->schema([
                Repeater::make('variations')
                    ->label(false)
                    ->schema([
                        Section::make()
                            ->schema($fields)
                            ->columns(3),
                        TextInput::make('quantity')
                            ->label('Quantity')
                            ->numeric()
                            ->default($productRecord->quantity),
                        TextInput::make('price')
                            ->label('Price')
                            ->numeric()
                            ->default($productRecord->price),
                    ])
                    ->collapsible()
                    ->addable(false)
                    ->defaultItems(1)
                    ->columns(2)
                    ->columnSpan(2)
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        /** @var Product $product */
        $product = $this->record;
        $variations = $product->variations->toArray();
        $data['variations'] = $this->mergeCartesianWithExisting($product->variationTypes, $variations);
        return $data;
    }

    private function mergeCartesianWithExisting($variationTypes, $existingData): array
    {
        /** @var Product $productRecord */
        $productRecord = $this->record;
        $defaultQuantity = $productRecord->quantity;
        $defaultPrice = $productRecord->price;
        $cartesianProduct = $this->cartesianProduct($variationTypes, $defaultQuantity, $defaultPrice);
        $mergedResult = [];

        foreach($cartesianProduct as $product) {
            // Extract option IDs from the current product combination as an array
            // $optionIds = collect($product)->pluck('id')->toArray();
            // $optionIds = collect($product)->filter(function($value, $key) {
            //     return Str::startsWith($key, 'variation_type_');
            // })->toArray();
            $optionIds = collect($product)
                ->filter(fn($value, $key) => Str::startsWith($key, 'variation_type_'))
                ->map(fn($option) => $option['id'])
                ->values()
                ->toArray();

            // Find the existing product variation that matches the current product combination
            $match = array_filter($existingData, function($existingOption) use ($optionIds) {
                // return count(array_diff($existingOptions, $optionIds)) === 0;
                return $existingOption['variation_type_option_ids'] == $optionIds;
            });

            // If match is found, override quantity and price
            if(!empty($match)) {
                $existingEntry = reset($match);
                $product['id'] = $existingEntry['id'];
                $product['quantity'] = $existingEntry['quantity'];
                $product['price'] = $existingEntry['price'];
            } else {
                // Set default quantity and price if no match
                $product['quantity'] = $defaultQuantity;
                $product['price'] = $defaultPrice;
            }

            $mergedResult[] = $product;
        }

        return $mergedResult;
    }

    private function cartesianProduct($variationTypes, $defaultQuantity = null, $defaultPrice = null): array
    {
        $result = [[]];
        foreach($variationTypes as $index => $variationType) {
            $temp = [];

            foreach($variationType->options as $option) {
                // Add the current option to all existing combinations
                foreach($result as $combination) {
                    $newCombination = $combination + [
                            'variation_type_' . ($variationType->id) => [
                                'id' => $option->id,
                                'name' => $option->name,
                                'label' => $variationType->name,
                            ]
                        ];

                    $temp[] = $newCombination;
                }
            }

            $result = $temp;
        }

        // Add quantity and price to each combination
        foreach($result as $combination) {
            if(count($combination) === count($variationTypes)) {
                $combination['quantity'] = $defaultQuantity;
                $combination['price'] = $defaultPrice;

            }
        }

        return $result;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Initialize an array to hold the formatted data
        $formattedData = [];
        foreach($data['variations'] as $option) {
            $variationTypeOptionIds = [];
            foreach($this->record->variationTypes as $variationType) {
                $variationTypeOptionIds[] = $option['variation_type_' . $variationType->id]['id'];
            }

            $quantity = $option['quantity'];
            $price = $option['price'];

            // Prepare the data structure for the formatted data
            $formattedData[] = [
                'id' => $option['id'],
                'variation_type_option_ids' => $variationTypeOptionIds,
                'quantity' => $quantity,
                'price' => $price,
            ];
        }

        $data['variations'] = $formattedData;
        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $variations = $data['variations'];
        unset($data['variations']);

        $variations = collect($variations)->map(fn($variation) => [
            'id' => $variation['id'],
            'variation_type_option_ids' => json_encode($variation['variation_type_option_ids']),
            'quantity' => $variation['quantity'],
            'price' => $variation['price'],
        ])->toArray();
//        $record->variations()->delete();
        // $record->variations()->createMany($variations);
        // $record->variations()->upsert($variations, ['variation_type_option_ids'], ['quantity', 'price']);
        $record->variations()->upsert($variations, ['id'], ['variation_type_option_ids', 'quantity', 'price']);

        return parent::handleRecordUpdate($record, $data);
    }
}
