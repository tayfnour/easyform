<tr x-data="{ modalIsOpen : false }">
    <td> {{ $product->sku }} </td>
    <td> {{ $product->pro_name }} </td>
    <td> {{ $product->price }} </td>
    <td> {{ $product->catgory }} </td>
    <td><a target="_blank" href="{{ 'http://localhost/easypanel/storage/app/'.$product->image}}"><img class="img-fluid" width="50" height="50" src="{{ 'http://localhost/easypanel/storage/app/'. $product->image }}" alt="image"></a></td>    
    @if(config('easy_panel.crud.product.delete') or config('easy_panel.crud.product.update'))
        <td>

            @if(config('easy_panel.crud.product.update'))
                <a href="@route(getRouteName().'.product.update', ['product' => $product->id])" class="btn text-primary mt-1">
                    <i class="icon-pencil"></i>
                </a>
            @endif

            @if(config('easy_panel.crud.product.delete'))
                <button @click.prevent="modalIsOpen = true" class="btn text-danger mt-1">
                    <i class="icon-trash"></i>
                </button>
                <div x-show="modalIsOpen" class="cs-modal animate__animated animate__fadeIn">
                    <div class="bg-white shadow rounded p-5" @click.away="modalIsOpen = false" >
                        <h5 class="pb-2 border-bottom">{{ __('DeleteTitle', ['name' => __('Product') ]) }}</h5>
                        <p>{{ __('DeleteMessage', ['name' => __('Product') ]) }}</p>
                        <div class="mt-5 d-flex justify-content-between">
                            <a wire:click.prevent="delete" class="text-white btn btn-success shadow">{{ __('Yes, Delete it.') }}</a>
                            <a @click.prevent="modalIsOpen = false" class="text-white btn btn-danger shadow">{{ __('No, Cancel it.') }}</a>
                        </div>
                    </div>
                </div>
            @endif
        </td>
    @endif
</tr>
