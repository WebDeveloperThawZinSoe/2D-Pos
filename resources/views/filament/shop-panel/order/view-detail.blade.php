<div class="space-y-6">

    {{-- Order Main Info --}}
    <div class="grid grid-cols-2 gap-4">
    <div>
            <div class="text-gray-500 text-sm">Order Number</div>
            <div class="font-semibold">{{ $record->order_number }}</div>
        </div>
        <div>
            <div class="text-gray-500 text-sm">Date</div>
            <div class="font-semibold">{{ $record->date }}</div>
        </div>
        <div>
            <div class="text-gray-500 text-sm">တင်ချိန်</div>
            <div class="font-semibold">{{ $record->section }}</div>
        </div>
        <div>
            <div class="text-gray-500 text-sm">ဒိုင် အမည်</div>
            <div class="font-semibold">{{ $record->manager->name ?? '-' }}</div>
        </div>
        <div>
            <div class="text-gray-500 text-sm">ထိုးသား</div>
            <div class="font-semibold">{{ $record->user->name ?? '-' }}</div>
        </div>
        <div>
            <div class="text-gray-500 text-sm">တင်ကွက်များ</div>
            <div class="font-semibold">{{ $record->order_type }}</div>
        </div>
        <div class="col-span-2">
            <div class="text-gray-500 text-sm">စုစုပေါင်းတင်‌ငွေ</div>
            <div class="font-semibold">{{ number_format($record->price) }} MMK</div>
        </div>
    </div>

    {{-- Order Details List --}}
    <div>
        <h3 class="text-lg font-bold">Order Detail List</h3>

        @if ($record->orderDetails->isNotEmpty())
            <div class="overflow-x-auto mt-2">
                <table class="w-full text-sm text-left text-gray-700 border rounded-lg overflow-hidden">
                    <thead class="text-xs uppercase bg-gray-100 text-gray-600">
                        <tr>
                            <th scope="col" class="px-4 py-2 border-b">#</th>
                            <th scope="col" class="px-4 py-2 border-b">Number</th>
                            <th scope="col" class="px-4 py-2 border-b">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($record->orderDetails as $index => $detail)
                            <tr class="bg-white hover:bg-gray-50">
                                <td class="px-4 py-2 border-b">{{ $index + 1 }}</td>
                                <td class="px-4 py-2 border-b">{{ $detail->number }}</td>
                                <td class="px-4 py-2 border-b">{{ number_format($detail->price) }} MMK</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-gray-400 italic mt-2">No details found for this order.</div>
        @endif
    </div>

</div>
