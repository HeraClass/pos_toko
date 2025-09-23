import React, { Component } from "react";
import { createRoot } from "react-dom/client";
import axios from "axios";
import Swal from "sweetalert2";
import { sum } from "lodash";

class Cart extends Component {
    constructor(props) {
        super(props);
        this.state = {
            cart: [],
            products: [],
            customers: [],
            barcode: "",
            search: "",
            customer_id: "",
            translations: {},
            loading: false
        };

        // Binding methods
        this.loadCart = this.loadCart.bind(this);
        this.handleOnChangeBarcode = this.handleOnChangeBarcode.bind(this);
        this.handleScanBarcode = this.handleScanBarcode.bind(this);
        this.handleChangeQty = this.handleChangeQty.bind(this);
        this.handleEmptyCart = this.handleEmptyCart.bind(this);
        this.loadProducts = this.loadProducts.bind(this);
        this.handleChangeSearch = this.handleChangeSearch.bind(this);
        this.handleSeach = this.handleSeach.bind(this);
        this.setCustomerId = this.setCustomerId.bind(this);
        this.handleClickSubmit = this.handleClickSubmit.bind(this);
        this.loadTranslations = this.loadTranslations.bind(this);
        this.handleClickDelete = this.handleClickDelete.bind(this);
        this.addProductToCart = this.addProductToCart.bind(this);
    }

    componentDidMount() {
        this.loadTranslations();
        this.loadCart();
        this.loadProducts();
        this.loadCustomers();
    }

    loadTranslations() {
        axios
            .get("/admin/locale/cart")
            .then((res) => {
                this.setState({ translations: res.data });
            })
            .catch((error) => {
                console.error("Error loading translations:", error);
            });
    }

    loadCustomers() {
        axios.get(`/admin/customers`).then((res) => {
            this.setState({ customers: res.data });
        });
    }

    loadProducts(search = "") {
        this.setState({ loading: true });
        const query = search ? `?search=${search}` : "";
        axios.get(`/admin/products${query}`).then((res) => {
            this.setState({ products: res.data.data, loading: false });
        }).catch(() => {
            this.setState({ loading: false });
        });
    }

    handleOnChangeBarcode(event) {
        this.setState({ barcode: event.target.value });
    }

    loadCart() {
        axios.get("/admin/cart").then((res) => {
            this.setState({ cart: res.data });
        });
    }

    handleScanBarcode(event) {
        event.preventDefault();
        const { barcode } = this.state;
        if (barcode) {
            axios
                .post("/admin/cart", { barcode })
                .then(() => {
                    this.loadCart();
                    this.setState({ barcode: "" });
                })
                .catch((err) => {
                    Swal.fire("Error!", err.response.data.message, "error");
                });
        }
    }

    handleChangeQty(product_id, qty) {
        const newQty = parseInt(qty) || 0;

        if (newQty < 0) return;

        const cart = this.state.cart.map((c) => {
            if (c.id === product_id) {
                c.pivot.quantity = newQty;
            }
            return c;
        });

        this.setState({ cart });

        if (newQty === 0) {
            this.handleClickDelete(product_id);
            return;
        }

        axios
            .post("/admin/cart/change-qty", { product_id, quantity: newQty })
            .catch((err) => {
                Swal.fire("Error!", err.response.data.message, "error");
                this.loadCart(); // Reload cart to sync with server
            });
    }

    getTotal(cart) {
        const total = cart.map((c) => c.pivot.quantity * c.price);
        return sum(total).toFixed(2);
    }

    handleClickDelete(product_id) {
        Swal.fire({
            title: this.state.translations["confirm_delete"] || "Are you sure?",
            text: this.state.translations["delete_product_warning"] || "This product will be removed from cart",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: this.state.translations["delete"] || 'Delete',
            cancelButtonText: this.state.translations["cancel"] || 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                axios
                    .post("/admin/cart/delete", { product_id, _method: "DELETE" })
                    .then(() => {
                        const cart = this.state.cart.filter((c) => c.id !== product_id);
                        this.setState({ cart });
                    });
            }
        });
    }

    handleEmptyCart() {
        Swal.fire({
            title: this.state.translations["confirm_empty_cart"] || "Are you sure?",
            text: this.state.translations["empty_cart_warning"] || "All items will be removed from cart",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: this.state.translations["empty_cart"] || 'Empty Cart',
            cancelButtonText: this.state.translations["cancel"] || 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                axios.post("/admin/cart/empty", { _method: "DELETE" }).then(() => {
                    this.setState({ cart: [] });
                });
            }
        });
    }

    handleChangeSearch(event) {
        this.setState({ search: event.target.value });
    }

    handleSeach(event) {
        if (event.keyCode === 13) {
            this.loadProducts(event.target.value);
        }
    }

    addProductToCart(barcode) {
        let product = this.state.products.find((p) => p.barcode === barcode);
        if (product) {
            if (product.quantity <= 0) {
                Swal.fire("Error!", "Product out of stock", "error");
                return;
            }

            axios
                .post("/admin/cart", { barcode })
                .then(() => {
                    this.loadCart();
                })
                .catch((err) => {
                    Swal.fire("Error!", err.response.data.message, "error");
                });
        }
    }

    setCustomerId(event) {
        this.setState({ customer_id: event.target.value });
    }

    handleClickSubmit() {
        const { cart, translations } = this.state;
        const invalidItems = cart.filter(c => c.pivot.quantity > c.quantity);

        if (invalidItems.length > 0) {
            Swal.fire("Error!", "Some products exceed available stock", "error");
            return;
        }

        if (cart.length === 0) {
            Swal.fire("Error!", "Cart is empty", "error");
            return;
        }

        const total = this.getTotal(cart);

        Swal.fire({
            title: translations["received_amount"] || "Payment Amount",
            input: "number",
            inputValue: total,
            inputAttributes: {
                step: "0.01",
                min: "0"
            },
            cancelButtonText: translations["cancel_pay"] || "Cancel",
            showCancelButton: true,
            confirmButtonText: translations["confirm_pay"] || "Confirm Payment",
            showLoaderOnConfirm: true,
            preConfirm: (amount) => {
                if (amount < total) {
                    Swal.showValidationMessage("Amount cannot be less than total");
                    return false;
                }
                return axios
                    .post("/admin/orders", {
                        customer_id: this.state.customer_id,
                        amount,
                    })
                    .then((res) => {
                        this.loadCart();
                        return res.data;
                    })
                    .catch((err) => {
                        Swal.showValidationMessage(err.response.data.message);
                    });
            },
            allowOutsideClick: () => !Swal.isLoading(),
        }).then((result) => {
            if (result.value) {
                Swal.fire({
                    title: translations["payment_success"] || "Success!",
                    text: translations["order_created"] || "Order created successfully",
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            }
        });
    }

    renderProductItem(p) {
        const isLowStock = window.APP.warning_quantity > p.quantity;
        const isOutOfStock = p.quantity <= 0;

        return (
            <div
                onClick={() => !isOutOfStock && this.addProductToCart(p.barcode)}
                key={p.id}
                className={`product-item ${isOutOfStock ? 'out-of-stock' : ''} ${isLowStock ? 'low-stock' : ''}`}
            >
                <div className="product-image">
                    <img src={p.image_url || '/images/placeholder-product.png'} alt={p.name} />
                    {isOutOfStock && <div className="stock-badge">Out of Stock</div>}
                    {isLowStock && !isOutOfStock && <div className="stock-badge warning">Low Stock</div>}
                </div>
                <div className="product-info">
                    <h5>{p.name}</h5>
                    <div className="product-details">
                        <p className="price">{window.APP.currency_symbol} {p.price}</p>
                    </div>
                    <div className="product-stock">
                        <p className="stock">Stock: {p.quantity}</p>
                    </div>
                </div>
            </div>
        );
    }

    renderCartItem(c) {
        const isExceedingStock = c.pivot.quantity > c.quantity;

        return (
            <tr key={c.id} className={isExceedingStock ? 'table-warning' : ''}>
                <td>
                    <div className="product-name">{c.name}</div>
                    {isExceedingStock && (
                        <small className="text-danger">Exceeds available stock</small>
                    )}
                </td>
                <td>
                    <div className="quantity-controls">
                        <button
                            className="btn btn-sm btn-outline-secondary"
                            onClick={() => this.handleChangeQty(c.id, c.pivot.quantity - 1)}
                            disabled={c.pivot.quantity <= 1}
                        >
                            -
                        </button>
                        <input
                            type="number"
                            className="form-control form-control-sm qty-input"
                            value={c.pivot.quantity}
                            min="0"
                            onChange={(event) => this.handleChangeQty(c.id, event.target.value)}
                        />
                        <button
                            className="btn btn-sm btn-outline-secondary"
                            onClick={() => this.handleChangeQty(c.id, c.pivot.quantity + 1)}
                            disabled={c.pivot.quantity >= c.quantity}
                        >
                            +
                        </button>
                    </div>
                </td>
                <td className="text-right">
                    {window.APP.currency_symbol} {(c.price * c.pivot.quantity).toFixed(2)}
                </td>
                <td>
                    <button
                        className="btn btn-danger btn-sm"
                        onClick={() => this.handleClickDelete(c.id)}
                        title="Remove item"
                    >
                        <i className="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        );
    }

    render() {
        const { cart, products, customers, barcode, translations, loading } = this.state;
        const total = this.getTotal(cart);

        return (
            <div className="cart-container">
                <div className="row">
                    {/* Left Column - Cart */}
                    <div className="col-md-5 col-lg-4">
                        <div className="cart-section">
                            <div className="section-header">
                                <h4>{translations["shopping_cart"] || "Shopping Cart"}</h4>
                            </div>

                            {/* Customer Selection */}
                            <div className="customer-selection mb-3">
                                <select
                                    className="form-control"
                                    onChange={this.setCustomerId}
                                    value={this.state.customer_id}
                                >
                                    <option value="">{translations["general_customer"] || "General Customer"}</option>
                                    {customers.map((cus) => (
                                        <option key={cus.id} value={cus.id}>
                                            {`${cus.first_name} ${cus.last_name}`}
                                        </option>
                                    ))}
                                </select>
                            </div>

                            {/* Barcode Scanner */}
                            <form onSubmit={this.handleScanBarcode} className="barcode-scanner mb-3">
                                <div className="input-group">
                                    <input
                                        type="text"
                                        className="form-control"
                                        placeholder={translations["scan_barcode"] || "Scan barcode..."}
                                        value={barcode}
                                        onChange={this.handleOnChangeBarcode}
                                        autoFocus
                                    />
                                    <div className="input-group-append">
                                        <button className="btn btn-outline-primary" type="submit">
                                            <i className="fas fa-barcode"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>

                            {/* Cart Items */}
                            <div className="cart-items">
                                <div className="card">
                                    <div className="card-body p-0">
                                        {cart.length === 0 ? (
                                            <div className="empty-cart text-center p-4">
                                                <i className="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                                <p>{translations["cart_empty"] || "Your cart is empty"}</p>
                                            </div>
                                        ) : (
                                            <div className="table-responsive">
                                                <table className="table table-hover mb-0">
                                                    <thead className="thead-light">
                                                        <tr>
                                                            <th>{translations["product_name"] || "Product"}</th>
                                                            <th>{translations["quantity"] || "Qty"}</th>
                                                            <th className="text-right">{translations["price"] || "Price"}</th>
                                                            <th width="60"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        {cart.map((c) => this.renderCartItem(c))}
                                                    </tbody>
                                                </table>
                                            </div>
                                        )}
                                    </div>
                                </div>
                            </div>

                            {/* Cart Summary */}
                            {cart.length > 0 && (
                                <div className="cart-summary">
                                    <div className="total-section p-3 bg-light rounded">
                                        <div className="d-flex justify-content-between align-items-center mb-2">
                                            <strong>{translations["subtotal"] || "Subtotal"}:</strong>
                                            <strong>{window.APP.currency_symbol} {total}</strong>
                                        </div>
                                        <div className="row">
                                            <div className="col-6">
                                                <button
                                                    type="button"
                                                    className="btn btn-outline-danger btn-block"
                                                    onClick={this.handleEmptyCart}
                                                >
                                                    <i className="fas fa-trash mr-2"></i>
                                                    {translations["cancel"] || "Cancel"}
                                                </button>
                                            </div>
                                            <div className="col-6">
                                                <button
                                                    type="button"
                                                    className="btn btn-primary btn-block"
                                                    onClick={this.handleClickSubmit}
                                                >
                                                    <i className="fas fa-credit-card mr-2"></i>
                                                    {translations["checkout"] || "Checkout"}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            )}
                        </div>
                    </div>

                    {/* Right Column - Products */}
                    <div className="col-md-7 col-lg-8">
                        <div className="products-section">
                            <div className="section-header d-flex justify-content-between align-items-center mb-3">
                                <h4>{translations["products"] || "Products"}</h4>
                                <div className="search-box" style={{ width: '300px' }}>
                                    <div className="input-group">
                                        <input
                                            type="text"
                                            className="form-control"
                                            placeholder={translations["search_product"] + "..." || "Search products..."}
                                            onChange={this.handleChangeSearch}
                                            onKeyDown={this.handleSeach}
                                        />
                                        <div className="input-group-append">
                                            <span className="input-group-text">
                                                <i className="fas fa-search"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {/* Products Grid */}
                            <div className="products-grid">
                                {loading ? (
                                    <div className="text-center p-4">
                                        <i className="fas fa-spinner fa-spin fa-2x"></i>
                                        <p>Loading products...</p>
                                    </div>
                                ) : products.length === 0 ? (
                                    <div className="text-center p-4">
                                        <i className="fas fa-box-open fa-3x text-muted mb-3"></i>
                                        <p>{translations["no_products"] || "No products found"}</p>
                                    </div>
                                ) : (
                                    <div className="row">
                                        {products.map((p) => (
                                            <div key={p.id} className="col-sm-6 col-md-4 col-lg-3 mb-3">
                                                {this.renderProductItem(p)}
                                            </div>
                                        ))}
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>
                </div>

                {/* Add some custom CSS */}
                <style>{`
                    .cart-container {
                        padding: 20px;
                    }
                    .section-header {
                        border-bottom: 2px solid #dee2e6;
                        padding-bottom: 10px;
                        margin-bottom: 20px;
                    }
                    .product-item {
                        border: 1px solid #dee2e6;
                        border-radius: 8px;
                        padding: 15px;
                        text-align: center;
                        cursor: pointer;
                        transition: all 0.3s ease;
                        height: 100%;
                    }
                    .product-item:hover {
                        transform: translateY(-2px);
                        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                    }
                    .product-item.out-of-stock {
                        opacity: 0.6;
                        cursor: not-allowed;
                    }
                    .product-item.low-stock {
                        border-color: #ffc107;
                    }
                    .product-image {
                        position: relative;
                        margin-bottom: 10px;
                    }
                    .product-image img {
                        width: 100%;
                        height: 120px;
                        object-fit: cover;
                        border-radius: 4px;
                    }
                    .stock-badge {
                        position: absolute;
                        top: 5px;
                        right: 5px;
                        background: #dc3545;
                        color: white;
                        padding: 2px 6px;
                        border-radius: 3px;
                        font-size: 10px;
                    }
                    .stock-badge.warning {
                        background: #ffc107;
                        color: #212529;
                    }
                    .product-info h5 {
                        font-size: 14px;
                        margin-bottom: 8px;
                        height: 40px;
                        overflow: hidden;
                    }
                    .product-details, .product-stock {
                        align-items: center;
                    }
                    .price {
                        font-weight: bold;
                        color: #28a745;
                    }
                    .stock {
                        font-size: 12px;
                        color: #6c757d;
                    }
                    .quantity-controls {
                        display: flex;
                        align-items: center;
                        gap: 5px;
                    }
                    .qty-input {
                        width: 60px;
                        text-align: center;
                    }
                    .empty-cart {
                        color: #6c757d;
                    }
                    .table-warning {
                        background-color: #fff3cd !important;
                    }
                `}</style>
            </div>
        );
    }
}

export default Cart;

const root = document.getElementById("cart");
if (root) {
    const rootInstance = createRoot(root);
    rootInstance.render(<Cart />);
}