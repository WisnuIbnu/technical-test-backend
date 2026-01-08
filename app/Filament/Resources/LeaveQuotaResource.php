<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaveQuotaResource\Pages;
use App\Filament\Resources\LeaveQuotaResource\RelationManagers;
use App\Models\LeaveQuota;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LeaveQuotaResource extends Resource
{
    protected static ?string $model = LeaveQuota::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Kelola Kouta Cuti';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                ->relationship('user', 'name')
                ->required()
                ->searchable()
                ->preload(),
            Forms\Components\TextInput::make('year')
                ->numeric()
                ->default(date('Y'))
                ->required(),
            Forms\Components\TextInput::make('total_quota')
                ->label('Total Jatah Cuti')
                ->numeric()
                ->default(12)
                ->required(),
            Forms\Components\TextInput::make('used_quota')
                ->label('Kuota Terpakai')
                ->numeric()
                ->default(0)
                ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Karyawan')->searchable(),
            Tables\Columns\TextColumn::make('year')->label('Tahun'),
            Tables\Columns\TextColumn::make('total_quota')->label('Total Jatah'),
            Tables\Columns\TextColumn::make('used_quota')->label('Terpakai'),
            Tables\Columns\TextColumn::make('remaining')
                ->label('Sisa Cuti')
                ->getStateUsing(fn ($record) => $record->total_quota - $record->used_quota)
                ->badge()
                ->color('success'),
            ])
            ->filters([
                //
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
            'index' => Pages\ListLeaveQuotas::route('/'),
            'create' => Pages\CreateLeaveQuota::route('/create'),
            'edit' => Pages\EditLeaveQuota::route('/{record}/edit'),
        ];
    }
}
