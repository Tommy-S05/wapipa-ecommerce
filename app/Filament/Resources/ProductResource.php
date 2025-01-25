<?php

namespace App\Filament\Resources;

use App\Enums\ProductStatusEnum;
use App\Enums\RolesEnum;
use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-s-queue-list';
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::End;
    public static function getNavigationLabel(): string
    {
        return __('Products');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->createdBy();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label(__('labels.title'))
                            ->live(onBlur: true)
                            ->required()
                            ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                                if (($get('slug') ?? '') !== Str::slug($old)) {
                                    return;
                                }

                                $set('slug', Str::slug($state));
                            }),
//                    ->afterStateUpdated(fn(Set $set, $state) => $set('slug', Str::slug($state)))
                        Forms\Components\TextInput::make('slug')
                            ->unique(ignoreRecord: true)
//                    ->dehydrated()
                            ->required(),
                        Forms\Components\Select::make('department_id')
                            ->label(__('labels.department'))
                            ->relationship('department', 'name')
                            ->required()
                            ->preload()
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(fn(Set $set) => $set('category_id', null)),

                        Forms\Components\Select::make('category_id')
                            ->label(__('labels.category'))
                            ->relationship(
                                name: 'category',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn(Get $get, Builder $query) => $query->where('department_id', $get('department_id'))
                            )
//                                modifyQueryUsing: function (Builder $query, callable $get) {
//                                    // Modify the category query based on the selected department
//                                    $departmentId = $get('department_id'); // Get selected department
//                                    if ($departmentId) {
//                                        $query->where('department_id', $departmentId);
//                                    }
//                                }
//                            )
                            ->required()
                            ->preload()
                            ->searchable()
                    ]),

                Forms\Components\RichEditor::make('description')
                    ->label(__('labels.description'))
                    ->required()
                    ->toolbarButtons([
                        'blockquote',
                        'bold',
                        'bulletList',
                        'h2',
                        'h3',
                        'italic',
                        'link',
                        'orderedList',
                        'redo',
                        'strike',
                        'underline',
                        'undo',
                    ])
                    ->columnSpan(2),

                Forms\Components\TextInput::make('price')
                    ->label(__('labels.price'))
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('quantity')
                    ->label(__('labels.quantity'))
                    ->integer(),
                Forms\Components\Select::make('status')
                    ->label(__('labels.status'))
                    ->options(ProductStatusEnum::class)
                    ->default(ProductStatusEnum::Draft->value)
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')
                    ->label(__('labels.image'))
                    ->collection('images')
                    ->limit(1)
                    ->conversion('thumb'),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('labels.title'))
                    ->sortable()
                    ->words(10)
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('labels.status'))
                    ->badge(),
                Tables\Columns\TextColumn::make('department.name')
                    ->label(__('labels.department'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label(__('labels.category'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('labels.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('labels.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('labels.status'))
                    ->options(ProductStatusEnum::class),
                Tables\Filters\SelectFilter::make('department_id')
                    ->label(__('labels.department'))
                    ->relationship('department', 'name')
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
            'images' => Pages\ProductImages::route('/{record}/images'),
            'variation-types' => Pages\ProductVariationTypes::route('/{record}/variation-types'),
            'variation' => Pages\ProductVariations::route('/{record}/variations'),
        ];
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\EditProduct::class,
            Pages\ProductImages::class,
            Pages\ProductVariationTypes::class,
            Pages\ProductVariations::class,
        ]);
    }

    public static function canViewAny(): bool
    {
        /** @var User $user */
        $user = Filament::auth()->user();
        return $user && $user->hasRole(RolesEnum::Vendor->value);
    }
}
