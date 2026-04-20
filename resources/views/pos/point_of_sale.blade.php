@extends('admin.themes.layouts.main')

@section('title', 'POS')

@section('content')

<style>
    body {
        overflow-y: hidden;
    }

    .item-content {
        overflow-y: scroll !important;
    }

    .product-content {
        max-height: 400px;
        overflow-y: scroll !important;
    }

    .hover-box {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }

    .hover-box:hover {
        transform: scale(1.05);
        box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
        cursor: pointer;
    }
</style>


<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Orders</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a
                            href="{{ action('App\Http\Controllers\AdminController@home') }}">Home</a></li>
                    <li class="breadcrumb-item">Transactions</li>
                    <li class="breadcrumb-item">Point of Sale</li>
                </ol>
            </div>
        </div>
    </div>
</div>

{{-- Main Content --}}
<div class="content">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible" id="success-alert">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fa fa-check"></i> Alert!</h5>
            {{ session('success') }}
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                setTimeout(function () {
                    var alert = document.getElementById('success-alert');
                    if (alert) {
                        alert.style.transition = 'opacity 0.5s ease';
                        alert.style.opacity = '0';
                        setTimeout(function () {
                            alert.remove();
                        }, 500);
                    }
                }, 1000);
            });
        </script>
    @endif
    <div class="row">
        <div class="col-md-8">
            <div class="container-fluid">
                <header>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="info-box bg-gradient-success hover-box">
                                <span class="info-box-icon"><i class="fa fa-peso-sign"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Earnings Today</span>
                                    <span class="info-box-number">₱{{ getTotalTransactionAmountToday() }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="info-box bg-gradient-warning hover-box">
                                <span class="info-box-icon"><i class="fa fa-box-open"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Cash on Hand</span>
                                    <span class="info-box-number">₱{{ getCashOnHandtToday() }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="info-box bg-gradient-info hover-box">
                                <span class="info-box-icon"><i class="fa fa-hashtag"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Transaction Number</span>
                                    <span class="info-box-number">{{ getTransactionNumber() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
                <section>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="d-flex align-items-center">
                                <label for="mcat_id mt-1" style="flex-basis: 20%" class="">Categories: </label>
                                <select class="form-control" name="mcat_id" id="mcat_id">
                                    <option value="">-- ALL --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->mcat_name }}">{{ $category->mcat_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 product-content">
                            <table class="table table-striped table-hover" id="example2">
                                <thead>
                                    <tr>
                                        <th style="text-align: center">Menu Name</th>
                                        <th style="text-align: center">Category</th>
                                        <th style="text-align: center" width="120px">Price</th>
                                        <th style="text-align: center" width="20px">Quantity</th>
                                        <th style="text-align: center" class="text-center">Add</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($menus as $menu)
                                        <tr>
                                            <td style="vertical-align: middle">{{ $menu->menu_name }}</td>
                                            <td style="vertical-align: middle">{{ $menu->mcat_name }}</td>
                                            <td style="vertical-align: middle" width="120px">{{ $menu->menu_price }}</td>
                                            <td style="vertical-align: middle" width="20px">
                                                <input class="form-control item-quantity" type="number" step="1" max="50"
                                                    placeholder="0" data-price="{{ $menu->menu_price }}" required>
                                            </td>
                                            <td style="vertical-align: middle" class="text-center">
                                                <button type="button" class="btn btn-sm btn-primary button-item-add"
                                                    onclick="addItemToOrder('{{ $menu->menu_name }}', '{{ $menu->mcat_name }}', '{{ $menu->menu_price }}')">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <div class="col-md-4">
            <form action="{{ action('App\Http\Controllers\POSController@payment') }}" method="POST">
                @csrf
                <div class="card my-0 py-0" style="min-height: 555px; max-height: 560px">
                    <div class="card-body item-content pt-1">
                        <hr>
                        <label for="">Orders:</label>
                        <hr>
                        <ul>
                            <p class="text-center">No item Selected</p>
                        </ul>
                    </div>
                    <div class="col-md-5 hidden-fields-container">
                        <!-- Hidden fields will be appended here -->
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="">Discount:</label>
                            </div>
                            <div class="col-md-4">
                                <input type="number" style="text-align: center" class="form-control form-control-sm"
                                    name="mtrx_discount_whole" id="mtrx_discount_whole" placeholder="whole">
                            </div>
                            <div class="col-md-4">
                                <input type="number" style="text-align: center" class="form-control form-control-sm"
                                    name="mtrx_discount_percent" id="mtrx_discount_percent" placeholder="%">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-2">
                                <label for="">Cash:</label>
                            </div>
                            <div class="col-md-4">
                                <input type="number" class="form-control form-control-sm" name="mtrx_cash"
                                    id="mtrx_cash">
                            </div>
                            <div class="col-md-2">
                                <label for="">Change:</label>
                            </div>
                            <div class="col-md-4">
                                <input type="number" class="form-control form-control-sm" name="mtrx_change"
                                    id="mtrx_change" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-2">
                                <label for="">Total:</label>
                            </div>
                            <div class="col-md-5">
                                <input type="number" class="form-control form-control-sm" name="mtrx_total"
                                    id="mtrx_total" readonly>
                            </div>
                            <div class="col-md-3">
                                <input type="number" class="form-control form-control-sm" name="mtrx_total_orders"
                                    id="mtrx_total_orders" readonly>
                            </div>
                            <div class="col-md-2">
                                <label for="">orders</label>
                            </div>
                            <div class="col-md-12 mt-2">
                                <button class="btn btn-sm btn-success" id="confirmButton" style="width: 100%"
                                    type="submit">Confirm</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Function to update order list
        function updateOrderList(menu_name, itemPrice, mcat_name, quantity) {
            const orderList = document.querySelector('.item-content ul');

            if (!orderList) {
                console.error('Order list element not found.');
                return;
            }

            let item = Array.from(orderList.children).find(li => {
                const textElement = li.querySelector('.info-box-text');
                return textElement && textElement.textContent.includes(menu_name);
            });

            if (item) {
                const quantityInput = item.querySelector('#mtrxo_order_quantity');
                const priceInput = item.querySelector('#mtrxo_order_price');
                const totalInput = item.querySelector('#mtrxo_total_amount');

                if (quantityInput && priceInput && totalInput) {
                    quantityInput.value = parseInt(quantityInput.value) + parseInt(quantity);
                    totalInput.value = quantityInput.value * priceInput.value;
                    quantityInput.dispatchEvent(new Event('input')); // Trigger the input event to recalculate totals

                    // Update hidden fields
                    updateHiddenFields(menu_name, itemPrice, mcat_name, quantityInput.value, totalInput.value);
                } else {
                    console.error('Quantity, Price, or Total input not found.');
                }
            } else {
                const newItem = document.createElement('li');
                newItem.className = 'ml-0 pl-0';
                newItem.style.listStyle = 'none';

                newItem.innerHTML = `
            <div class="info-box">
                <div class="info-box-content">
                    <span class="info-box-text">${menu_name} - ${mcat_name}</span>
                    <div class="row">
                        <div class="col-md-2">
                            <label for="mtrxo_order_price">Price:</label>
                        </div>
                        <div class="col-md-5">
                            <input type="number" class="form-control form-control-border form-control-sm" id="mtrxo_order_price" value="${itemPrice}" readonly required>
                        </div>
                        <div class="col-md-3">
                            <input type="number" class="form-control form-control-border form-control-sm" id="mtrxo_order_quantity" value="${quantity}" data-price="${itemPrice}" required>
                        </div>
                        <div class="col-md-2">
                            <label for="mtrxo_order_quantity">orders</label>
                        </div>
                        <div class="col-md-2">
                            <label for="mtrxo_total_amount">Total:</label>
                        </div>
                        <div class="col-md-5">
                            <input type="number" class="form-control form-control-border form-control-sm" id="mtrxo_total_amount" value="${quantity * itemPrice}" readonly required>
                        </div>
                        <div class="col-md-5">
                            <input type="hidden" id="prd_id" value="" readonly>
                            <button class="btn btn-sm btn-danger mb-0 pb-0 mt-1" style="width: 100%" type="button" onclick="removeItem(this)"><i class="fa fa-trash" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        `;

                orderList.appendChild(newItem);

                // Add event listener to the new quantity input
                newItem.querySelector('#mtrxo_order_quantity').addEventListener('input', updateTotalForItem);

                // Add hidden fields for the new item
                addHiddenFields(menu_name, itemPrice, mcat_name, quantity, quantity * itemPrice);
            }

            updateTotals();
            toggleNoItemMessage();
        }

        let uniqueId = 0; // Initialize a unique ID counter

        function addHiddenFields(menu_name, itemPrice, mcat_name, quantity, total) {
            const hiddenFieldsContainer = document.querySelector('.hidden-fields-container');
            uniqueId++; // Increment unique ID counter

            const hiddenFieldsHTML = `
                <div class="hidden-fields-item" data-id="${uniqueId}">
                    <input type="hidden" name="menu_name[]" value="${menu_name}">
                    <input type="hidden" name="mcat_name[]" value="${mcat_name}">
                    <input type="hidden" name="mtrxo_order_price[]" value="${itemPrice}">
                    <input type="hidden" name="mtrxo_order_quantity[]" value="${quantity}">
                    <input type="hidden" name="mtrxo_total_amount[]" value="${total}">
                </div>
            `;

            hiddenFieldsContainer.insertAdjacentHTML('beforeend', hiddenFieldsHTML);
        }

        function updateHiddenFields(menu_name, itemPrice, mcat_name, quantity, total) {
            const hiddenFieldsContainer = document.querySelector('.hidden-fields-container');
            const items = Array.from(hiddenFieldsContainer.querySelectorAll(`.hidden-fields-item`));
            const item = items.find(div => {
                const menuNameInput = div.querySelector('input[name="menu_name[]"]');
                return menuNameInput && menuNameInput.value === menu_name;
            });

            if (item) {
                item.querySelector('input[name="mtrxo_order_quantity[]"]').value = quantity;
                item.querySelector('input[name="mtrxo_total_amount[]"]').value = total;
            }
        }

        function removeHiddenFields(menu_name) {
            const hiddenFieldsContainer = document.querySelector('.hidden-fields-container');
            const items = Array.from(hiddenFieldsContainer.querySelectorAll(`.hidden-fields-item`));

            items.forEach(item => {
                const menuNameInput = item.querySelector('input[name="menu_name[]"]');
                if (menuNameInput && menuNameInput.value === menu_name) {
                    item.remove(); // Remove only the specific hidden fields
                }
            });
        }

        // Function to update total for an item
        function updateTotalForItem(event) {
            const quantityInput = event.target;
            const item = quantityInput.closest('li');
            const priceInput = item.querySelector('#mtrxo_order_price');
            const totalInput = item.querySelector('#mtrxo_total_amount');

            if (priceInput && totalInput) {
                totalInput.value = quantityInput.value * priceInput.value;
                updateHiddenFields(item.querySelector('.info-box-text').textContent.split(' - ')[0], item.querySelector('#mtrxo_order_price').value, item.querySelector('.info-box-text').textContent.split(' - ')[1], quantityInput.value, totalInput.value);
                updateTotals(); // Update the grand total
            } else {
                console.error('Price or Total input not found.');
            }
        }

        // Function to update totals
        function updateTotals() {
            const totalQuantity = Array.from(document.querySelectorAll('#mtrxo_order_quantity')).reduce((sum, input) => sum + parseInt(input.value) || 0, 0);
            const grandTotal = Array.from(document.querySelectorAll('#mtrxo_total_amount')).reduce((sum, input) => sum + parseInt(input.value) || 0, 0);

            document.querySelector('#mtrx_total_orders').value = totalQuantity;
            document.querySelector('#mtrx_total').value = grandTotal;

            // Update totals and change based on inputs
            const totalInput = document.getElementById('mtrx_total');
            const cashInput = document.getElementById('mtrx_cash');
            const changeInput = document.getElementById('mtrx_change');
            const discountWholeInput = document.getElementById('mtrx_discount_whole');
            const discountPercentInput = document.getElementById('mtrx_discount_percent');
            const totalOrdersInput = document.getElementById('mtrx_total_orders');
            const confirmButton = document.getElementById('confirmButton');

            // Get the total value and calculate discounts
            let total = parseFloat(totalInput.value) || 0;
            let discountWhole = parseFloat(discountWholeInput.value) || 0;
            let discountPercent = parseFloat(discountPercentInput.value) || 0;

            // Apply whole discount
            if (discountWhole > 0) {
                total -= discountWhole;
            }

            // Apply percentage discount
            if (discountPercent > 0) {
                total -= (total * discountPercent / 100);
            }

            // Update total with discount
            totalInput.value = total.toFixed(2);

            // Update total orders input (if applicable)
            totalOrdersInput.value = totalOrdersInput.value; // Update this with the actual total orders if needed

            // Calculate change if cash is provided
            const cash = parseFloat(cashInput.value) || 0;
            let change = cash - total;

            // Update change input
            changeInput.value = change.toFixed(2);

            // Enable/Disable Confirm button based on conditions
            if (cash > 0 && change >= 0) {
                confirmButton.classList.remove('disabled'); // Remove disabled class
            } else {
                confirmButton.classList.add('disabled'); // Add disabled class
            }
        }

        // Function to toggle no-item message
        function toggleNoItemMessage() {
            const orderList = document.querySelector('.item-content ul');
            const noItemMessage = document.querySelector('.item-content ul p');

            if (orderList.children.length > 0) {
                noItemMessage.style.display = 'none';
            } else {
                noItemMessage.style.display = 'block';
            }
        }

        // Function to add item to order
        function addItemToOrder(menu_name, mcat_name, itemPrice) {
            const quantityInput = event.target.closest('tr').querySelector('.item-quantity');
            const quantity = quantityInput.value;

            if (quantity > 0) {
                updateOrderList(menu_name, itemPrice, mcat_name, quantity);
                quantityInput.value = ''; // Clear the quantity input
            }
        }

        // Function to remove an item
        function removeItem(button) {
            const item = button.closest('li');
            const quantityInput = item.querySelector('#mtrxo_order_quantity');
            const priceInput = item.querySelector('#mtrxo_order_price');
            const totalInput = item.querySelector('#mtrxo_total_amount');
            const menuName = item.querySelector('.info-box-text').textContent.split(' - ')[0]; // Extract menu name for hidden fields

            if (quantityInput && priceInput && totalInput) {
                // Set values to 0 before removing the item
                quantityInput.value = 0;
                priceInput.value = 0;
                totalInput.value = 0;

                // Trigger the input event to update totals
                quantityInput.dispatchEvent(new Event('input'));
            }

            item.remove(); // Remove the item from the DOM

            // Remove the associated hidden fields
            removeHiddenFields(menuName);

            // Update the grand total
            updateTotals();

            // Update the visibility of the no-item message
            toggleNoItemMessage();
        }


        // Attach event listeners to inputs
        const inputs = [
            document.getElementById('mtrx_cash'),
            document.getElementById('mtrx_discount_whole'),
            document.getElementById('mtrx_discount_percent')
        ];

        inputs.forEach(input => {
            input.addEventListener('input', updateTotals);
        });

        // Ensure total is updated when the page loads
        updateTotals();

        // Make functions globally accessible
        window.addItemToOrder = addItemToOrder;
        window.removeItem = removeItem;
    });
</script>

<script>
    // Select Categories
    document.getElementById('mcat_id').addEventListener('change', function () {
        let selectedCategory = this.value;
        let tableRows = document.querySelectorAll('tbody tr');

        tableRows.forEach(row => {
            let categoryCell = row.cells[1]; // The category is in the first cell
            let categoryValue = categoryCell.textContent.trim();

            if (selectedCategory === "" || categoryValue === selectedCategory) {
                row.style.display = ""; // Show row
            } else {
                row.style.display = "none"; // Hide row
            }
        });
    });
</script>

@endsection