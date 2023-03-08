<x-main-layout>
    <x-splade-table :for="$data">
        <x-slot name="head">
            <thead>
                <tr>
                    @foreach($data->columns() as $column)
                        <th>{{ $column->label }}</th>
                    @endforeach
                </tr>
            </thead>
        </x-slot>

        <x-slot name="body">
            <tbody>
                @foreach($data->resource as $user)
                    <tr>
                        ...
                    </tr>
                @endforeach
            </tbody>
        </x-slot>
    </x-splade-table>
</x-main-layout>
