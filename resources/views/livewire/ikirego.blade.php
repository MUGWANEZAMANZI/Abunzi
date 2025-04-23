<div>
    <header class="bg-blue-300">
        <div>Abunzi</div>
        <div>Tanga ikirego cyawe</div>
    </header>
    <form wire:submit="save">
        <div class="">
            <div>
        <label>Umutwe<input type="text" name="title" wire:model="title"></label>
            </div>
        <div>
            @error('title')
                {{$message}}
            @enderror
        </div>
        <div>
        <label>Icyabaye<textarea></textarea name="content" wire:model="content"></label>
        </div>
        <div>
            @error('title')
                {{$message}}
            @enderror
        </div>
        <div>
        <label>Ndarega</label><input type="text" name="offender" wire:model="offender"></label>
        </div>
        <div>
            @error('title')
                {{$message}}
            @enderror
        </div>
        <div><input type="submit" class="block mt-2" value="Ohereza">
        </div>
    </form>


</div>
