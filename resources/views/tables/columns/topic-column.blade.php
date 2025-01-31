<div>
<x-filament::modal :close-by-clicking-away="false" :close-by-escaping="false" width="5xl" slide-over>
    <x-slot name="trigger">
        <x-filament::button>
            Open modal
        </x-filament::button>
    </x-slot>
 
    <x-slot name="heading">
        Modal heading
    </x-slot>
 
    <x-slot name="description">
        Modal description
    </x-slot>

    <x-filament::dropdown.list>
        <x-filament::dropdown.list.item wire:click="openViewModal">
            View
        </x-filament::dropdown.list.item>
        
        <x-filament::dropdown.list.item wire:click="openEditModal">
            Edit
        </x-filament::dropdown.list.item>
        
        <x-filament::dropdown.list.item wire:click="openDeleteModal">
            Delete
        </x-filament::dropdown.list.item>
    </x-filament::dropdown.list>
</x-filament::modal>
</div>
