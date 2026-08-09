<x-sidebar-layout>
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">Edit Pelatih</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">Perbarui data pelatih ASC Academy.</p>
            </div>
        </div>

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-6 md:p-8 max-w-3xl">
            @if ($coach->photo)
                <div class="mb-6">
                    <img src="{{ Storage::url($coach->photo) }}" class="w-20 h-20 rounded-full object-cover">
                </div>
            @endif

            <form action="{{ route('admin.coaches.update', $coach) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="name" value="Nama" />
                        <x-text-input id="name" name="name" class="mt-1 block w-full" value="{{ old('name', $coach->name) }}" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="email" value="Email" />
                        <x-text-input id="email" type="email" name="email" class="mt-1 block w-full" value="{{ old('email', $coach->email) }}" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="phone" value="Nomor HP" />
                        <x-text-input id="phone" name="phone" class="mt-1 block w-full" value="{{ old('phone', $coach->phone) }}" />
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>

                    <div class="md:col-span-2">
                        <x-input-label for="address" value="Alamat" />
                        <textarea id="address" name="address" class="mt-1 block w-full bg-surface-container-lowest border-outline-variant rounded-lg px-3 py-2 shadow-sm focus:border-primary focus:ring-primary/30">{{ old('address', $coach->address) }}</textarea>
                        <x-input-error :messages="$errors->get('address')" class="mt-2" />
                    </div>

                    <div class="md:col-span-2">
                        <x-input-label for="photo" value="Ganti Foto" />
                        <input type="file" id="photo" name="photo" class="mt-1 block w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-3 py-2 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all" accept="image/*">
                        <x-input-error :messages="$errors->get('photo')" class="mt-2" />
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <x-primary-button>Update</x-primary-button>
                    <a href="{{ route('admin.coaches.index') }}" class="inline-flex items-center justify-center gap-2 border border-primary text-primary px-5 py-2.5 rounded-lg font-label-md text-label-md hover:bg-primary-container hover:text-on-primary transition-all">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-sidebar-layout>
