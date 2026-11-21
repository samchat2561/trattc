<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\EditProfile as PagesEditProfile;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class EditProfile extends PagesEditProfile
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getAvatarFormComponent(),
                $this->getNameFormComponent(),
                $this->getTaglineFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
                $this->getCurrentPasswordFormComponent(),
                $this->getBioFormComponent(),
            ]);
    }

    public function getAvatarFormComponent()
    {
        return FileUpload::make('profile_photo')
            ->label("Profile Photo")
            ->avatar()
            ->disk('public');
    }

    public function getTaglineFormComponent()
    {
        return TextInput::make('tagline')
            ->nullable();
    }

    public function getBioFormComponent()
    {
        return Textarea::make('bio')
            ->rows(4)
            ->nullable();
    }
}
