<div style="display: flex" class="el">

    <div style="flex:1" class="el">
        <div style="padding:5px; min-height:600px ;border:1px solid #aaa " class="el">
            <p class="el">
                Past Here : <kbd class="el">CTRL</kbd> + <kbd class="el">V</kbd>.
            </p>
            <div style="padding:5px;" class="el">
                <input wire:model="pastImage" type="file" id="document_attachment_doc" class="el">
            </div>

             @if ($pastImage) 
            <img src="{{ $pastImage->temporaryUrl() }}" style="width:100%" class="el"> @error('pastImage') <span class="error el" style="padding:5px">{{ $message }}</span>
            @enderror
            <input type="button" wire:click="savePost()" value="save" class="el">
            @endif
        </div>

    </div>

    <div style="flex:3 ;padding:5px; height:600px ; border:1px solid #aaa " class="el">
        <div class="el">
            <input wire:model="postTitle" class="form-control el" style="height: 20%">
        </div>

        <div class="el">
            <textarea wire:model="postbody" class="form-control el" style="overflow-y:auto ;height: 530px;margin-top: 10px; margin-bottom: 0px;"></textarea>

        </div>

    </div>
</div>