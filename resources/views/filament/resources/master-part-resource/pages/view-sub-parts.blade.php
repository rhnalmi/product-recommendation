<x-filament::page>
    <h2 class="text-2xl font-bold mb-4">
        Sub Parts for: {{ $this->masterPart->part_name }} ({{ $this->masterPart->part_number }})
    </h2>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-bold text-gray-700 uppercase">Sub Part Number</th>
                    <th class="px-4 py-2 text-left text-xs font-bold text-gray-700 uppercase">Sub Part Name</th>
                    <th class="px-4 py-2 text-left text-xs font-bold text-gray-700 uppercase">Price</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($this->masterPart->subParts as $subPart)
                    <tr class="odd:bg-white even:bg-gray-50">
                        <td class="px-4 py-2 text-gray-900">{{ $subPart->sub_part_number }}</td>
                        <td class="px-4 py-2 text-gray-900">{{ $subPart->sub_part_name }}</td>
                        <td class="px-4 py-2 text-gray-900">{{ $subPart->price }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-2 text-center text-gray-500">No sub parts found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-filament::page>