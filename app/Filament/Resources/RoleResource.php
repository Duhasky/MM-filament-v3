<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\{Pages};
use App\Models\Ability;
use App\Models\{Role};
use App\Policies\RolePolicy;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\{Forms, Tables};
use Illuminate\Support\Facades\Auth;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(100),
                Forms\Components\Select::make('hierarchy')
                    ->options(
                        function () {
                            $user = Auth::user();

                            // Carregar as roles do usuário logado com a relação já carregada
                            $roles = $user->roles->pluck('hierarchy'); //@phpstan-ignore-line

                            // Verificar se o usuário tem roles
                            if ($roles->isNotEmpty()) {
                                // Pega o menor valor de hierarquia das roles
                                $minHierarchy = $roles->min();

                                // Gera o intervalo do menor nível de hierarquia até 100
                                return collect(range($minHierarchy, 100))->mapWithKeys(fn ($value) => [$value => $value]);
                            }

                            // Se o usuário não tiver roles, retorne um array vazio
                            return [];
                        }
                    )
                    ->searchable()
                    ->optionsLimit(5),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('hierarchy')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('add-abilities')
                    ->requiresConfirmation()
                    ->modalHeading(__('Abilities'))
                    ->modalWidth('full')
                    ->modalDescription(null)
                    ->modalIcon(null)
                    ->label('Abilities')
                    ->translateLabel()
                    ->icon('heroicon-o-shield-check')
                    ->iconSize('md')
                    ->color('success')
                    ->fillForm(function ($record) {
                        $abilities = Role::find($record->id)->abilities->pluck('id')->toArray(); //@phpstan-ignore-line

                        return [
                            'abilities' => $abilities,
                        ];
                    })
                    ->form([
                        Forms\Components\ToggleButtons::make('abilities')
                            ->options(
                                function () {
                                    // Carregar as habilidades com o id e nome
                                    $abilities = Ability::query()->orderBy('id')->pluck('name', 'id')->toArray();

                                    // Traduzir os nomes das habilidades usando os arquivos de tradução
                                    return collect($abilities)->mapWithKeys(function ($name, $id) {
                                        // Retornar a chave como o id e o valor como o nome traduzido
                                        return [$id => __($name)];
                                    })->toArray();
                                }
                            )
                            ->multiple()
                            ->gridDirection('row')
                            ->columns([
                                'sm'  => 3,
                                'xl'  => 6,
                                '2xl' => 8,
                            ]),
                    ])
                    ->action(function (array $data, Role $role) {
                        $role->abilities()->sync($data['abilities']); //@phpstan-ignore-line
                    })
                    ->after(function () {
                        Notification::make()
                            ->success()
                            ->title(__('Abilities updated'))
                            ->send();
                    })
                    ->authorize('addAbilities', RolePolicy::class),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageRoles::route('/'),
        ];
    }
}
