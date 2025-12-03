@extends('layouts.app', ['activePage' => 'items', 'titlePage' => __('Items')])
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card ">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title mt-0">{{ __('Add New Item to Shop') }}</h4>
                        <p class="card-category"> </p>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card ">
                                    <form action="{{ url('items/') }}" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
                                        @csrf
                                        <div class="card-body ">
                                            <div class="form-group bmd-form-group mb-4">
                                                <label>Select Category (<a href="{{url('/item-categories')}}">Create New Category</a>)</label>
                                                <select class="form-select" name="category_id">
                                                    @foreach (getModelList('item-categories') as $item_category)
                                                    <option value="{{ __($item_category->id) }}">{{ __($item_category->name) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group bmd-form-group mb-4">
                                                <label>Name/Description</label>
                                                <input type="text" required name="description" class="form-control" placeholder="Enter Item Description" aria-label="Enter Item Description">
                                            </div>
                                            <div class="form-group bmd-form-group mb-4">
                                                <label>Select Unit of Measurement</label>
                                                <select class="form-select" name="unit_measurement">
                                                    <option value="">--Select--</option>
                                                    <option value="piece">Piece</option>
                                                    <option value="roll">Roll</option>
                                                    <option value="roll">Yard</option>
                                                </select>
                                            </div>
                                            <div class="form-group bmd-form-group mb-4">
                                                <label>For Sale</label>
                                                <input type="checkbox" id="for_sale" name="for_sale" value="1" class="form-check">
                                            </div>
                                            <div id="sell" class="form-group bmd-form-group mb-4" style="display: none;">
                                                <label>Selling Price</label>
                                                <input type="number" name="price" value="0" class="form-control">
                                            </div>
                                            <div class="form-group bmd-form-group mb-4">
                                                <label>Item Variations (Color & Quantity) </label>
                                                <div id="item-variations-container">
                                                    <div class="d-flex mb-3 variation-row">
                                                        <input type="text" required name="variations[0][color]" class="form-control me-2" placeholder="Color (e.g., Red, Blue)" aria-label="Item Color">
                                                        <input type="number" required name="variations[0][qty]" class="form-control" placeholder="Quantity" value="0" aria-label="Variation Quantity">
                                                        <button type="button" class="btn btn-danger btn-sm ms-2 remove-variation">X</button>
                                                    </div>
                                                </div>
                                                <button type="button" id="add-variation-btn" class="btn btn-secondary btn-sm mt-2">+ Add Color/Quantity</button>
                                            </div>

                                            <div class="form-contol mb-4">
                                                <label>Image</label>
                                                @if(auth()->user()->isPremiumUser())
                                                <input type="file" name="image" class="form-control mb-4" placeholder="Image">
                                                @else
                                                <input type="text" readonly class="form-control" placeholder="Image upload only for premium users">
                                                @endif
                                            </div>

                                        </div>
                                        <div class="card-footer ">
                                            <button type="submit" class="btn btn-primary">Create</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
    window.addEventListener('load', function() {
        $("#for_sale").change(function() {
            if ($("#for_sale").prop('checked')) {
                //Show Selling Price
                $('#sell').show();
            } else {
                //Hide Selling Price
                $('#sell').hide();
            }
        });
        const container = document.getElementById('item-variations-container');
        const addButton = document.getElementById('add-variation-btn');
        let variationIndex = 1; // Start index for new rows

        // Initial event listeners for the first row (if any)
        container.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-variation')) {
                // Ensure at least one row remains
                if (container.children.length > 1) {
                    e.target.closest('.variation-row').remove();
                } else {
                    alert("You must have at least one variation.");
                }
            }
        });

        // Function to add a new variation row
        addButton.addEventListener('click', function() {
            const newRow = document.createElement('div');
            newRow.className = 'd-flex mb-3 variation-row';

            // Note the dynamic index [variationIndex]
            newRow.innerHTML = `
            <input type="text" required name="variations[${variationIndex}][color]" class="form-control me-2" placeholder="Color (e.g., Black)" aria-label="Item Color">
            <input type="number" required name="variations[${variationIndex}][qty]" class="form-control" placeholder="Quantity" value="0" aria-label="Variation Quantity">
            <button type="button" class="btn btn-danger btn-sm ms-2 remove-variation">X</button>
        `;

            container.appendChild(newRow);
            variationIndex++;
        });

        // Initialize with one row if the container is empty (optional, based on your initial HTML)
        if (container.children.length === 0) {
            addButton.click(); // Programmatically add the first row
        }
    });
</script>