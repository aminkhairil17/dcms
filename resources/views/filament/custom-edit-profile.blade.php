<x-filament-panels::page>
    <div class="space-y-6">
        <form wire:submit="save" class="space-y-6">
            {{ $this->form }}

            <div class="flex items-center justify-end gap-x-3 pt-6 mt-6 border-t border-gray-200/60">
                <x-filament::button
                    type="button"
                    color="gray"
                    outlined
                    tag="a"
                    :href="filament()->getUrl()"
                    icon="heroicon-o-arrow-left"
                >
                    Kembali
                </x-filament::button>

                <x-filament::button
                    type="submit"
                    color="primary"
                    icon="heroicon-o-check-circle"
                >
                    Simpan Perubahan
                </x-filament::button>
            </div>
        </form>
    </div>
</x-filament-panels::page>
