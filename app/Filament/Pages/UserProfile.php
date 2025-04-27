<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Filament\Notifications\Notification;

class UserProfile extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel = 'My Profile';
    protected static ?string $title = 'My Profile';
    protected static ?string $slug = 'profile';
    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 80;

    protected static string $view = 'filament.pages.user-profile';

    public ?array $data = [];

    public function mount()
    {
        $user = Auth::user();
        $this->form->fill([
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Full Name')
                    ->disabled() // Make readonly
                    ->required(),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->disabled() // Make readonly
                    ->required(),

                Forms\Components\TextInput::make('password')
                    ->password()
                    ->label('New Password')
                    ->nullable()
                    ->minLength(8) // Ensure password is secure
                    ->dehydrated(fn ($state) => filled($state)) // Only update if provided
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state)), // Hash the password

                Forms\Components\TextInput::make('password_confirmation')
                    ->password()
                    ->label('Confirm New Password')
                    ->same('password')
                    ->nullable(),
            ])
            ->statePath('data'); // Correct state binding
    }

    public function save()
    {
        $user = Auth::user();

        if (!empty($this->data['password'])) {
            // Update and encrypt password
            $user->update([
                'password' => Hash::make($this->data['password']),
            ]);

            // Show success notification
            Notification::make()
                ->title('Password updated successfully! Please log in again.')
                ->success()
                ->send();

            Auth::logout(); // Log out user
            session()->invalidate(); // Invalidate session
            session()->regenerateToken(); // Regenerate CSRF token

            return redirect()->to('/login'); // Correct Livewire redirect
        }

        // If no password was provided
        Notification::make()
            ->title('No new password provided.')
            ->warning()
            ->send();
    }
}
