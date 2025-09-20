<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Schemas\UserForm;
use App\Filament\Resources\Users\Tables\UsersTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Manajemen User';

    public static function canViewAny(): bool
    {
        return auth()->user()->role === 'admin';
    }


    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            FileUpload::make('logo')
                ->label('Logo Toko')
                ->image()
                ->required(),

            TextInput::make('name')
                ->label('Nama Toko')
                ->required(),

            TextInput::make('username')
                ->label('Username')
                ->hint('Minimal 5 karakter tidak boleh ada spasi')
                ->minLength(5)
                ->regex('/^\S*$/')
                ->unique(ignoreRecord: true)
                ->required(),


            TextInput::make('email')
                ->label('Email')
                ->email()
                ->unique(ignoreRecord: true)
                ->required(),

            TextInput::make('password')
                ->label('Password')
                ->password()
                ->required(fn ($context) => $context === 'create')
                ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null),

            Select::make('role')
                ->label('Role')
                ->options([
                    'admin' => 'Admin',
                    'store' => 'Toko'
                ])
                ->required(),
        ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')
                ->label('Logo Toko'),
                TextColumn::make('name')
                ->label('Nama Toko'),
                TextColumn::make('username')
                ->label('Username'),
                TextColumn::make('email')
                ->label('Email'),
                TextColumn::make("role")
                ->label('Role'),
                TextColumn::make('created_at')->dateTime()
                ->label('Dibuat Pada'),
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
        ]);
            // ->bulkActions(UsersTable::getBulkActions());
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
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
