@extends('layouts.app')
@section('content')
 <!-- Main Content -->
 <div class="p-8">
    <div class="bg-white rounded-lg p-6 shadow-sm">
        <div class="flex justify-between items-center p-4 border-b">
            <h4 class="font-medium text-gray-700">Stock Entry</h4>
            <div class="flex space-x-2">
              <button class="bg-blue-600 text-white py-1 px-3 rounded text-sm hover:bg-blue-700">
                <a href="{{route('stock.index')}}">
                  <i class="fa fa-arrow-circle-left"></i> Back
                </a>
              </button>
            </div>
        </div>
        <!-- Form -->
        <form action="{{ route('stock.store') }}" method="POST" class="mt-4">
            @csrf
            <div class="p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Product -->
                    <div class="mb-4">
                        <label for="product_id" class="block text-sm font-medium text-gray-700 mb-1">Product</label>
                        <select name="product_id" id="product_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Select Product --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Vendor (Optional) -->
                    <div class="mb-4">
                        <label for="vendor_id" class="block text-sm font-medium text-gray-700 mb-1">Vendor (Optional)</label>
                        <select name="vendor_id" id="vendor_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Select Vendor --</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}">{{ $vendor->company_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date -->
                    <x-input label="Date" name="date" type="date" :value="now()->format('Y-m-d')" />

                    <!-- Quantity -->
                    <x-input label="Quantity" name="quantity" type="number" />

                    <!-- Type -->
                    <div class="mb-4">
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select name="type" id="type"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="in">In</option>
                            <option value="out">Out</option>
                        </select>
                    </div>

                    <!-- Stock Type -->
                    <div class="mb-4">
                        <label for="stock_type" class="block text-sm font-medium text-gray-700 mb-1">Stock Type</label>
                        <select name="stock_type" id="stock_type"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="purchase">Purchase</option>
                            <option value="sale">Sale</option>
                            <option value="adjustment">Adjustment</option>
                            <option value="return">Return</option>
                            <option value="damage">Damage</option>
                        </select>
                    </div>

                    <!-- Reference ID -->
                    <x-input label="Reference ID" name="reference_id" />

                    <!-- Note -->
                    <x-input label="Note" name="note" />                
                </div>
                <div class="mt-6 flex justify-end">
                    <button class="bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition duration-150">
                        <i class="fa fa-save"></i> Save
                    </button>
                    <button class="ml-2 bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition duration-150">
                        <i class="fa fa-times"></i> Cancel
                    </button>
                </div>
            </div>    
        </form>    
    </div>
</div>
@push('scripts')
<!-- Select2 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Then Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
    $('#product_id').select2({
        placeholder: "-- Select Product --",
        allowClear: true,
        width: '100%'
    });

    $('#vendor_id').select2({
        placeholder: "-- Select Vendor --",
        allowClear: true,
        width: '100%'
    });
});
</script>
@endpush


@endsection