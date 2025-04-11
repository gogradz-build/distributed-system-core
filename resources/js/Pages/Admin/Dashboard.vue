<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { onMounted,  nextTick, ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import $ from 'jquery';
import 'datatables.net-bs4';
import 'datatables.net-bs4/css/dataTables.bootstrap4.min.css';

const props = defineProps({
    productCount: Number,
    shopCount : Number,
    refCount : Number,
    supplierCount : Number,
    pradeep: Array,
    rafi: Array,
    susantha: Array
});

const products = ref([]);
const january = ref([{}]);

const pradeepData = computed(() => props.pradeep);
const rafiData = computed(() => props.rafi);
const susanthaData = computed(() => props.susantha);

const fetchProducts = async () => {
    try {
        const response = await axios.get('/products');
        products.value = response.data;
        console.log(response.data);
    } catch (error) {
        console.error('Error fetching courses:', error);
    }
}

var areaChartData = {
      labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
      datasets: [
        {
          label               : 'Pradeep',
          backgroundColor     : 'rgba(60,141,188,0.9)',
          borderColor         : 'rgba(60,141,188,0.8)',
          pointRadius          : false,
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data                : pradeepData.value 
        },
        {
          label               : 'Rafi',
          backgroundColor     : 'rgba(210, 214, 222, 1)',
          borderColor         : 'rgba(210, 214, 222, 1)',
          pointRadius         : false,
          pointColor          : 'rgba(210, 214, 222, 1)',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data                : rafiData.value
        },
        {
          label               : 'Susantha',
          backgroundColor     : 'rgba(250, 214, 222, 1)',
          borderColor         : 'rgba(210, 214, 222, 1)',
          pointRadius         : false,
          pointColor          : 'rgba(210, 214, 222, 1)',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data                : susanthaData.value
        },
      ]
    }
   
onMounted(() => {
    fetchProducts().then(() => {
        initializeDataTable();
    });

    const barCanvas = document.getElementById('barChart').getContext('2d');
    const pieCanvas = document.getElementById('pieChart');

     //-------------
    //- BAR CHART -
    //-------------
    var barChartCanvas = $('#barChart').get(0).getContext('2d')
    var barChartData = $.extend(true, {}, areaChartData)
    var temp0 = areaChartData.datasets[0]
    var temp1 = areaChartData.datasets[1]
    var temp2 = areaChartData.datasets[2]
    barChartData.datasets[0] = temp1
    barChartData.datasets[1] = temp0
    barChartData.datasets[2] = temp2

    var barChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      datasetFill             : false
    }

    new Chart(barChartCanvas, {
      type: 'bar',
      data: barChartData,
      options: barChartOptions
    })

    if (pieCanvas) {
        new Chart(pieCanvas, {
            type: 'pie',
            data:  {
            labels: ['Staples', 'Rivets', 'Bolts', "Nails"],
            datasets: [{
                data: [300, 50, 100, 250],
                backgroundColor: ['#f56954', '#00a65a', '#f39c12', '#f39c12'],
                hoverBackgroundColor: ['#f56954', '#00a65a', '#f39c12', '#f39c12']
            }]
         },
         options: {
            responsive: true,
            maintainAspectRatio: false
        }
        });
    }
});
function initializeDataTable() {
    nextTick(() => {
        if ($.fn.DataTable.isDataTable('#productTable')) {
            $('#productTable').DataTable().destroy();
        }
        $('#productTable').DataTable({
            responsive: true,
            scrollX: true,
            scrollCollapse: true,
            paging: true,
        });
        const successMessage = localStorage.getItem('successMessage');
        if (successMessage) {
            toast.success(successMessage);
            localStorage.removeItem('successMessage');
        }
    });
}
</script>


<template>
     <Head title="Dashboard" />
    <AdminLayout>
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="mt-2">DASHBOARD</h4>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mt-2">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box">
                    <div class="inner">
                        <h3>{{ productCount }}</h3>
                        <p>All Products</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-solid fa-box"></i>
                    </div>
                    <a :href="route('admin.products')" class="small-box-footer">More info<i class="fas fa-arrow-circle-right ml-1"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box">
                    <div class="inner">
                        <h3>{{ shopCount }}</h3>
                        <p>All Shops</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-solid fa-store"></i>
                    </div>
                    <a :href="route('admin.show.shop')" class="small-box-footer">More info <i class="fas fa-arrow-circle-right ml-1"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box ">
                    <div class="inner">
                        <h3>{{ refCount }}</h3>

                        <p>All Sales Ref Members</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-solid fa-user-tie"></i>
                    </div>
                    <a :href="route('admin.show.ref')" class="small-box-footer">More info <i class="fas fa-arrow-circle-right ml-1"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box">
                    <div class="inner">
                        <h3>{{ supplierCount }}</h3>
                        <p>All Suppliers</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-solid fa-users"></i>
                    </div>
                    <a :href="route('admin.showSupplier')" class="small-box-footer">More info <i class="fas fa-arrow-circle-right ml-1"></i></a>
                </div>
            </div>
            <!-- ./col -->
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">REF SALES</h3>

                      <!-- <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                          <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                          <i class="fas fa-times"></i>
                        </button>
                      </div> -->
                    </div>
                    <div class="card-body">
                      <div class="chart"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                      <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 562px;" width="562" height="250" class="chartjs-render-monitor"></canvas>
                      </div>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <!--  -->
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="card card-secondary">
                    <div class="card-body">
                        <h6 style="font-weight: 700;" class="">STOCK TABLE</h6>
                        <hr>
                        <!-- Add ID to the table -->
                        <table id="productTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 8%;">#</th>
                                    <th style="width: 8%;">Product Code</th>
                                    <th style="width: 40%;">Product Name</th>
                                    <th style="width: 10%;">Brand Name</th>
                                    <th style="width: 10%;">Price (Rs.)</th>
                                    <th style="width: 10%;">Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(product, index) in products" :key="product.id">
                                    <td>{{ 1 + index }}</td>
                                    <td>{{ product.code }}</td>
                                    <td>{{ product.name }}</td>
                                    <td>{{ product.brand.name }}</td>
                                    <td>{{ product.price }}</td>
                                    <td>{{ product.warehouse_stocks.length > 0 ? product.warehouse_stocks[0].quantity :
                                        0 }}</td>
                                    <!-- <td>
                                        <span class="badge badge-pill badge-success"
                                            v-if="staffMember.is_active === 1">Active</span>
                                        <span class="badge badge-pill badge-success" v-else>Inactive</span>
                                    </td> -->
                                    
                                </tr>
                            </tbody>
                        </table>
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
<style scoped>
    .fas{
        font-family: "Font Awesome 5 Free" !important;
    }
    .small-box, .fas{
        color: #006769 !important;
    }
    .small-box>.small-box-footer{
        background-color: #006769 !important;
    }
    .small-box>.small-box-footer:hover{
        background-color: #01787a !important;
    }
    .small-box-footer i{
        color: white !important;
    }
   

</style>