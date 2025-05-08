<div>
    <div class="relative">
        <input id="search_query" 
               name="search_query" 
               type="text"
               wire:model.debounce.500ms="search_query"
               wire:keydown.arrow-down="highlightNext" 
               wire:keydown.arrow-up="highlightPrevious" 
               wire:keydown.enter="selectItem" 
               placeholder="Search" 
               class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
    
        <input wire:model="search_query" type="text" name="search_query" id="search_query" hidden>
    
        @if (!empty($search_query) && empty($selected_item_id))
            <ul class="list-group absolute z-10 bg-white border w-full mt-1 rounded shadow">
                @if (!empty($search_results))
                    @foreach ($search_results as $index => $item)
                        <li>
                            <a class="py-2 px-3 list-group-item hover:bg-slate-100 cursor-pointer @if($highlighted_index === $index) bg-blue-100 @endif" 
                               wire:click.prevent="selectItem" 
                               data-index="{{ $index }}">
                                {{ $item->{$search_field} }} <!-- Display the dynamic search field -->
                            </a>
                        </li>
                    @endforeach
                @else
                    <div class="py-2 px-3 hover:bg-slate-100 w-full cursor-pointer">No data!</div>
                @endif
            </ul>
        @endif
    </div>
    
</div>
