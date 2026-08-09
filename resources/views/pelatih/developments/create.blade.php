<x-sidebar-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Isi Penilaian — {{ $student->full_name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('pelatih.developments.store', [$class, $student]) }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <x-input-label for="period" value="Periode" />
                        <x-text-input id="period" name="period" class="mt-1 block w-full"
                                      placeholder="Contoh: Agustus 2026 / Paket 1" value="{{ old('period') }}" required />
                        <x-input-error :messages="$errors->get('period')" class="mt-2" />
                    </div>

                    <p class="text-xs text-gray-500">
                        @foreach (\App\Models\Development::scores() as $scoreKey => $scoreLabel){{ $loop->first ? '' : ', ' }}{{ $loop->iteration }} = {{ $scoreLabel }}@endforeach
                    </p>

                    <h3 class="font-semibold text-gray-700">Penilaian Umum</h3>

                    @foreach (\App\Models\Development::umumAspects() as $key => $label)
                        <div>
                            <x-input-label :for="$key" :value="$label" />
                            <x-assessment-score :name="$key" />
                        </div>
                    @endforeach

                    <hr>

                    <h3 class="font-semibold text-gray-700">Penilaian Aspek Khusus</h3>

                    @foreach (\App\Models\Development::khususAspects() as $key => $label)
                        <div>
                            <x-input-label :for="$key" :value="$label" />
                            <x-assessment-score :name="$key" />
                        </div>
                    @endforeach

                    <div>
                        <x-input-label for="coach_note" value="Catatan Coach" />
                        <textarea id="coach_note" name="coach_note" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" rows="3">{{ old('coach_note') }}</textarea>
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('pelatih.developments.index', $class) }}" class="px-4 py-2 bg-gray-200 rounded-md">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md">Simpan Penilaian</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-sidebar-layout>
