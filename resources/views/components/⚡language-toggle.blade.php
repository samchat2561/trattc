<?php

use Livewire\Component;
use Illuminate\Support\Facades\{App, Session, Request};

new class extends Component {
    public string $currentLanguage;
    public array $availableLanguages;

    public function mount(): void
    {
        // ดึง Locale ปัจจุบัน หรือค่า Default
        $this->currentLanguage = App::getLocale();

        // ดึงรายการภาษาที่รองรับจาก config/languages.php
        $this->availableLanguages = config('languages', []);
    }

    public function switchLanguage(string $locale): void
    {
        $locale = trim($locale);

        if (array_key_exists($locale, $this->availableLanguages)) {
            // บันทึกภาษาลงใน Session
            Session::put('locale', $locale);
            App::setLocale($locale);

            // รีเฟรชหน้าเดิมเพื่อให้ Middleware ทำงานและเปลี่ยนภาษา
            $this->redirect(Request::header('Referer'), navigate: false);
        }
    }
};
?>

<x-filament::dropdown>
    <x-slot name="trigger">
        <x-filament::button color="gray" outlined icon="heroicon-m-globe-alt">
            <span>{{ $availableLanguages[$this->currentLanguage]['name'] ?? $this->currentLanguage }}</span>
        </x-filament::button>
    </x-slot>

    <x-filament::dropdown.list>
        @foreach ($availableLanguages as $locale => $language)
            <x-filament::dropdown.list.item wire:click="switchLanguage('{{ $locale }}')">
                {{ $language['name'] }}
            </x-filament::dropdown.list.item>
        @endforeach
    </x-filament::dropdown.list>

</x-filament::dropdown>
