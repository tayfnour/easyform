<div class='{{ $opts[$col]['bootstrap'] }}'>
    @if ($type == 'start' || $type == 'oneRow')
        <label class='p-2' wire:click="currrentColumnOptions('{{ $opts[$col]["formName"]}}','{{$col}}')"
            style="cursor:pointer;font-size: 15px">{{ $opts[$col]['arabic_name'] }}</label>
    @endif               
    <input 
        wire:model.debounce.2000ms='colArr.{{ $k }}.{{ $opts[$col]['colName'] }}'
        class='form-control'>
    @error('colArr.' . $k . '.' . $col)
        <span class="error" style="text-align:left">
            {{ preg_replace('/[col ar\.]+\d+\./', ' ', $message) }}
        </span>
    @enderror
</div>
