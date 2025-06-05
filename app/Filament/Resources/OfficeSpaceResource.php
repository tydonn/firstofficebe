<?php

namespace App\Filament\Resources;
//JANGAN LUPA IMPORT!!
use App\Filament\Resources\OfficeSpaceResource\Pages;
use App\Filament\Resources\OfficeSpaceResource\RelationManagers;
use App\Models\OfficeSpace;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\SelectFilter;

class OfficeSpaceResource extends Resource
{
    protected static ?string $model = OfficeSpace::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //Membuat tampilan input pada filament
                Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),

                Forms\Components\TextInput::make('address')
                ->required()
                ->maxLength(255),

                Forms\Components\FileUpload::make('thumbnail')
                ->Image()
                ->required(),

                Forms\Components\Textarea::make('about')
                ->required()
                ->rows(10)
                ->maxLength(255),

                Forms\Components\Repeater::make('photos')//'photos' adalah kelas pada model OfficeSpace
                //Repeater berfungsi untuk menambahkan data lebih dari satu
                ->relationship('photos')//membuat relasi pada tabel OfficeSpacePhoto menggunakan kelas photos pada model OfficeSpace
                ->schema([
                    Forms\Components\FileUpload::make('photo')//'photo' adalah nama kolom pada tabel OfficeSpacePhoto
                    ->Image()
                    ->required(),
                ]),

                Forms\Components\Repeater::make('benefits')
                ->relationship('benefits')
                ->schema([
                    Forms\Components\TextInput::make('name')
                    ->required(),
                ]),

                Forms\Components\Select::make('city_id')
                ->relationship('city', 'name')
                ->searchable()
                ->preload()
                ->required(),

                Forms\Components\TextInput::make('price')
                ->required()
                ->numeric()
                ->prefix('IDR'),

                Forms\Components\TextInput::make('duration')
                ->required()
                ->numeric()
                ->prefix('Days'),

                Forms\Components\Select::make('is_open')
                ->options([
                    true => 'Open',
                    false => 'Not Open',
                ])
                ->required(),

                Forms\Components\Select::make('is_full_booked')
                ->options([
                    true => 'Not Available',
                    false => 'Available',
                ])
                ->required(),
                    

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //Menampilkan tabel data
                Tables\Columns\TextColumn::make('name')
                ->searchable(),

                Tables\Columns\ImageColumn::make('thumbnail'),

                Tables\Columns\TextColumn::make('city.name'),

                Tables\Columns\IconColumn::make('is_full_booked')
                ->boolean()
                ->trueColor('danger')
                ->falseColor('succes')
                ->trueIcon('heroicon-o-x-circle')
                ->falseIcon('heroicon-o-check-circle')
                ->label('Available'),

            ])
            ->filters([
                //Membuat filter data
                SelectFilter::make('city_id')
                ->label('City')
                ->relationship('city', 'name'),
            ])
            ->actions([
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
            'index' => Pages\ListOfficeSpaces::route('/'),
            'create' => Pages\CreateOfficeSpace::route('/create'),
            'edit' => Pages\EditOfficeSpace::route('/{record}/edit'),
        ];
    }
}
