<x-filament::page>
    <form wire:submit.prevent="save">
        {{ $this->form }}
        <br>
        <x-filament::button type="submit" class="mt-4">Save Changes</x-filament::button>
    </form>
</x-filament::page>
