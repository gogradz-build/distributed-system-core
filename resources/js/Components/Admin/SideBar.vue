<style>
.nav-pills .nav-link.active {
    color: #fff;
    background-color: transparent !important;
}

.side-bar-nav-link {
    box-shadow: none !important;
}

.nav-treeview>.nav-item>.nav-link.active {
    color: #fff !important;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
}
</style>

<template>
    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="index3.html" class="brand-link">
            <img src="/images/main_logo.png" alt="AdminLTE Logo" class="brand-image" style="opacity: 0.8" />
            <span class="brand-text font-weight-light">MAHAJANA HARDWARE</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <!-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="https://adminlte.io/themes/v3/dist/img/user2-160x160.jpg" class="img-circle elevation-2"
                        alt="User Image">
                </div>
                <div class="info">
                    <a href="#" class="d-block">Admin</a>
                </div>
            </div> -->
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                    data-accordion="false">
                    <li class="nav-item">
                        <a :href="route('admin.dashboard')" class="nav-link"
                            :class="{ active: activeItem === 'dashboard' }" @click="setActive('dashboard')">
                            <!-- <i class="fas fa-tachometer-alt"></i> -->
                            <img src="/images/icon/dashboard.svg" alt="">
                            <p class="ml-2">Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item " v-if="['Super Admin', 'admin'].includes(userRole)">
                        <a :href="route('admin.products')" method="post" class="nav-link">
                            <img src="/images/icon/Product.svg" alt="" width="22px" height="22px" />
                            <p class="ml-2">Products
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a :href="route('admin.category')" class="nav-link">
                                    <p class="ml-5">Category</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a :href="route('admin.brands')" class="nav-link">
                                    <p class="ml-5">Brands</p>
                                </a>
                            </li>
                            <!-- <li class="nav-item">
                                <a :href="route('admin.variation')" class="nav-link">
                                    <p class="ml-5">Variation</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a :href="route('admin.variation.type')" class="nav-link">
                                    <p class="ml-5">Variation Type</p>
                                </a>
                            </li> -->
                            <li v-if="['Super Admin', 'admin'].includes(userRole)" class="nav-item">
                                <a :href="route('admin.products')" class="nav-link">
                                    <p class="ml-5">Create Products</p>
                                </a>
                            </li>
                            <li v-if="['Super Admin', 'admin'].includes(userRole)" class="nav-item">
                                <a :href="route('admin.adjustment')" class="nav-link">
                                    <p class="ml-5">Create Adjustment</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a :href="route('admin.warehouse')" method="post" class="nav-link">
                            <img src="/images/icon/warehouse.svg" alt="" width="22px" height="22px" />
                            <p class="ml-2">Warehouse</p>
                        </a>
                    </li>
                    <li v-if="['Super Admin', 'admin'].includes(userRole)" class="nav-item">
                        <a :href="route('admin.showSupplier')" method="post" class="nav-link">
                            <img src="/images/icon/supplier.svg" alt="" width="22px" height="22px" />
                            <p class="ml-2">Supplier</p>
                        </a>
                    </li>
                    <li v-if="['Super Admin', 'admin'].includes(userRole)" class="nav-item">
                        <a :href="route('admin.show.ref')" method="post" class="nav-link">
                            <img src="/images/icon/sale ref.svg" alt="" width="22px" height="22px" />
                            <p class="ml-2">Sales Ref</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a :href="route('admin.show.shop')" method="post" class="nav-link">
                            <img src="/images/icon/shop.svg" alt="" width="22px" height="22px" />
                            <p class="ml-2">Shop</p>
                        </a>
                    </li>
                    <li v-if="['Super Admin', 'admin'].includes(userRole)" class="nav-item ">
                        <a :href="route('admin.perches')" method="post" class="nav-link">
                            <img src="/images/icon/Purchase.svg" alt="" width="22px" height="22px">
                            <p class="ml-2">
                                Purchase
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a :href="route('admin.create.perches')" class="nav-link">
                                    <p class="ml-5">Create Purchase </p>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a :href="route('admin.perches')" class="nav-link">
                                    <p class="ml-5">Purchase</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item ">
                        <a :href="route('admin.Sales')" class="nav-link side-bar-nav-link">
                            <img src="/images/icon/sale.svg" alt="">
                            <p class="ml-2">Sales
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a :href="route('admin.create.Sales')" class="nav-link">
                                    <p class="ml-5">Create Sale</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a :href="route('admin.Sales')" class="nav-link">
                                    <p class="ml-5">Sales Details</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a :href="route('admin.sale.return')" class="nav-link">
                                    <p class="ml-5">Returns</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li v-if="['Super Admin', 'admin'].includes(userRole)" class="nav-item">
                        <a href="#" class="nav-link side-bar-nav-link">
                            <img src="/images/icon/payment.svg" alt="">
                            <p class="ml-2">Payments
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a :href="route('admin.payment.sales')" class="nav-link">
                                    <p class="ml-5">Sales Payments</p>
                                </a>
                            </li>
                            <!-- <li class="nav-item">
                                <a :href="route('admin.payment.shops')" class="nav-link">
                                    <p class="ml-5">Shops Balance </p>
                                </a>
                            </li> -->
                            <li class="nav-item">
                                <a :href="route('admin.payment.purchase')" class="nav-link">
                                    <p class="ml-5">Purchase Payments</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li v-if="['Super Admin', 'admin'].includes(userRole)" class="nav-item">
                        <a :href="route('admin.Sales')" class="nav-link side-bar-nav-link">
                            <img src="/images/icon/report.svg" alt="">
                            <p class="ml-2">Reports
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a :href="route('admin.monthly.sales')" class="nav-link">
                                    <p class="ml-5">Monthly Sales</p>
                                </a>
                            </li>
                            <!-- <li class="nav-item">
                                <a :href="route('admin.outstanding')" class="nav-link">
                                    <p class="ml-5">Outstanding</p>
                                </a>
                            </li> -->
                            <li class="nav-item">
                                <a :href="route('admin.stock')" class="nav-link">
                                    <p class="ml-5">Stock</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a :href="route('admin.collection')" class="nav-link">
                                    <p class="ml-5">Collection</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a :href="route('admin.product.sales')" class="nav-link">
                                    <p class="ml-5">Product Sales</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a :href="route('logout')" method="post" class="nav-link"
                            :class="{ active: activeItem == 'payment' }" @click="setActive('payment')">
                            <img src="/images/icon/logout.svg" alt="">
                            <p class="ml-2">Log out</p>
                        </a>
                    </li>
                    <!-- <li class="nav-item ">
                        <a href="#" class="nav-link" :class="{ active: activeItem == 'setting' }"
                            @click="setActive('setting')">
                            <img src="/images/icon/setting.svg" alt="">
                            <p class="ml-2"> Setting
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="" class="nav-link" :class="{ active: activeItem == 'subject' }"
                                    @click="setActive('subject')">
                                    <p class="ml-5">Email</p>
                                </a>
                            </li>
                        </ul>
                    </li> -->
                </ul>
            </nav>
        </div>
    </aside>
</template>

<script>
import { Link } from "@inertiajs/vue3";
import { ref, onMounted } from 'vue';
import axios from 'axios';

export default {
    setup() {
        const userRole = ref(null);

        onMounted(() => {
            axios.get('/user/role')
                .then(response => {
                    userRole.value = response.data.role;
                })
                .catch(error => {
                    console.error("Error fetching user role:", error);
                });
        });
        return { userRole };
    },

    name: "SideBar",

    data() {
        return {
            activeItem: "dashboard",
        };
    },
    methods: {
        setActive(item) {
            this.activeItem = item;
        },
    },
    components: {
        Link,
    },
};
</script>
