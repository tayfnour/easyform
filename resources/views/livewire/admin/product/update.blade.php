<div class="card">
    <div class="card-header p-0">
        <h3 class="card-title">{{ __('UpdateTitle', ['name' => __('Product') ]) }}</h3>
        <div class="px-2 mt-4">
            <ul class="breadcrumb mt-3 py-3 px-4 rounded" style="background-color: #e9ecef!important;">
                <li class="breadcrumb-item"><a href="@route(getRouteName().'.home')" class="text-decoration-none">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="@route(getRouteName().'.product.read')" class="text-decoration-none">{{ __(\Illuminate\Support\Str::plural('Product')) }}</a></li>
                <li class="breadcrumb-item active">{{ __('Update') }}</li>
            </ul>
        </div>
    </div>

    <form class="form-horizontal" wire:submit.prevent="update" enctype="multipart/form-data">

        <div class="card-body">

            
            <!-- Id Input -->
            <div class='form-group'>
                <label for='inputid' class='col-sm-2 control-label'> {{ __('Id') }}</label>
                <input type='text' wire:model.lazy='id' class="form-control @error('id') is-invalid @enderror" id='inputid'>
                @error('id') <div class='invalid-feedback'>{{ $message }}</div> @enderror
            </div>
            
            <!-- Pro_name Input -->
            <div class='form-group'>
                <label for='inputpro_name' class='col-sm-2 control-label'> {{ __('Pro_name') }}</label>
                <input type='text' wire:model.lazy='pro_name' class="form-control @error('pro_name') is-invalid @enderror" id='inputpro_name'>
                @error('pro_name') <div class='invalid-feedback'>{{ $message }}</div> @enderror
            </div>
            
            <!-- Price Input -->
            <div class='form-group'>
                <label for='inputprice' class='col-sm-2 control-label'> {{ __('Price') }}</label>
                <input type='text' wire:model.lazy='price' class="form-control @error('price') is-invalid @enderror" id='inputprice'>
                @error('price') <div class='invalid-feedback'>{{ $message }}</div> @enderror
            </div>
            
            <!-- Catgory Input -->
            <div class='form-group'>
                <label for='inputcatgory' class='col-sm-2 control-label'> {{ __('Catgory') }}</label>
                <input type='text' wire:model.lazy='catgory' class="form-control @error('catgory') is-invalid @enderror" id='inputcatgory'>
                @error('catgory') <div class='invalid-feedback'>{{ $message }}</div> @enderror
            </div>
            
            <!-- Sku Input -->
            <div class='form-group'>
                <label for='inputsku' class='col-sm-2 control-label'> {{ __('Sku') }}</label>
                <input type='text' wire:model.lazy='sku' class="form-control @error('sku') is-invalid @enderror" id='inputsku'>
                @error('sku') <div class='invalid-feedback'>{{ $message }}</div> @enderror
            </div>
            
            <!-- Image Input -->
            <div class='form-group'>
                <label for='inputimage' class='col-sm-2 control-label'> {{ __('Image') }}</label>
                <input type='file' wire:model='image' class="form-control-file @error('image') is-invalid @enderror" id='inputimage'>
                @if($image and !$errors->has('image') and $image instanceof \Livewire\TemporaryUploadedFile and (in_array( $image->guessExtension(), ['png', 'jpg', 'gif', 'jpeg'])))
                    <a href="{{ $image->temporaryUrl() }}"><img width="200" height="200" class="img-fluid shadow" src="{{ $image->temporaryUrl() }}" alt=""></a>
                @endif
                @error('image') <div class='invalid-feedback'>{{ $message }}</div> @enderror
            </div>
            

        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-info ml-4">{{ __('Update') }}</button>
            <a href="@route(getRouteName().'.product.read')" class="btn btn-default float-left">{{ __('Cancel') }}</a>
        </div>
    </form>
</div>
