<x-filament-panels::page>
    <x-filament::tabs label="Content tabs">
        <x-filament::tabs.item 
            :active="$this->activeTab === 'transactions'"
            wire:click="switchTab('transactions')"
        >
            Transactions
        </x-filament::tabs.item>
    
        <x-filament::tabs.item 
            :active="$this->activeTab === 'products'"
            wire:click="switchTab('products')"
        >
            Products
        </x-filament::tabs.item>
    </x-filament::tabs>
    
    <div class="mt-4">
        {{ $this->table }}
    </div>
</x-filament-panels::page>